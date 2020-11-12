@extends('layouts.app', ["current"=>"diario"])

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Diário</h5>
                    @if(session('mensagem'))
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <p>{{session('mensagem')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <form method="GET" action="/outro/diario/consulta">
                        @csrf
                    <label for="turma">Turma
                        <select class="custom-select" id="turma" name="turma" required>
                            <option value="">Selecione</option>
                            @foreach ($turmas as $turma)
                            <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="data">Selecione o Dia
                    <input class="form-control" type="date" name="data" value="{{date("Y-m-d")}}" required></label>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
<a href="/outro/diario" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection