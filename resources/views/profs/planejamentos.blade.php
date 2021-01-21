@extends('layouts.app', ["current"=>"home"])

@section('body')
<div class="card border">
    @if($ensino=="fund" || $ensino=="todos")
    <div class="card-body">
            <h5 class="card-title">Painel de Planejamentos - Ensino Fundamental - {{$planejamento->descricao}} - Ano: {{$planejamento->ano}}</h5>
            <div class="table-responsive-xl">
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
                    @php
                        $fundPertence = 0;
                    @endphp
                    <tr>
                        <td>{{$fundDisc->nome}}
                        @foreach ($fundTurmas as $turma)
                            @php
                            $fundPertence = 0;
                            @endphp
                            @foreach ($fundDisc->turmas as $turmaFund)
                                @if($turmaFund->serie==$turma->serie)
                                    @php
                                        $fundPertence = 1;
                                    @endphp
                                @endif
                            @endforeach</td>
                            @foreach ($contFunds as $contFund)
                                @if($contFund->disciplina->nome == $fundDisc->nome && $contFund->serie==$turma->serie)
                                        @if($fundPertence===0) <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                            @if($contFund->arquivo=='')
                                                <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contFund->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                            @else
                                                @if($contFund->comentario!="")
                                                <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalComent{{$contFund->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                                @else
                                                    @if($contFund->conferido==1) 
                                                    <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalConf{{$contFund->id}}"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i></a><br/>
                                                    @else
                                                    <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalConf{{$contFund->id}}"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i></a><br/>
                                                    @endif
                                                @endif
                                                <a type="button" class="badge badge-success" href="/prof/planejamentos/download/{{$contFund->id}}"><i class="material-icons md-18">cloud_download</i></a>
                                                
                                            @endif
                                            </td>
                                            <!-- Modal Anexar -->
                                                    <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$planejamento->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="/prof/planejamentos/anexar/{{$contFund->id}}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                                    <br/>
                                                                    <b style="font-size: 90%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>
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
        @endif
        @if($ensino=="medio" || $ensino=="todos")
        <div class="card-body">
            <h5 class="card-title">Painel de Questões de Provas - Ensino Médio - {{$planejamento->descricao}} - {{$planejamento->bimestre}}º Bimestre - Ano: {{$planejamento->ano}}</h5>
            <div class="table-responsive-xl">
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
                    @php
                        $medioPertence = 0;
                    @endphp
                    <tr>
                        <td>{{$medioDisc->nome}}
                        @foreach ($medioTurmas as $turma)
                            @php
                                $medioPertence = 0;
                            @endphp
                            @foreach ($medioDisc->turmas as $turmaMedio)
                                @if($turmaMedio->serie==$turma->serie)
                                    @php
                                        $medioPertence = 1;
                                    @endphp
                                @endif
                            @endforeach</td>
                            @foreach ($contMedios as $contMedio)
                                @if($contMedio->disciplina->nome == $medioDisc->nome && $contMedio->serie==$turma->serie)
                                    @if($medioPertence===0) <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($contMedio->arquivo=='')
                                        <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contMedio->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                    @else
                                        @if($contMedio->comentario!="")
                                        <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalConf{{$contMedio->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                        @else
                                            @if($contMedio->conferido==1) 
                                            <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalConf{{$contMedio->id}}"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i></a><br/>
                                            @else
                                            <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalConf{{$contMedio->id}}"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i></a><br/>
                                            @endif
                                        @endif
                                        <a type="button" class="badge badge-success" href="/prof/planejamentos/download/{{$contMedio->id}}"><i class="material-icons md-18">cloud_download</i></a>
                                    @endif
                                    </td>
                                    <!-- Modal Anexar -->
                                            <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$planejamento->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/prof/planejamentos/anexar/{{$contMedio->id}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                            <br/>
                                                            <b style="font-size: 90%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
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
        @endif
    </div>
    <br>
<a href="/prof/planejamentos/{{$ano}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
</div>
@endsection
