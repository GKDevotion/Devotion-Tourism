<?php

namespace App\Exports;

use App\Models\Continent;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClientsContinentBaseExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        // $continentObj = Continent::all();
        // $returnArr = [];
        
        // foreach( $continentObj as $continent ){
        //     $returnArr[] = new ClientsExport( $continent->id, $continent->name );
        // }

        return [
            new ClientsExport( 1, 'Africa' ),
            new ClientsExport( 2, 'America' ),
            new ClientsExport( 3, 'Asia' ),
            new ClientsExport( 4, 'Europe' ),
            new ClientsExport( 5, 'Eceania' ),
            new ClientsExport( 6, 'Polar' ),
            // $returnArr
            // new UsersExport(),  // Export Users data
            // new OrdersExport(), // Export Orders data
        ];
    }
}
