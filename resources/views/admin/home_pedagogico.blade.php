@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Atividades Extras</h5>
                    <p class="card-text">
                        Consultar e cadastrar Atividades Extras
                    </p>
                    <a href="/atividadeExtra" class="btn btn-primary">Atividades Extras</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Listas Atividades</h5>
                    <p class="card-text">
                        Consultar e cadastrar Listas Atividades
                    </p>
                    <a href="/admin/listaAtividade" class="btn btn-primary">Listas Atividades</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Atividades</h5>
                    <p class="card-text">
                        Consultar e cadastrar Atividades
                    </p>
                    <a href="/admin/atividade" class="btn btn-primary">Atividades</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Ocorrências</h5>
                    <p class="card-text">
                        Consultar ocorrências
                    </p>
                    <a href="/admin/ocorrencias" class="btn btn-primary">Ocorrências</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Conteúdos</h5>
                    <p class="card-text">
                        Consultar conteúdos
                    </p>
                    <a href="/admin/conteudos/{{date("Y")}}" class="btn btn-primary">Conteúdos</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection