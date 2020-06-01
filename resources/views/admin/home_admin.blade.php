@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Disciplinas</h5>
                    <p class="card-text">
                        Consultar e cadastrar Disciplinas
                    </p>
                    <a href="/disciplinas" class="btn btn-primary">Disciplinas</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Turmas</h5>
                    <p class="card-text">
                        Consultar e cadastrar Turmas
                    </p>
                    <a href="/turmas" class="btn btn-primary">Turmas</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Turmas&Disciplinas</h5>
                    <p class="card-text">
                        Consultar e cadastrar Disciplinas das Turmas
                    </p>
                    <a href="/turmasDiscs" class="btn btn-primary">Turmas&Disciplinas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
