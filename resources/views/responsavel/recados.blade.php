@extends('layouts.app', ["current"=>"recados"])

@section('body')
<div class="jumbotron bg-light border border-secondary">
    <div class="row">
        <div class="card-deck">
            @if(count($recados)==0)
                <div class="alert alert-danger" role="alert">
                    Sem recados para exibir!
                </div>
            @else
                    @foreach ($recados as $recado)
                    <div class="d-flex justify-content-center centralizado">
                        <div class="card border-primary text-center" style="width: 19rem;">
                            <div class="card-header" style="color: black;">
                                {{$recado->titulo}}
                            </div>
                            <div class="card-body">
                              <p class="card-text" style="color: black;">{{$recado->descricao}}
							  @if($recado->aluno=="") @if($recado->turma=="") @else <br/> Para Turma: {{$recado->turma->serie}}º {{$recado->turma->turma}} @endif @else <br/> Sobre Aluno: {{$recado->aluno->name}} @endif
							  </p>
                            </div>
                            <div class="card-footer text-muted">
                                {{date("d/m/Y", strtotime($recado->created_at))}}
                            </div>
                        </div>
                    </div>     
                    @endforeach 
            @endif
        </div>
    </div>
</div>
@endsection