@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Listas de Compra</h5>
            <a type="button" class="float-button" href="/outro/listaCompras/nova" data-toggle="tooltip" data-placement="bottom" title="Nova Lista">
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
                        <th>PDF</th>
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
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal{{$lista->id}}">
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
                            <a target="_blank" href="/outro/listaCompras/pdf/{{$lista->id}}" class="btn btn-sm btn-success">Gerar PDF</a>
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
    <a href="/outro/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection