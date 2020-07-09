@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Ocorrências</h5>
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                </div>
            @endif
            @if(count($ocorrencias)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem ocorrências cadastradas!
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/ocorrencias" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/ocorrencias/filtro">
                        @csrf
                        <label for="tipo">Tipo
                        <select class="custom-select" id="tipo" name="tipo" style="width:170px;">
                            <option value="">Selecione o tipo</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->codigo}} - {{$tipo->descricao}} - @if($tipo->tipo=="despontuacao") Despontuar: @else Elogio: @endif{{$tipo->pontuacao}}</option>
                            @endforeach
                        </select></label>
                        <label for="aluno">Aluno
                        <select class="custom-select" id="aluno" name="aluno" style="width:170px;">
                            <option value="">Selecione o aluno</option>
                            @foreach ($alunos as $aluno)
                            <option value="{{$aluno->id}}">{{$aluno->name}} - {{$aluno->turma->serie}}º ANO {{$aluno->turma->turma}}</option>
                            @endforeach
                        </select></label>
                        <label for="dataInicio">Data Início
                        <input class="form-control" type="date" name="dataInicio"></label>
                        <label for="dataFim">Data Fim
                        <input class="form-control" type="date" name="dataFim"></label>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <br/>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Aluno</th>
                        <th>Turna</th>
                        <th>Disciplina</th>
                        <th>Data</th>
                        <th>Observação</th>
                        <th>Aprovação</th>
                        <th>Resp. Ciente</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ocorrencias as $ocorrencia)
                    <tr>
                        <td>{{$ocorrencia->tipo_ocorrencia->codigo}}</td>
                        <td>{{$ocorrencia->aluno->name}}</td>
                        <td>{{$ocorrencia->aluno->turma->serie}}º ANO {{$ocorrencia->aluno->turma->turma}}</td>
                        <td>{{$ocorrencia->disciplina->nome}}</td>
                        <td>{{date("d/m/Y", strtotime($ocorrencia->data))}}</td>
                        <td>
                            @if($ocorrencia->observacao=="")
                            <h6 style="color: red;">Sem observação</h6>
                            @else
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModalOb{{$ocorrencia->id}}">
                                Ver Observação
                            </button>
                            @endif
                            <div class="modal fade" id="exampleModalOb{{$ocorrencia->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Observação</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{$ocorrencia->observacao}}</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($ocorrencia->aprovado==1)
                                <b><p style="color: green; font-size: 50%;"><i class="material-icons green">check_circle</i> APROVADO</p></b>
                            @else
                                @if($ocorrencia->aprovado!==NULL)
                                    <b><p style="color: red; font-size: 50%;"><i class="material-icons green">highlight_off</i>REPROVADO</p></b>
                                @else
                                    <a href="/admin/ocorrencias/aprovar/{{$ocorrencia->id}}" class="btn btn-sm btn-success">Aprovar</a>
                                    <a href="/admin/ocorrencias/reprovar/{{$ocorrencia->id}}" class="btn btn-sm btn-danger">Reprovar</a>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($ocorrencia->responsavel_ciente==1) SIM @else NÃO @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="card-footer">
                {{$ocorrencias->links() }}
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection