@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Tipos de Ocorrências</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Tipo de Ocorrência">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Tipos Ocorrências</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form action="/tiposOcorrencias" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Digite o código da Ocorrência" required>
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Digite a Descricao da Ocorrência" required>
                                <br/>
                                <label for="tipo">Tipo</label>
                                    <select id="tipo" name="tipo" required>
                                        <option value="">Selecione o tipo</option>
                                            <option value="despontuacao">Despontuação</option>
                                            <option value="elogio">Elogio</option>
                                    </select>
                                <br/>
                                <label for="pontuacao">Pontuação</label>
                                <input type="text" class="form-control" name="pontuacao" id="pontuacao" placeholder="Digite a pontuação" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                        <button type="cancel" class="btn btn-danger btn-sn">Cancelar</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
            @if(count($tipos)==0)
                <div class="alert alert-danger" role="alert">
                    Sem tipos cadastrados!
                </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Pontuação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tipos as $tipo)
                    <tr>
                        <td>{{$tipo->codigo}}</td>
                        <td>{{$tipo->descricao}}</td>
                        <td>@if($tipo->tipo=='despontuacao') Despontuação @else Elogio @endif</td>
                        <td>{{$tipo->pontuacao}}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$tipo->id}}">
                                Editar
                            </button>
                            
                            <div class="modal fade" id="exampleModal{{$tipo->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Tipo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/tiposOcorrencias/editar/{{$tipo->id}}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="codigo">Código</label>
                                                <input type="text" class="form-control" name="codigo" id="codigo" value="{{$tipo->codigo}}" required>
                                                <label for="descricao">Descrição</label>
                                                <input type="text" class="form-control" name="descricao" id="descricao" value="{{$tipo->descricao}}" required>
                                                <label for="tipo">Tipo</label>
                                                    <select id="tipo" name="tipo" required>
                                                        <option value="{{$tipo->tipo}}">Selecione o tipo</option>
                                                            <option value="despontuacao">Despontuação</option>
                                                            <option value="elogio">Elogio</option>
                                                    </select>
                                                <label for="pontuacao">Pontuação</label>
                                                <input type="text" class="form-control" name="pontuacao" id="pontuacao" value="{{$tipo->pontuacao}}" required>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                        <button type="cancel" class="btn btn-danger btn-sn">Cancelar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                            <a href="/tiposOcorrencias/apagar/{{$tipo->id}}" class="btn btn-sm btn-danger">Apagar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
    <br>
    <a href="/admin/administrativo" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection