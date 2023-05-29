<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['name', 'weight', 'type'];

    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }
}
