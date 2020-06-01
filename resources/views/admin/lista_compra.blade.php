@extends('layouts.app', ["current"=>"estoque"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Produtos</h5>
            @if(count($prods)==0)
                <div class="alert alert-danger" role="alert">
                    Sem produtos cadastrados!
                </div>
            @else
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Selecionar</th>
                        <th style="text-align: center;">Produto</th>
                        <th style="text-align: center;">Estoque</th>
                    </tr>
                </thead>
                    <tbody>
                        <form action="/listaCompras" method="POST">
                            @csrf
                        @foreach ($prods as $prod)
                        <tr>
                            <td><input type="checkbox" name="produtos[]" value="{{$prod->id}}"></td>
                            <td>{{$prod->nome}}</td>
                            <td style="text-align: center;">{{$prod->estoque}}</td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
            <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
            </form>
            @endif
        </div>
    </div>
    <br>
    <a href="/admin/estoque" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection