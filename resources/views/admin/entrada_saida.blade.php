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
                    <form action="/entradaSaida" method="POST">
                        @csrf
                        <div class="form-group">
                            <br>
                            <input type="radio" id="entrada" name="tipo" value="entrada">
                            <label for="entrada">Entrada</label>
                            <input type="radio" id="saida" name="tipo" value="saida">
                            <label for="saida">Saída</label>
                            <br><br>
                            <label for="produtos">Produto</label>
                            <select id="produtos" name="produto" required>
                                <option value="">Selecione</option>
                                @foreach ($prods as $prod)
                                    <option value="{{$prod->id}}">{{$prod->nome}}</option>
                                @endforeach
                            </select>
                            <br><br>
                            <label for="qtd">Quantidade</label>
                            <input type="number" class="form-control" name="qtd" id="qtd" placeholder="Digite a quantidade">
                            <br>
                            <label for="req">Requisitante</label>
                            <input type="text" class="form-control" name="req" id="req" placeholder="Digite o requisitante">
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
                    <div class="alert alert-danger" role="alert">
                        @if($busca=="nao")
                        Sem movimentos cadastrados!
                        @else @if($busca=="sim")
                        Sem resultados da busca!
                        <a href="/entradaSaidaRel" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card border">
            <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/entradaSaida/filtro_entradaSaida">
                @csrf
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="">Selecione o tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
                <label for="produto">Produto</label>
                <input class="form-control mr-sm-2" type="text" for="produto" placeholder="Código do Produto" name="produto">
                <label for="dataInicio">Data Início</label>
                <input class="form-control mr-sm-2" type="date" name="dataInicio">
                <label for="dataFim">Data Fim</label>
                <input class="form-control mr-sm-2" type="date" name="dataFim">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
            </div>
            <table id="yesprint" class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Movimento</th>
                        <th>Tipo Movimento</th>
                        <th>Código Produto</th>
                        <th>Nome Produto</th>
                        <th>Quantidade</th>
                        <th>Requisitante</th>
                        <th>Usuário</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    @if($rel->tipo=='entrada') <tr style="color:blue;"> @else <tr style="color:red;"> @endif
                        <td>{{$rel->id}}</td>
                        <td>@if($rel->tipo=='entrada') Entrada @else Saída @endif</td>
                        <td>{{$rel->produto_id}}</td>
                        <td>{{$rel->produto_nome}}</td>
                        <td>{{$rel->quantidade}}</td>
                        <td>{{$rel->requisitante}}</td>
                        <td>{{$rel->usuario}}</td>
                        <td>{{date("d/m/Y", strtotime($rel->data))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        <div class="card-footer">
            {{ $rels->links() }}
        </div>
    </div>
    <br/>
    <a href="/admin/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection