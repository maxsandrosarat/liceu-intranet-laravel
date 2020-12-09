@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Listas de Compra</h5>
            <a type="button" class="float-button" href="/admin/listaCompras/nova" data-toggle="tooltip" data-placement="bottom" title="Nova Lista">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if(count($listaProds)==0)
                <div class="alert alert-danger" role="alert">
                    Sem listas cadastradas!
                </div>
            @else
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Usuário</th>
                        <th>Criação</th>
                        <th>Produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaProds as $lista)
                    <tr>
                        <td>{{$lista->id}}</td>
                        <td>{{$lista->usuario}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($lista->created_at))}}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal{{$lista->id}}">
                            Produtos
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$lista->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Produtos da Lista {{$lista->id}} - {{date("d/m/Y", strtotime($lista->created_at))}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        @foreach ($lista->produtos as $produto)
                                        <li>{{$produto->nome}}</li>
                                        <br/>
                                        @endforeach
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </div>
                        </td>
                        <td>
                            <a target="_blank" href="/admin/listaCompras/pdf/{{$lista->id}}" class="badge badge-success">Gerar PDF</a>
                            <button type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$lista->id}}"><i class="material-icons md-18">delete</i></button></td>
                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$lista->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Lista Nº {{$lista->id}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>Tem certeza que deseja excluir essa lista?</h5>
                                            <p>Não será possivel reverter esta ação.</p>
                                            <a href="/admin/listaCompras/apagar/{{$lista->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="card-footer">
                {{ $listaProds->links() }}
            </div>
            @endif
        </div>

    </div>
    <br>
    <a href="/admin/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection