<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #000;
            text-align: center;
            font-weight: normal;
        }

        th {
            background-color: #ab8134;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table>
        @if (count($data) > 0)
            <thead>
                {{-- Dynamic Header Row --}}
                <tr>
                    <th colspan="{{ count($data[0]) - 1 }}">
                        &nbsp;
                    </th>

                    @if ($cashAvailable)
                        <th colspan="3">Cash</th>
                    @endif

                    @foreach ($companyBankAccountObj ?? [] as $bank)
                        <th colspan="3">{{ pgTitle($bank) }}</th>
                    @endforeach
                </tr>

                {{-- Column Titles --}}
                <tr>
                    @php
                        $headers = [
                            'Serial Number',
                            'Date',
                            'Username',
                            'Payment Type',
                            'Company Code',
                            'Company Name',
                            'Description',
                            'Remark',
                        ];
                    @endphp

                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach

                    @if ($cashAvailable)
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                    @endif

                    @foreach ($companyBankAccountObj ?? [] as $bank)
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row['serial_number'] }}</td>
                        <td>{{ formatDate('d-m-Y', $row['date']) }}</td>
                        <td>{{ $row['user_name'] }}</td>
                        <td>{{ $row['payment_type'] }}</td>
                        <td>{{ $row['company_code'] }}</td>
                        <td>{{ $row['company_name'] }}</td>
                        <td>{{ $row['description'] }}</td>
                        <td>{{ $row['remarks'] }}</td>

                        @if ($cashAvailable)
                            <td>{{ number_format($row['cash_credit'], 2) }}</td>
                            <td>{{ number_format($row['cash_debit'], 2) }}</td>
                            <td>{{ number_format($row['cash_balance'], 2) }}</td>
                        @endif

                        @foreach ($row['companyBankAccountObj'] ?? [] as $amount)
                            <td>{{ number_format($amount, 2) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="100%" style="text-align: center;">
                        No data available for your current selection. Try choosing a different option.
                    </td>
                </tr>
            </tbody>
        @endif
    </table>

</body>

</html>
