@extends('layouts.app', ["current"=>"estoque"])

<div class="card border">
    <div class="card-body">
        <table id="yesprint" class="table table-striped table-ordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Cód.</th>
                    <th>Tipo</th>
                    <th>Produto</th>
                    <th>Quant.</th>
                    <th>Requis.</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rels as $rel)
                @if($rel->tipo=='entrada') <tr style="color:blue;"> @else <tr style="color:red;"> @endif
                    <td>{{$rel->id}}</td>
                    <td>@if($rel->tipo=='entrada') Entrada @else Saída @endif</td>
                    <td>{{$rel->produto_nome}}</td>
                    <td>{{$rel->quantidade}}</td>
                    <td>{{$rel->requisitante}}</td>
                    <td>{{date("d/m/Y", strtotime($rel->data))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>   
</div>