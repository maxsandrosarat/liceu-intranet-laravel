@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row justify-content-center">
        <div class="col align-self-center">
        <div class="card-deck">
            <!--<div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Diário</h5>
                        <p class="card-text">
                            Veja o diário do dia!
                        </p>
                        <a href="/responsavel/diario" class="btn btn-primary">Diário</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
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
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
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
            </div>-->
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Administrativo</h5>
                        <p class="card-text">
                            Consultar Contratos, entre outros.
                        </p>
                        <a href="#" class="btn btn-primary">Administrativo</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Financeiro</h5>
                        <p class="card-text">
                            Consultar boletos, histórico de pagamentos, entre outros.
                        </p>
                        <a href="#" class="btn btn-primary">Financeiro</a>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center centralizado">
                <div class="card border-primary text-center" style="width: 300px;">
                    <div class="card-body">
                        <h5>Pedagógico</h5>
                        <p class="card-text">
                            Consultar Ocorrências, Recados, Boletim, entre outros.
                        </p>
                        <a href="#" class="btn btn-primary">Pedagógico</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
