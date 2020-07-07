@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Disciplinas</h5>
                        <p class="card-text">
                            Gerenciar as Disciplinas
                        </p>
                        <a href="/disciplinas" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Turmas</h5>
                        <p class="card-text">
                            Gerenciar as Turmas
                        </p>
                        <a href="/turmas" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Turmas&Disciplinas</h5>
                        <p class="card-text">
                            Gerenciar as Disciplinas das Turmas
                        </p>
                        <a href="/turmasDiscs" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Tipos de Ocorrências</h5>
                        <p class="card-text">
                            Gerenciar os Tipos de Ocorrências
                        </p>
                        <a href="/tiposOcorrencias" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Professores</h5>
                        <p class="card-text">
                            Gerenciar os Professores
                        </p>
                        <a href="/prof/consulta" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Alunos</h5>
                        <p class="card-text">
                            Gerenciar os Alunos
                        </p>
                        <a href="/aluno/consulta" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Responsáveis</h5>
                        <p class="card-text">
                            Gerenciar os Responsáveis
                        </p>
                        <a href="/responsavel/consulta" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Colaboradores</h5>
                        <p class="card-text">
                            Gerenciar os Colaboradores
                        </p>
                        <a href="/outro/consulta" class="btn btn-primary">Gerenciar</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Admin</h5>
                        <p class="card-text">
                            Cadastrar Admin
                        </p>
                        <a href="/admin/novo" class="btn btn-primary">Cadastrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection