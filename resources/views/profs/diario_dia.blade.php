@extends('layouts.app', ["current"=>"diario"])

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">Diário</h5>
                    <form method="GET" action="/prof/diario">
                        @csrf
                    <input type="hidden" name="disciplina" value="{{$discId}}">
                    <input type="hidden" name="turma" value="{{$turmaId}}">
                    <label for="data">Selecione o Dia
                    <input class="form-control" type="date" name="data" value="{{date("Y-m-d")}}" required></label>
                    <button type="submit" class="btn btn-primary">Lançar Diário</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
<a href="/prof/diario/{{$discId}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection