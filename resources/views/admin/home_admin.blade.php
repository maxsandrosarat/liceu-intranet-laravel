@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Administrativo</h5>
                        <p class="card-text">
                            Gerenciar Turmas, Alunos, Professores, entre outros.
                        </p>
                        <a href="/admin/administrativo" class="btn btn-primary">Administrativo</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Pedagógico</h5>
                        <p class="card-text">
                            Consultar Lista de Atividades, Conteudos, Ocorrências, entre outros.
                        </p>
                        <a href="/admin/pedagogico" class="btn btn-primary">Pedagógico</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
