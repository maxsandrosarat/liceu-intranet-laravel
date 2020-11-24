@extends('layouts.app', ["current"=>"simulados"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <p>{{session('mensagem')}}</p>
            </div>
            @endif
            <form action="/prof/simulados" method="GET">
                @csrf
                <label for="ano">Selecione o ano:
                <select class="custom-select" id="ano" name="ano">
                    <option value="">Selecione</option>
                    @foreach ($anos as $an)
                    <option value="{{$an->ano}}">{{$an->ano}}</option>
                    @endforeach
                  </select></label>
                <input type="submit" class="btn btn-primary" value="Selecionar">
            </form>
            <h5 class="card-title">Questões para Simulados - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Simulado</th>
                        <th>Descrição</th>
                        <th>Bimestre</th>
                        <th>Ano</th>
                        <th>Série(s)</th>
                        <th>Questões</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($simulados as $sim)
                    <tr>
                        <td>{{$sim->id}}</td>
                        <td>{{$sim->descricao}}</td>
                        <td>{{$sim->bimestre}}° Bimestre</td>
                        <td>{{$sim->ano}}</td>
                        <td>
                            <ul>
                            @foreach ($sim->series as $serie)
                                <li>{{$serie->serie}}º ANO</li>
                            @endforeach
                            </ul>
                        </td>
                        <td><a href="/prof/simulados/painel/{{$sim->id}}" class="btn btn-primary">Anexar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br><br>
    <a href="/prof" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection