<table style=" border:1px solid black;border-collapse:collapse">
    <thead>
        <tr style="background: #edb28d">
            <th style=" border:1px solid black;width: 400px;font-weight: bold;text-align: center">
                Line
            </th>
            <th style=" border:1px solid black;width: 400px;font-weight: bold;text-align: center">
                Column
            </th>
            <th style=" border:1px solid black;width: 400px;font-weight: bold;text-align: center">
                Error message
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($errors as $failure)
            <tr>
                <td style=" border:1px solid black;text-align: center;font-weight: bold">
                    {{ $failure->row() }}
                </td>
                <td style=" border:1px solid black;text-align: center;font-weight: bold">
                    {{ $failure->attribute() }}
                </td>
                <td style=" border:1px solid black;text-align: center;color:#e16666;font-weight: bold">
                    {{ $failure->errors()[0] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
