<?php

namespace App\Imports;

use App\Aluno;
use App\Turma;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlunoImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if($key>=1){
                if($row[0]==null){
                    
                }else{
                    $aluno = new Aluno();
                    $aluno->name = $row[0];
                    $aluno->email = $row[1];
                    $aluno->password = Hash::make($row[2]);
                    $serie = $row[3];
                    $turma = $row[4];
                    $turno = $row[5];
                    $ensino = $row[6];
                    $turmas = DB::table('turmas')->select(DB::raw("id"))->where('serie',"$serie")->where('turma',"$turma")->where('turno',"$turno")->where('ensino',"$ensino")->get();
                    foreach($turmas as $turma){
                        $turmaId = $turma->id;
                    }
                    $turma_id = $turmaId;
                    $aluno->turma_id = $turma_id;
                    $aluno->save();
                }
            }
        }
    }
}
