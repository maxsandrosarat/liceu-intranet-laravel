@extends('layouts.app', ["current"=>"conteudo"])

@section('body')
    <div class="card border">
        @if($ensino=="fund")
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Fundamental - {{$tipo}} - {{$bim}}º Bimestre</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        <th>{{$serie}}º ANO</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($fundDiscs as $fundDisc)
                                <tr>
                                    <td>{{$fundDisc->nome}}</td>
                                        @foreach ($contFunds as $contFund)
                                            @if($contFund->disciplina->nome == $fundDisc->nome && $contFund->serie==$serie)
                                                @if($contFund->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> Solicite ao Professor(a) @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/aluno/conteudos/download/{{$contFund->id}}"><i class="material-icons md-48">cloud_download</i></a> @endif </td>
                                            @endif
                                        @endforeach
                                </tr>
                        @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif
        @if($ensino=="medio")
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Médio - {{$tipo}} - {{$bim}}º Bimestre</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        <th>{{$serie}}º ANO</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($medioDiscs as $medioDisc)

                                <tr>
                                    <td>{{$medioDisc->nome}}</td>

                                        @foreach ($contMedios as $contMedio)
                                            @if($contMedio->disciplina->nome == $medioDisc->nome && $contMedio->serie==$serie)
                                                @if($contMedio->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> Solicite ao Professor(a) @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/aluno/conteudos/download/{{$contMedio->id}}"><i class="material-icons md-48">cloud_download</i></a> @endif </td>
                                            @endif
                                        @endforeach
                                </tr>
                        @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif
    </div>
    <br>
    <a href="/aluno/conteudos/{{$ano}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
