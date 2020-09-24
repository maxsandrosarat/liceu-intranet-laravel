@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Relatório de Entradas/Saídas</h5>
            <!-- Button trigger modal -->
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Lançar Entrada e Saída">
                <i class="material-icons blue md-60">add_circle</i>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lançamento de Entrada e Saída</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/admin/entradaSaida" method="POST">
                        @csrf
                        <div class="form-group">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" type="radio" id="entrada" name="tipo" value="entrada">
                                <label class="form-check-label" for="entrada">
                                Entrada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" type="radio" id="saida" name="tipo" value="saida">
                                <label class="form-check-label" for="entrada">
                                Saída
                                </label>
                            </div>
                            <br>
                            <label for="produtos">Produto</label>
                            <select class="custom-select" id="produtos" name="produto" required>
                                <option value="">Selecione</option>
                                @foreach ($prods as $prod)
                                    <option value="{{$prod->id}}">{{$prod->nome}} - Estoque: {{$prod->estoque}}</option>
                                @endforeach
                            </select>
                            <br><br>
                            <label for="qtd">Quantidade</label>
                            <input class="form-control" type="number" class="form-control" name="qtd" id="qtd" placeholder="Digite a quantidade">
                            <br>
                            <label for="req">Requisitante</label>
                            <input class="form-control" type="text" class="form-control" name="req" id="req" placeholder="Digite o requisitante">
                            <br>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                </div>
                </form>
                </div>
            </div>
            </div>
            @if(count($rels)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem movimentos cadastrados! Faça novo movimento no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @else @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/entradaSaida" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/entradaSaida/filtro">
                @csrf
                <label for="tipo">Tipo</label>
                <select class="custom-select" id="tipo" name="tipo">
                    <option value="">Selecione o tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
                <label for="produto">Produto
                <select class="custom-select" id="produto" name="produto">
                    <option value="">Selecione</option>
                    @foreach ($prods as $prod)
                        <option value="{{$prod->id}}">{{$prod->nome}}</option>
                    @endforeach
                </select></label>
                <label for="dataInicio">Data Início
                <input class="form-control" type="date" name="dataInicio"></label>
                <label for="dataFim">Data Fim
                <input class="form-control" type="date" name="dataFim"></label>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
            </div>
            <div class="table-responsive-xl">
            <table id="yesprint" class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Movimento</th>
                        <th>Tipo Movimento</th>
                        <th>Nome Produto</th>
                        <th>Quantidade</th>
                        <th>Requisitante</th>
                        <th>Usuário</th>
                        <th>Data & Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    @if($rel->tipo=='entrada') <tr style="color:blue;"> @else <tr style="color:red;"> @endif
                        <td>{{$rel->id}}</td>
                        <td>@if($rel->tipo=='entrada') Entrada @else Saída @endif</td>
                        <td>{{$rel->produto_nome}}</td>
                        <td>{{$rel->quantidade}}</td>
                        <td>{{$rel->requisitante}}</td>
                        <td>{{$rel->usuario}}</td>
                        <td>{{date("d/m/Y H:i", strtotime($rel->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
			<div class="card-footer">
            {{ $rels->links() }}
			</div>
            @endif
        </div>
        
        </div>
    </div>
    <br/>
    <a href="/admin/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection