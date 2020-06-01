@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Retornos da Atividade - {{$descricao}}</h5>
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Aluno</th>
                        <th>Data Retorno</th>
                        <th>Cometário</th>
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
        </div>
    </div>
    <br>
    <a href="/prof/disciplinas" class="btn btn-success">Voltar</a>
@endsection