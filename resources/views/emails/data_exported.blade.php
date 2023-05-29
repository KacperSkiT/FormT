<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Nowa przesyłka</title>

   <style>
      body {
         font-family: Arial, sans-serif;
         font-size: 16px;
         line-height: 1.5;
         color: #333;
         margin: 0;
         padding: 0;
      }

      h2 {
         font-size: 24px;
         font-weight: bold;
         margin-bottom: 20px;
      }

      table {
         max-width: 100%;
         border-collapse: collapse;
         margin: 20px 5px 5px 5px;
      }

      th {
         font-weight: bold;
         background-color: #f2f2f2;
         padding: 8px;
         text-align: left;
      }

      td {
         padding: 8px;
         border: 1px solid #ccc;
      }

      tbody tr:nth-child(even) {
         background-color: #f2f2f2;
      }

      strong {
         font-weight: bold;
      }
   </style>

</head>
<body>
   <h2>Transport</h2>

   <p><strong>Transport z:</strong> {{ $data['from'] }}</p>
   <p><strong>Transport do:</strong> {{ $data['to'] }}</p>
   <p><strong>Rodzaj samolotu:</strong> {{ $data['plane'] }}</p>
   <p><strong>Data transportu:</strong> {{ $data['date'] }}</p>

   <h3>Ładunki:</h3>
   <table>
         <thead>
            <tr>
               <th>Nazwa ładunku</th>
               <th>Typ ładunku</th>
               <th>Ciężar łądunku</th>
            </tr>
         </thead>
         <tbody>
            @foreach($data['items'] as $item)
            <tr>
               <td>{{ $item['name'] }}</td>
               <td>{{ $item['type'] }}</td>
               <td>{{ $item['weight'] }} kg</td>
            </tr>
            @endforeach
         </tbody>
   </table>

</body>
</html>
