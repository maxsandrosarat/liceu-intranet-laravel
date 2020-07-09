@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Recados</h5>
            <!-- Button trigger modal -->
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Recado">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                </div>
            @endif
            @if(count($recados)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem recados cadastrados! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/recados" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/recados/filtro">
                        @csrf
                        <input class="form-control mr-sm-2" type="text" placeholder="Titulo" name="titulo">
                        <label for="dataInicio">Data Início</label>
                        <input class="form-control mr-sm-2" type="date" name="dataInicio">
                        <label for="dataFim">Data Fim</label>
                        <input class="form-control mr-sm-2" type="date" name="dataFim">
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
                        <th>Titulo</th>
                        <th>Descrição</th>
                        <th>Geral</th>
                        <th>Turma</th>
                        <th>Aluno</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recados as $recado)
                    <tr>
                        <td>{{$recado->id}}</td>
                        <td>{{$recado->titulo}}</td>
                        <td><button type="button" class="badge badge-primary" data-toggle="modal" data-target="#exampleModalDesc{{$recado->id}}">Descrição</button></td>
                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModalDesc{{$recado->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{$recado->titulo}} - {{date("d/m/Y", strtotime($recado->created_at))}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{$recado->descricao}}
                            </div>
                            </div>
                        </div>
                        </div>
                        <td>@if($recado->geral==1) SIM @else NÃO @endif</td>
                        <td>@if($recado->turma=="") @else {{$recado->turma->serie}}º ANO {{$recado->turma->turma}} @endif</td>
                        <td>@if($recado->aluno=="") @else {{$recado->aluno->name}} @endif</td>
                        <td>{{date("d/m/Y", strtotime($recado->created_at))}}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$recado->id}}" data-toggle="tooltip" data-placement="left" title="Editar">
                                <i class="material-icons md-48">edit</i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$recado->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Recado</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border">
                                            <div class="card-body">
                                                <form action="/admin/recados/editar/{{$recado->id}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="titulo">Titulo Recado</label>
                                                        <input type="text" class="form-control" name="titulo" id="titulo" value="{{$recado->titulo}}" required>
                                                        <label for="descricao">Descrição
                                                        <textarea class="form-control" name="descricao" id="descricao" rows="10" cols="60" required>{{$recado->descricao}}</textarea></label>
                                                        <h5>Geral?</h5>
                                                        <input type="radio" id="sim" name="geral" value="1" @if($recado->geral=="1") checked @endif required>
                                                        <label for="sim">Sim</label>
                                                        <input type="radio" id="nao" name="geral" value="0" @if($recado->geral=="0") checked @endif required>
                                                        <label for="nao">Não</label>
                                                        <br/>
                                                        <select class="custom-select" id="turma" name="turma" required>
                                                            <option value="@if($recado->turma=="") @else {{$recado->turma->id}} @endif">@if($recado->turma=="")Selecione @else {{$recado->turma->serie}}º ANO {{$recado->turma->turma}}@endif</option>
                                                            @foreach ($turmas as $turma)
                                                                @if($recado->turma!="" && $turma->id==$recado->turma->id)
                                                                @else
                                                                <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <br/>
                                                        <h5>Ou</h5>
                                                        <select class="custom-select" id="aluno" name="aluno" required>
                                                            <option value="@if($recado->aluno=="") @else {{$recado->aluno->id}} @endif">@if($recado->aluno=="")Selecione @else {{$recado->aluno->name}}@endif</option>
                                                            @foreach ($alunos as $aluno)
                                                                @if($recado->aluno!="" && $aluno->id==$recado->aluno->id)
                                                                @else
                                                                <option value="{{$aluno->id}}">{{$aluno->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                            <a href="/admin/recados/apagar/{{$recado->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Excluir"><i class="material-icons md-48">delete</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="card-footer">
                {{$recados->links() }}
            </div>
            @endif
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Novo Recado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="card border">
                <div class="card-body">
                    <form action="/admin/recados" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="titulo">Titulo Recado</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Digite o titulo" required>
                            <label for="descricao">Descrição
                            <textarea class="form-control" name="descricao" id="descricao" rows="10" cols="60" required></textarea></label>
                            <br/>
                            <label for="selectGeral">Geral?</label>
                            <select class="custom-select" name="geral" id="selectGeral">
                                <option value="">Selecione</option>
                                <option value="1">SIM</option>
                                <option value="0">NÃO</option>
                            </select>
                            <br/>
                            <div id="principalSelect">
                                <div id="1">
                                </div>
                                <div id="0">
                                    <br/>
                                    <select class="custom-select" id="turma" name="turma">
                                        <option value="">Selecione uma turma</option>
                                        @foreach ($turmas as $turma)
                                            <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                        @endforeach
                                    </select>
                                    <br/>
                                    <h5>Ou</h5>
                                    <select class="custom-select" id="aluno" name="aluno">
                                        <option value="">Selecione um aluno</option>
                                            @foreach ($alunos as $aluno)
                                                <option value="{{$aluno->id}}">{{$aluno->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
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
    <br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection