<html>
    <head>
        <style>
            table{
                border: 1px solid #000;
                width: 100%;
            }
            td, th{
                padding: 5px;
                border: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%">
            {{-- <tr style="">
                <td colspan="{{count( $data[0] ) + count( $data[0]['companyBankAccountObj'] ?? [] ) - 1 }}" style="text-align: center; font-weight: bold;">
                    Current Balance: {{number_format( (float)$companyObj->balance, 2 )}} AED | Total Credited: {{number_format( (float)$companyObj->total_credit, 2 )}} AED | Total Debited: {{number_format( (float)$companyObj->total_debit, 2 )}} AED
                </td>
            </tr>
            <tr style="">
                <td colspan="{{count( $data[0] )}}"></td>
            </tr> --}}

            @if( COUNT( $data ) > 0 )
                <thead>
                    <tr style="background-color: #ab8134; color: #ffffff; ">
                        <th colspan="{{count( $data[0] ) - 1 }}"></th>

                        @if( $cashAvailable  )
                            <th colspan="3" style="text-align: center; font-weight: bold;">Cash</th>
                        @endif

                        @if( isset( $data[0]['companyBankAccountObj'] ) )
                            @foreach ( $companyBankAccountObj as $bank )
                                <th colspan="3" style="text-align: center; font-weight: bold;">{{pgTitle( $bank )}}</th>
                            @endforeach
                        @endif
                    </tr>
                    <tr style="background-color: #ab8134; color: #ffffff;">
                        <th style="text-align: center; font-weight: bold;">Serial Number</th>
                        <th style="text-align: center; font-weight: bold;">Date</th>
                        <th style="text-align: center; font-weight: bold;">Username</th>
                        <th style="text-align: center; font-weight: bold;">Payment Type</th>
                        <th style="text-align: center; font-weight: bold;">Company Code</th>
                        <th style="text-align: center; font-weight: bold;">Company Name</th>
                        <th style="text-align: center; font-weight: bold;">Description</th>
                        <th style="text-align: center; font-weight: bold;">Remark</th>

                        @if( $cashAvailable  )
                            <th style="text-align: center; font-weight: bold;">Credit</th>
                            <th style="text-align: center; font-weight: bold;">Debit</th>
                            <th style="text-align: center; font-weight: bold;">Balance</th>
                        @endif

                        @if( isset( $data[0]['companyBankAccountObj'] ) )
                            @foreach ( $companyBankAccountObj as $bank )
                                <th style="text-align: center; font-weight: bold;">Credit</th>
                                <th style="text-align: center; font-weight: bold;">Debit</th>
                                <th style="text-align: center; font-weight: bold;">Balance</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>{{ $row['serial_number'] }}</td>
                            <td>{{ formatDate( 'd-m-Y', $row['date'] ) }}</td>
                            <td>{{ $row['user_name'] }}</td>
                            <td>{{ $row['payment_type'] }}</td>
                            <td>{{ $row['company_code'] }}</td>
                            <td>{{ $row['company_name'] }}</td>
                            <td>{{ $row['description'] }}</td>
                            <td>{{ $row['remarks'] }}</td>

                            @if( $cashAvailable )
                                <td>{{ number_format( $row['cash_credit'], 2 ) }}</td>
                                <td>{{ number_format( $row['cash_debit'], 2 ) }}</td>
                                <td>{{ number_format( (float)$row['cash_balance'], 2 ) }}</td>
                            @endif

                            @if( isset( $row['companyBankAccountObj'] ) )
                                @foreach ( $row['companyBankAccountObj'] as $name=>$amount )
                                    <td>{{ number_format( $amount, 2 ) }}</td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @else
                <tbody>
                    <tr>
                        <td style="text-align: center;">No data available for your current selection. Try choosing a different option.</td>
                    </tr>
                </tbody>
            @endif
        </table>
    </body>
</html>
