@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
        <div class="card-deck">
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Estoque</h5>
                        <p class="card-text">
                            Gerenciar Produtos, Categorias, Entrada&Saídas, entre outros.
                        </p>
                        <a href="/outro/estoque" class="btn btn-primary">Estoque</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Ficha de Sala (Diário)</h5>
                        <p class="card-text">
                            Lançamentos de Diário
                        </p>
                        <a href="/outro/diario" class="btn btn-primary">Diário</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
