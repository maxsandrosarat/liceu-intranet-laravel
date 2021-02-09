<?php

namespace App\Exports;

use App\Aluno;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AlunoExportView implements FromView
{
    public function view(): View
    {
        return view('admin.alunosTable', [
            'alunos' => Aluno::all()
        ]);
    }
}
