@extends('layouts.app', ["current"=>"la"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Atividades com Data de Entrega pelos Professores - {{$ano}}</h5>
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
            @if(count($las)!=0)
            <form action="/prof/listaAtividade" method="GET">
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

            <div class="table-responsive-xl">
                <table class="table table-striped table-ordered table-hover">
                    <thead class="thead-dark">
                        <tr style="text-align: center;">
                            <th>Mês</th>
                            <th>1ª Semana</th>
                            <th>2ª Semana</th>
                            <th>3ª Semana</th>
                            <th>4ª Semana</th>
                            <th>5ª Semana</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $nomeMes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
                        @endphp
                        @foreach ($meses as $mes)
                        @php
                        $contSemana = 0;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{$nomeMes[$mes->mes-1]}}</td>
                            @foreach ($las as $la)
                                @if($la->mes==$mes->mes)
                                    @php
                                        $contSemana++;
                                    @endphp
                                    <td style="text-align: center;"><a href="/prof/listaAtividade/painel/{{$la->data}}" class="btn btn-primary">{{date("d/m/Y", strtotime($la->data))}}</a></td>
                                @endif
                            @endforeach
                            @if($contSemana==4)
                                <td style="text-align: center;"><a href="#" class="btn btn-primary"></a></td>
                            @endif
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