@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Lista de Atividades</h5>
                    <p class="card-text">
                        Anexar e baixar os arquivos de Listas de Atividades
                    </p>
                    <a href="/prof/listaAtividade" class="btn btn-primary">Lista Atividades</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Ocorrências</h5>
                    <p class="card-text">
                        Anexar e baixar os arquivos de Atividades
                    </p>
                    <a href="/prof/ocorrencias" class="btn btn-primary">Ocorrências</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Conteúdos</h5>
                    <p class="card-text">
                        Anexar e baixar os conteúdos
                    </p>
                    <a href="/prof/conteudos/{{date("Y")}}" class="btn btn-primary">Atividades</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
