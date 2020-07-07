@extends('layouts.app', ["current"=>"ocorrencias"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            @if(count($ocorrencias)==0)
                <div class="alert alert-danger" role="alert">
                    Sem ocorrências para exibir!
                </div>
            @else
                    @foreach ($ocorrencias as $ocorrencia)
                        <div class="d-flex justify-content-center centralizado">
                            @if($ocorrencia->tipo_ocorrencia->tipo=="despontuacao") <div class="card border-danger text-center" style="width: 20rem;"> @else <div class="card border-success text-center" style="width: 20rem;"> @endif
                                <div class="card-header" style="color: black;">
                                    {{$ocorrencia->aluno->name}}
                                </div>
                                @if($ocorrencia->tipo_ocorrencia->tipo=="despontuacao") <div class="card-body text-danger"> @else <div class="card-body text-success"> @endif
                                  <h5 class="card-title">{{$ocorrencia->tipo_ocorrencia->codigo}} - {{$ocorrencia->tipo_ocorrencia->descricao}}</h5>
                                  <p class="card-text">
                                    {{$ocorrencia->disciplina->nome}} - Prof(a). {{$ocorrencia->prof->name}}
                                  </p>
                                  @if($ocorrencia->observacao!="")
                                <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#collapseExample{{$ocorrencia->id}}" aria-expanded="false" aria-controls="collapseExample">
                                    Observação
                                </button>
                                @endif
                                @if($ocorrencia->responsavel_ciente!=1)
                                <a href="/responsavel/ocorrencias/ciente/{{$ocorrencia->id}}" class="btn btn-primary">Ciente</a>
                                @else
                                    <h6>Ciente <i class="material-icons">done</i></h6>
                                @endif
                                </div>
                                <div class="collapse" id="collapseExample{{$ocorrencia->id}}">
                                    <div class="card card-body">
                                        {{$ocorrencia->observacao}}
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    {{date("d/m/Y", strtotime($ocorrencia->data))}}
                                </div>
                            </div>
                        </div>
                    @endforeach
            @endif
        </div>
    </div>
</div>
@endsection