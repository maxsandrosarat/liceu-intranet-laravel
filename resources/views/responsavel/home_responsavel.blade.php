@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            <!--<div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Diário</h5>
                    <p class="card-text">
                        Veja o diário do dia!
                    </p>
                    <a href="/responsavel/diario" class="btn btn-primary">Diário</a>
                </div>
            </div>-->
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Ocorrências</h5>
                    <p class="card-text">
                        Veja as ocorrências!
                    </p>
                    <a style="text-align: center;"type="button" href="/responsavel/ocorrencias" class="btn btn-primary">
                        Ocorrências <span class="badge badge-light">{{$ocorrencias}}</span>
                    </a>
                </div>
            </div>
            <div class="card border border-primary mb-3" style="width: 20rem;">
                <div class="card-body">
                    <h5>Recados</h5>
                    <p class="card-text">
                        Veja os recados!
                    </p>
                    <a type="button" href="/responsavel/recados" class="btn btn-primary">
                        Recados <span class="badge badge-light">{{ Auth::user()->recados }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
