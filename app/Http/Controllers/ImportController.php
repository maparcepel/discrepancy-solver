<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Illuminate\Support\Str;
use App\Imports\DiscrepanceImport;
use App\Models\Discrepance;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportController extends Controller
{
    public function importDiscrepances ()
    {
        Excel::import(new DiscrepanceImport, 'discrepancias_Precios_ES.csv', 'public');
        
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

    public function createFeeds ()
    {
        $folderName = 'public/' . Carbon::now()->toDateString() . '-prices/';

        $centers = Discrepance::select('center')->where('center', '<>', 'Centro')->distinct()->pluck('center');
        echo $centers;

        foreach ($centers as $center) {
            $discrepances = Discrepance::where('center', $center)->get();
    
            $discrepances = $discrepances->map(function ($discrepancy) {
                $price = preg_replace('/\D+$/u', '', $discrepancy->price_label); //Delete text characters
                if($price == ''){
                    $prices = [
                        'price_label' => $discrepancy->offer_price_label,
                        'price' => $discrepancy->offer_price
                    ];
                }else{
                    $prices = [
                        'price_label' => $discrepancy->price_label,
                        'offer_price_label' => $discrepancy->offer_price_label,
                        'price' => str_replace(',', '.', $price),
                        'offer_price' => $discrepancy->offer_price,
                    ];
                }
    
                return [
                    'product_id' => $discrepancy->reference,
                    'metadata' => [
                        'country' => '011',
                        'gourmet' => false,
                    ],
                    'operation' => [
                        'operation' => 'prices',
                    ],
                    'prices' => $prices,
                    'is_published' => true,
                ];
            });
    
            $feeds = $discrepances->map(function ($discrepancy) {
                $json = json_encode($discrepancy);
                return Str::replace('\\u20ac', '€', $json);
            })->implode("\n");
        
            Storage::put($folderName . $center . '.txt', $feeds);
        
        }
        return response()->json('Precios organizados y guardados');
    }
}
