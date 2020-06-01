@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades Extras - Ensino Fundamental</h5>
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        @foreach ($fundTurmas as $turma)
                        <th>{{$turma->serie}}º ANO</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fundDiscs as $fundDisc)
                    <tr>
                        <td>{{$fundDisc->nome}}</td>
                        @foreach ($fundTurmas as $turma)
                            @foreach ($aeFunds as $aeFund)
                                @if($aeFund->disciplina->nome == $fundDisc->nome && $aeFund->serie==$turma->serie)
                                @if($aeFund->serie==9 && $fundDisc->nome=="Arte" || $aeFund->serie==9 && $fundDisc->nome=="Ciências" || $aeFund->serie==9 && $fundDisc->nome=="Educação Física" || $aeFund->serie!=9 && $fundDisc->nome=="Biologia" || $aeFund->serie!=9 && $fundDisc->nome=="Física" || $aeFund->serie!=9 && $fundDisc->nome=="Química") <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($aeFund->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal6{{$aeFund->id}}"><i class="material-icons md-48">cloud_upload</i></button>  @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/atividadeExtra/download/{{$aeFund->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal6{{$aeFund->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-danger" href="/atividadeExtra/apagar/{{$aeFund->id}}"><i class="material-icons md-48">delete</i></a> @endif </td>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal6{{$aeFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar AE - {{$aeFund->disciplina->nome}} - {{$aeFund->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/atividadeExtra/anexar/{{$aeFund->id}}" enctype="multipart/form-data">
                                                            @csrf
                                                        <b>Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <h5 class="card-title">Painel de Atividades Extras - Ensino Médio</h5>
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Disciplinas</th>
                        @foreach ($medioTurmas as $turma)
                        <th>{{$turma->serie}}º ANO</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medioDiscs as $medioDisc)
                    <tr>
                        <td>{{$medioDisc->nome}}</td>
                        @foreach ($medioTurmas as $turma)
                            @foreach ($aeMedios as $aeMedio)
                                @if($aeMedio->disciplina->nome == $medioDisc->nome && $aeMedio->serie==$turma->serie)
                                @if($aeMedio->serie==3 && $medioDisc->nome=="Química I") <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($aeMedio->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal6{{$aeMedio->id}}"><i class="material-icons md-48">cloud_upload</i></button>  @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-success" href="/atividadeExtra/download/{{$aeMedio->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal6{{$aeMedio->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-danger" href="/atividadeExtra/apagar/{{$aeMedio->id}}"><i class="material-icons md-48">delete</i></a> @endif </td>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal6{{$aeMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar AE - {{$aeMedio->disciplina->nome}} - {{$aeMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/atividadeExtra/anexar/{{$aeMedio->id}}" enctype="multipart/form-data">
                                                            @csrf
                                                        <b>Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <a href="/atividadeExtra" class="btn btn-success">Voltar</a>
@endsection
