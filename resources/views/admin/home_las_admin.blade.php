@extends('layouts.app', ["current"=>"pedagogico"])

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
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Categoria">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <form action="/admin/listaAtividade" method="GET">
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
            @if(count($las)!=0)
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
                                    <td style="text-align: center;"><a href="/admin/listaAtividade/painel/{{$la->data}}" class="btn btn-primary">{{date("d/m/Y", strtotime($la->data))}}</a></td>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Listas de Atividades</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/admin/listaAtividade" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                                <label for="mes">Mês</label>
                                <select class="custom-select" id="mes" name="mes" required>
                                    <option value="">Selecione o mês</option>
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                                <br/>
                                <h5>1ª Semana</h5>
                                <label for="data">Data
                                <input type="date" class="form-control" name="data1" id="data" required></label>
                                <br/>
                                <h5>2ª Semana</h5>
                                <label for="data">Data
                                <input type="date" class="form-control" name="data2" id="data" required></label>
                                <br/>
                                <h5>3ª Semana</h5>
                                <label for="data">Data
                                <input type="date" class="form-control" name="data3" id="data" required></label>
                                <br/>
                                <h5>4ª Semana</h5>
                                <label for="data">Data
                                <input type="date" class="form-control" name="data4" id="data" required></label>
                                <br/>
                                <h5>5ª Semana</h5>
                                <label for="data">Data
                                <input type="date" class="form-control" name="data5" id="data"></label>
                                <br/>
                                <label for="ano">Ano</label>
                                <input type="number" class="form-control" name="ano" id="ano" placeholder="Digite o ano, exemplo 2021" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection