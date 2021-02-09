<?php

namespace App\Exports;

use App\Aluno;
use Maatwebsite\Excel\Concerns\FromCollection;

class AlunoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Aluno::all();
    }
}
