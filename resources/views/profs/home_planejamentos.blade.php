@extends('layouts.app', ["current"=>"home"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <p>{{session('mensagem')}}</p>
            </div>
            @endif
            @if(count($planejamentos)==0)
                <div class="alert alert-danger" role="alert">
                    Sem planejamentos cadastrados!
                </div>
            @else
            <form action="/prof/planejamentos" method="GET">
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
            <h5 class="card-title">Planejamentos - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Ano</th>
                        <th>Série(s)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planejamentos as $plan)
                    <tr>
                        <td>{{$plan->id}}</td>
                        <td>{{$plan->descricao}}</td>
                        <td>{{$plan->ano}}</td>
                        <td>
                            <ul>
                            @foreach ($plan->series as $serie)
                                <li>{{$serie->serie}}º ANO</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="/prof/planejamentos/painel/{{$plan->id}}" class="badge badge-primary" data-toggle="tooltip" data-placement="right" title="Painel"><i class="material-icons md-48">attach_file</i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/prof" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection