<?php

namespace App\Http\Controllers;

use App\Mail\DataExported;
use App\Models\Item;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Summary of TransportController
 */
class TransportController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function sendEmail(array $validatedData, $attachment): void
    {
        $data = [
            'from' => $validatedData['from'],
            'to' => $validatedData['to'],
            'plane' => $validatedData['plane'],
            'date' => $validatedData['date'],
            'items' => array_map(function ($index) use ($validatedData) {
                return [
                    'name' => $validatedData['name'][$index],
                    'weight' => $validatedData['weight'][$index],
                    'type' => $validatedData['type'][$index],
                ];
            }, array_keys($validatedData['name'])),
        ];

        $mail = new DataExported($data);
        if ($attachment) {
            $mail->attach($attachment->getRealPath(), [
                'as' => $attachment->getClientOriginalName(),
                'mime' => $attachment->getMimeType(),
            ]);
        }

        // Wyślij e-mail na odpowiedni adres
        if ($validatedData['plane'] === 'Airbus A380') {
            Mail::to('airbus@lemonmind.com')->send($mail);
        } elseif ($validatedData['plane'] === 'Boeing 747') {
            Mail::to('boeing@lemonmind.com')->send($mail);
        }
    }

    public function store(Request $request)
    {
        $weightLimit = $this->getWeightLimit($request->plane);

        // Walidacja danych przesłanych przez formularz
        $validatedData = $request->validate([
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'plane' => ['nullable', 'required_if:weight.*,null', Rule::in(['Airbus A380', 'Boeing 747'])],
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = date('N', strtotime($value));
                    if ($dayOfWeek === '6' || $dayOfWeek === '7') {
                        $fail(__('validation.date_not_allowed'));
                    }
                },
            ],
            'documents.*' => 'nullable|file|mimes:jpg,png,doc,docx,pdf',
            'name.*' => 'required|string|max:255',
            'weight.*' => [
                'required',
                'numeric',
                'required_if:plane,Airbus A380,Boeing 747',
                function ($attribute, $value, $fail) use ($request) {
                    $minWeight = $this->getMinWeightLimit($request->plane);
                    $maxWeight = $this->getMaxWeightLimit($request->plane);
                    if ($value < $minWeight || $value > $maxWeight) {
                        $fail(__('validation.between.numeric', [
                            'min' => $minWeight,
                            'max' => $maxWeight,
                        ]));
                    }
                },
            ],
            'type.*' => 'required|string',
        ]);

        $attachment = null;
        if ($request->hasFile('documents')) {
            $attachment = $request->file('documents')[0];
        }

        if ($request->hasFile('documents')) {
            $attachmentPaths = [];
            foreach ($request->file('documents') as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath = '/'.$fileName;

                $file->store('public');

                $attachmentPaths[] = storage_path('app/public/'.$filePath);
            }

            $documents = json_encode($attachmentPaths);
        } else {
            $documents = null;
        }

        $transport = new Transport();
        $transport->from = $validatedData['from'];
        $transport->to = $validatedData['to'];
        $transport->plane = $validatedData['plane'];
        $transport->date = $validatedData['date'];
        $transport->documents = $documents;
        $transport->save();

        foreach (array_keys($validatedData['name']) as $index) {
            $item = new Item();
            $item->name = $validatedData['name'][$index];
            $item->weight = $validatedData['weight'][$index];
            $item->type = $validatedData['type'][$index];
            $transport->items()->save($item);
        }

        $this->sendEmail($validatedData, $attachment);

        return redirect('/')->with('success', 'Formularz został wysłany.');
    }

    private function getWeightLimit(string $plane): array
    {
        return match ($plane) {
            'Airbus A380' => ['min' => 1, 'max' => 35000],
            'Boeing 747' => ['min' => 1, 'max' => 38000],
            default => ['min' => 0, 'max' => 0],
        };
    }

    private function getMaxWeightLimit(string $plane): int
    {
        return $this->getWeightLimit($plane)['max'] ?? 0;
    }

    private function getMinWeightLimit(string $plane): int
    {
        return $this->getWeightLimit($plane)['min'] ?? 0;
    }
}
