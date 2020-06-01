@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Controle de Estoque</h5>
                    <p class="card-text">
                        Cadastre e Monitore seus produtos e categorias
                    </p>
                    <a href="/estoque" class="btn btn-primary">Controle de Estoque</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Atividades Extras (AE)</h5>
                    <p class="card-text">
                        Acompanhar e baixar os arquivos de questões das AEs
                    </p>
                    <a href="/atividadeExtra" class="btn btn-primary">Controle de AEs</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Disciplinas</h5>
                    <p class="card-text">
                        Consultar e cadastrar Disciplinas
                    </p>
                    <a href="/disciplinas" class="btn btn-primary">Disciplinas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
