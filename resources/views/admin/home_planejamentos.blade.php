@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <p>{{session('mensagem')}}</p>
            </div>
            @endif
            @if(count($planejamentos)==0)
                <div class="alert alert-danger" role="alert">
                    Sem planejamentos cadastrados!
                </div>
            @else
            <form action="/admin/planejamentos" method="GET">
                @csrf
                <label for="ano">Selecione o ano:
                <select class="custom-select" id="ano" name="ano">
                    <option value="">Selecione</option>
                    @foreach ($anos as $an)
                    <option value="{{$an->ano}}">{{$an->ano}}</option>
                    @endforeach
                  </select></label>
                <input type="submit" class="btn btn-primary" value="Selecionar">
            </form>
            <h5 class="card-title">Planejamentos - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Ano</th>
                        <th>Série(s)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planejamentos as $plan)
                    <tr>
                        <td>{{$plan->id}}</td>
                        <td>{{$plan->descricao}}</td>
                        <td>{{$plan->ano}}</td>
                        <td>
                            <ul>
                            @foreach ($plan->series as $serie)
                                <li>{{$serie->serie}}º ANO</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="/admin/planejamentos/painel/{{$plan->id}}" class="badge badge-primary" data-toggle="tooltip" data-placement="right" title="Painel"><i class="material-icons md-48">attach_file</i></a>
                            <button type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$plan->id}}"><i class="material-icons md-48">delete</i></button></td>
                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$plan->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Excluir Prova: {{$plan->descricao}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>Tem certeza que deseja excluir esse planejamento?</h5>
                                            <p>Não será possivel reverter esta ação.</p>
                                            <a href="/admin/planejamentos/apagar/{{$plan->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
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
            @endif
        </div>
    </div>
    <br>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="habilitarSubmit();">
        Gerar Painel
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Gerar Painel</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <form action="/admin/planejamentos/gerar" method="POST">
                    @csrf
                <label for="descricao">Descrição: </label>
                <input class="form-control" type="text"  name="descricao" id="descricao" required>
                <label for="ano">Ano: </label>
                <input class="form-control" type="number"  name="ano" id="ano" required>
                <br/><br/>
                <button type="submit" id="processamento" class="btn btn-primary">Gerar</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    </div>
    <br><br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection