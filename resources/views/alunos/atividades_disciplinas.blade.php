@extends('layouts.app', ["current"=>"atividade"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Disciplinas - Turma: {{$turma->serie}} º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif) - Atividades</h5>
            @if(count($turmaDiscs)==0)
                <div class="alert alert-danger" role="alert">
                    Sem disciplinas cadastradas!
                </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center;">Disciplinas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turmaDiscs as $turmaDisc)
                        @foreach ($turmaDisc->disciplinas as $disc)
                        <tr>
                            <td>
                                <a href="/aluno/atividade/{{$disc->id}}" class="btn btn-primary btn-lg btn-block">{{$disc->nome}}
                                    @foreach ($profs as $prof)
                                        @if($prof->disciplina_id==$disc->id)<b style="font-size: 80%;">(Prof. {{$prof->prof->name}})</b> @endif
                                    @endforeach
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    <br/>
    <a href="/aluno" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection