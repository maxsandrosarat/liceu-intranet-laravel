@extends('layouts.app', ["current"=>"atividade"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Retornos da Atividade - {{$descricao}}</h5>
            @if(count($retornos)==0)
                    <div class="alert alert-danger" role="alert">
                        Sem retornos até o momento!
                    </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Aluno</th>
                        <th>Data Retorno</th>
                        <th>Comentário</th>
                        <th>Arquivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($retornos as $retorno)
                    <tr>
                        <td>{{$retorno->aluno->name}}</td>
                        <td>{{date("d/m/Y", strtotime($retorno->data_retorno))}}</td>
                        <td>{{$retorno->comentario}}</td>
                        <td><a type="button" class="btn btn-success" href="/prof/atividade/retorno/download/{{$retorno->id}}"><i class="material-icons md-48">cloud_download</i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    <br>
@endsection