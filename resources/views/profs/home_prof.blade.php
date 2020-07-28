@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
        <div class="card-deck">
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Atividades</h5>
                        <p class="card-text">
                            Gerenciar as Atividades
                        </p>
                        <a href="/prof/atividade/disciplinas" class="btn btn-primary">Atividades</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Lista de Atividades</h5>
                        <p class="card-text">
                            Gerenciar as Lista de Atividades
                        </p>
                        <a href="/prof/listaAtividade" class="btn btn-primary">LAs</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Ocorrências</h5>
                        <p class="card-text">
                            Lançar as Ocorrências
                        </p>
                        <a href="/prof/ocorrencias/disciplinas" class="btn btn-primary">Ocorrências</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Conteúdos</h5>
                        <p class="card-text">
                            Anexar e baixar os conteúdos
                        </p>
                        <a href="/prof/conteudos/{{date("Y")}}" class="btn btn-primary">Conteúdos</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
