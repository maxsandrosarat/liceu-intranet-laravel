@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Lista de Atividades</h5>
                    <p class="card-text">
                        baixar os arquivos de Atividades
                    </p>
                    <a href="/aluno/disciplinas" class="btn btn-primary">Lista de Atividades</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
