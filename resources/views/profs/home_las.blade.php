@extends('layouts.app', ["current"=>"la"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Atividades com Data de Entrega pelos Professores</h5>
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
                    <tr>
                        <td style="text-align: center;">Abril</td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-04-06" class="btn btn-primary">06/04/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-04-13" class="btn btn-primary">13/04/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-04-20" class="btn btn-primary">20/04/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-04-27" class="btn btn-primary">27/04/2020</a></td>
                        <td style="text-align: center;"><a href="#" class="btn btn-primary"></a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Maio</td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-05-04" class="btn btn-primary">04/05/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-05-11" class="btn btn-primary">11/05/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-05-18" class="btn btn-primary">18/05/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-05-25" class="btn btn-primary">25/05/2020</a></td>
                        <td style="text-align: center;"><a href="#" class="btn btn-primary"></a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Junho</td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-06-01" class="btn btn-primary">01/06/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-06-08" class="btn btn-primary">08/06/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-06-15" class="btn btn-primary">15/06/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-06-22" class="btn btn-primary">22/06/2020</a></td>
                        <td style="text-align: center;"><a href="/prof/listaAtividade/painel/2020-06-29" class="btn btn-primary">29/06/2020</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <a href="/prof" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection