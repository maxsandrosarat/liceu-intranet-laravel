@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Atividades</h5>
                        <p class="card-text">
                            Baixar os arquivos de Atividades e enviar Retorno
                        </p>
                        <a href="/aluno/atividade/disciplinas" class="btn btn-primary">Atividades</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 20rem;">
                    <div class="card-body">
                        <h5>Conteúdos</h5>
                        <p class="card-text">
                            Baixar os Conteúdos
                        </p>
                        <a href="/aluno/conteudos/{{date("Y")}}" class="btn btn-primary">Conteúdos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
