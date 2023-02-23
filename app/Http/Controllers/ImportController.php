<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use File;

use App\Imports\DiscrepanceImport;

use App\Models\Discrepance;

use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function importDiscrepances ()
    {
        Excel::import(new DiscrepanceImport, 'discrepancias.csv', 'public');
        
        return 'Discrepancias importadas y organizadas';
    }

    public function look () 
    {
        $zips = Storage::disk('folders')->allFiles();

        $zips = array_reverse($zips);
        
        $file = fopen(storage_path("app/public/folders/" . $zips[2]), "r");

        $arrayFile = array();

        while(!feof($file)) {
            array_push($arrayFile, fgets($file));
        }
        
        fclose($file);

        return $arrayFile;
    }

    public function organiceCenter ($center)
    {

        $discrepances = Discrepance::where('center', $center)->get();

        $arrayFile = $this->getArrayFile();

        $finalArray = array();
        /* return $arrayFile; */
        foreach($discrepances as $discrepance)
        {
            foreach ($arrayFile as $key => $value) {
                if($value)
                {
                    if ($value->product_id == $discrepance->reference) {

                        if(isset($value->offer_price)) {
                            $value->prices->price = $discrepance->null_price;
                            $value->prices->price_label = $discrepance->null_price;
                            $value->prices->offer_price = $discrepance->web_price;
                            $value->prices->offer_price_label = $discrepance->label_price;
                        } else {
                            $value->prices->price = $discrepance->web_price;
                            $value->prices->price_label = $discrepance->label_price;
                        }

                        array_push($finalArray, $value);
                    }
                }
            }

        }
        $data = json_encode($finalArray);

        $data = explode('[', $data);
        $data = explode(']', $data[1]);

        $data = str_replace('},{', '} \n {', $data);

        /* return explode('},{', $data[0]); */

        /* return $data; */

        /* Storage::put('attempt3.txt', nl2br($data[0])); */

        echo nl2br($data[0]);
        
    }

    public function getArrayFile ()
    {
        $file = fopen(storage_path("app/public/010006.txt"), "r");

        $arrayFile = array();

        while(!feof($file)) {
            array_push($arrayFile, fgets($file));
        }
        
        fclose($file);

        $processedArray = array();

        for ($i=0; $i < count($arrayFile); $i++) { 
            $csv = json_decode($arrayFile[$i]);

            array_push($processedArray, $csv);
        }

        return $processedArray;
    }
}
