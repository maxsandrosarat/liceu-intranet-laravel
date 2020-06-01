@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Produtos</h5>
            @if(count($rels)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem produtos cadastrados!
                </div>
            @else
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center;">Produto</th>
                        <th style="text-align: center;">Estoque</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->produto->nome}}</td>
                        <td style="text-align: center;">{{$rel->estoque}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    <br>
    <a href="/listaCompras" class="btn btn-success">Voltar</a>
    <a href="/listaCompras/pdf/{{$lista_id}}" target="_blank" class="btn btn-success">Gerar PDF</a>
@endsection