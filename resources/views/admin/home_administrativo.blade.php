@extends('layouts.app', ["current"=>"administrativo"])

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
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Tipos de Ocorrências</h5>
                    <p class="card-text">
                        Consultar e cadastrar Tipos de Ocorrências
                    </p>
                    <a href="/tiposOcorrencias" class="btn btn-primary">Tipos de Ocorrências</a>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="card-deck">
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Alunos</h5>
                    <p class="card-text">
                        Consultar e cadastrar Alunos
                    </p>
                    <a href="/aluno/consulta" class="btn btn-primary">Alunos</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Professores</h5>
                    <p class="card-text">
                        Consultar e cadastrar Professores
                    </p>
                    <a href="/prof/consulta" class="btn btn-primary">Professores</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Outros</h5>
                    <p class="card-text">
                        Consultar e cadastrar Outros
                    </p>
                    <a href="/outro/consulta" class="btn btn-primary">Outros</a>
                </div>
            </div>
            <div class="card border border-primary">
                <div class="card-body">
                    <h5>Admin</h5>
                    <p class="card-text">
                        Cadastrar Admin
                    </p>
                    <a href="/admin/novo" class="btn btn-primary">Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection