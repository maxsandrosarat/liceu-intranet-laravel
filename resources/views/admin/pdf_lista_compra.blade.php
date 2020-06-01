@extends('layouts.app', ["current"=>"estoque"])

<div class="card border">
    <div class="card-body">
        <h5 class="card-title">Lista de Produtos</h5>
        @if(count($prods)==0)
            <div class="alert alert-danger" role="alert">
                Sem produtos cadastrados!
            </div>
        @else
        <table class="table table-striped table-ordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Estoque</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prods as $prod)
                <tr>
                    <td>{{$prod->id}}</td>
                    <td>{{$prod->nome}}</td>
                    <td>{{$prod->estoque}}</td>
                    <td>{{$prod->categoria->nome}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>