<?php

namespace App\Imports;

use App\Models\Discrepance;
use Maatwebsite\Excel\Concerns\ToModel;

class DiscrepanceImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Discrepance([
            'reference' => $row[0],
            'center' => $row[1],
            'offer_price' => $row[2],
            'offer_price_label' => $row[3],
            'price_label' => $row[4],
        ]);
    }
}
