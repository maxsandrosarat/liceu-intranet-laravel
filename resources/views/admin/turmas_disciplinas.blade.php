@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Turmas</h5>
            <a type="button" class="float-button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Disciplinas para as Turmas">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if(count($turmaDiscs)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem disciplinas para as turmas cadastradas!
                </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Turma</th>
                        <th>Disciplina</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turmaDiscs as $turmaDisc)
                    <tr>
                        <td>{{$turmaDisc->serie}}º ANO {{$turmaDisc->turma}} (@if($turmaDisc->turno=='M') Matutino @else @if($turmaDisc->turno=='V') Vespertino @else Noturno @endif @endif)</td>
                        <td>
                            <ul>
                            @foreach ($turmaDisc->disciplinas as $disciplina)
                            <li>{{$disciplina->nome}} <a href="/turmasDiscs/apagar/{{$turmaDisc->id}}/{{$disciplina->id}}" class="btn btn-danger btn-sm">Apagar</a></li>
                            <br/>
                            @endforeach
                            </ul>
                        </td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    <br>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Disciplinas para Turmas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="card border">
                        <div class="card-body">
                            <form action="/turmasDiscs" method="POST" style="text-align: center;">
                                @csrf
                                <div class="form-group">
                                    <label for="turma" class="col-md-4 col-form-label text-md-right">Turma</label>
                                    <br/>
                                    <select id="turma" name="turma" required>
                                        <option value="">Selecione a turma</option>
                                        @foreach ($turmas as $turma)
                                        <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                        @endforeach
                                    </select>
                                    <br/>
                                    <h3>Marque Todas as Disciplinas</h3>
                                    <input type="checkbox" id="todasFund" name="disciplina" value="todasFund">
                                    <label for="todasFund">Todas Fundamental</label>
                                    <input type="checkbox" id="todasMedio" name="disciplina" value="todasMedio">
                                    <label for="todasMedio">Todas Médio</label>
                                    <hr/>
                                    <h3>OU</h3>
                                    <hr/>
                                    <h3>Marque as Disciplinas</h3>
                                    @foreach ($discs as $disc)
                                    <input type="checkbox" id="disciplina{{$disc->id}}" name="disciplinas[]" value="{{$disc->id}}">
                                    <label for="disciplina{{$disc->id}}">{{$disc->nome}} (@if($disc->ensino=="fund") Fundamental @else Médio @endif)</label>
                                    <br>
                                    @endforeach
                                <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>    
                </div> 
            </div>
            </div>
        </div>
    </div>
</div>
<br/>
    <a href="/admin/administrativo" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection