<?php

namespace App\Imports;

use App\Outro;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;

class OutroImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //dd($collection);
        foreach ($collection as $key => $row) {
            if($key>=1){
                if($row[0]==null){
                    
                }else{
                    $outro = new Outro();
                    $outro->name = $row[0];
                    $outro->email = $row[1];
                    $outro->password = Hash::make($row[2]);
                    $outro->save();
                }
            }
        }
    }
}
