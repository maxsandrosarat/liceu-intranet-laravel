@extends('layouts.app', ["current"=>"estoque"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Relatório de Entrada/Saída</h5>
                    <p class="card-text">
                        Veja suas entradas e saídas até o momento
                    </p>
                    <a href="/entradaSaida" class="btn btn-primary">Relatório</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Lista de Compras</h5>
                    <p class="card-text">
                        Selecione produtos e gere uma lista de compras
                    </p>
                    <a href="/listaCompras" class="btn btn-primary">Lista de Compras</a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Produtos</h5>
                    <p class="card-text">
                        Consulte e Cadastre seus produtos
                    </p>
                    <a href="/produtos" class="btn btn-primary">Produtos</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Categorias</h5>
                    <p class="card-text">
                        Consulte e Cadastre suas categorias
                    </p>
                    <a href="/categorias" class="btn btn-primary">Categorias</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection