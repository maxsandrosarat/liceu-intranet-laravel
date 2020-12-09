@extends('layouts.app', ["current"=>"simulados"])

@section('body')
<div class="card border">
    @if($ensino=="fund" || $ensino=="todos")
    <div class="card-body">
            <h5 class="card-title">Painel de Questões de Simulado - Ensino Fundamental - {{$simulado->descricao}} - {{$simulado->bimestre}}º Bimestre - Ano: {{$simulado->ano}}</h5>
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
                @foreach($profDiscs as $profDisc)
                    @foreach ($fundDiscs as $fundDisc)
                    @if($fundDisc->id==$profDisc->disciplina_id)
                    <tr>
                        <td>{{$fundDisc->nome}}</td>
                        @foreach ($fundTurmas as $turma)
                            @foreach ($contFunds as $contFund)
                                @if($contFund->disciplina->nome == $fundDisc->nome && $contFund->serie==$turma->serie)
                                    @if($turma->serie==6 || $turma->serie==7 || $turma->serie==8)
                                        @if($contFund->disciplina->nome=="Biologia" || $contFund->disciplina->nome=="Física" || $contFund->disciplina->nome=="Química") <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($contFund->arquivo=='')
                                        <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contFund->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                        @else
                                        @if($contFund->comentario!="")
                                        <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalComent{{$contFund->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                        @else
                                            @if($contFund->conferido==1) 
                                            <td style="text-align: center;"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i><br/>
                                            @else
                                            <td style="text-align: center;"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i><br/>
                                            @endif
                                        @endif
                                        <a type="button" class="badge badge-success" href="/prof/simulados/download/{{$contFund->id}}"><i class="material-icons md-18">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contFund->id}}"><i class="material-icons md-18">edit</i></button> <a type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$contFund->id}}"><i class="material-icons md-18 white">delete</i></a>
                                        <!-- Modal Deletar -->
                                            <div style="text-align: center;" class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Excluir Questões do {{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>Tem certeza que deseja excluir esse arquivo?</h5>
                                                            <p>Não será possivel reverter esta ação.</p>
                                                            <a href="/prof/simulados/apagar/{{$contFund->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                    </td>
                                    <!-- Modal Anexar -->
                                            <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/prof/simulados/anexar/{{$contFund->id}}" enctype="multipart/form-data">
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
                                            <!-- Modal Comentário -->
                                            <div class="modal fade bd-example-modal-lg" id="exampleModalComent{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">{{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Comentário: {{$contFund->comentario}}.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                    @if($contFund->disciplina->nome=="Arte" || $contFund->disciplina->nome=="Ciências" || $contFund->disciplina->nome=="Educação Física") <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                    @if($contFund->arquivo=='')
                                    <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contFund->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                    @else
                                    @if($contFund->comentario!="")
                                    <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalComent{{$contFund->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                    @else
                                        @if($contFund->conferido==1) 
                                        <td style="text-align: center;"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i><br/>
                                        @else
                                        <td style="text-align: center;"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i><br/>
                                        @endif
                                    @endif
                                    <a type="button" class="badge badge-success" href="/prof/simulados/download/{{$contFund->id}}"><i class="material-icons md-18">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contFund->id}}"><i class="material-icons md-18">edit</i></button> <a type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$contFund->id}}"><i class="material-icons md-18 white">delete</i></a>
                                    <!-- Modal Deletar -->
                                        <div style="text-align: center;" class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Excluir Questões do {{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Tem certeza que deseja excluir esse arquivo?</h5>
                                                        <p>Não será possivel reverter esta ação.</p>
                                                        <a href="/prof/simulados/apagar/{{$contFund->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                                </td>
                                <!-- Modal Anexar -->
                                        <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="/prof/simulados/anexar/{{$contFund->id}}" enctype="multipart/form-data">
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
                                        <!-- Modal Comentário -->
                                        <div class="modal fade bd-example-modal-lg" id="exampleModalComent{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">{{$simulado->descricao}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Comentário: {{$contFund->comentario}}.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                    @endif
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif
        @if($ensino=="medio" || $ensino=="todos")
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Médio - {{$simulado->descricao}} - {{$simulado->bimestre}}º Bimestre - Ano: {{$simulado->ano}}</h5>
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
                @foreach($profDiscs as $profDisc)
                    @foreach ($medioDiscs as $medioDisc)
                    @if($medioDisc->id==$profDisc->disciplina_id)
                    <tr>
                        <td>{{$medioDisc->nome}}</td>
                        @foreach ($medioTurmas as $turma)
                            @foreach ($contMedios as $contMedio)
                                @if($contMedio->disciplina->nome == $medioDisc->nome && $contMedio->serie==$turma->serie)
                                    @if($turma->serie==3)
                                        @if($contMedio->disciplina->nome=="Química I") <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($contMedio->arquivo=='')
                                        <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contMedio->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                        @else
                                        @if($contMedio->comentario!="")
                                        <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalComent{{$contMedio->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                        @else
                                            @if($contMedio->conferido==1) 
                                            <td style="text-align: center;"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i><br/>
                                            @else
                                            <td style="text-align: center;"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i><br/>
                                            @endif
                                        @endif
                                        <a type="button" class="badge badge-success" href="/prof/simulados/download/{{$contMedio->id}}"><i class="material-icons md-18">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contMedio->id}}"><i class="material-icons md-18">edit</i></button> <a type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$contMedio->id}}"><i class="material-icons md-18 white">delete</i></a>
                                        <!-- Modal Deletar -->
                                            <div style="text-align: center;" class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Excluir Questões do {{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h5>Tem certeza que deseja excluir esse arquivo?</h5>
                                                            <p>Não será possivel reverter esta ação.</p>
                                                            <a href="/prof/simulados/apagar/{{$contMedio->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                    </td>
                                    <!-- Modal Anexar -->
                                            <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/prof/simulados/anexar/{{$contMedio->id}}" enctype="multipart/form-data">
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
                                            <!-- Modal Comentário -->
                                            <div class="modal fade bd-example-modal-lg" id="exampleModalComent{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">{{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Comentário: {{$contMedio->comentario}}.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                    @if($contMedio->arquivo=='')
                                    <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contMedio->id}}"><i class="material-icons md-18">cloud_upload</i></button> 
                                    @else
                                    @if($contMedio->comentario!="")
                                    <td style="text-align: center;"><a href="#" data-toggle="modal" data-target="#exampleModalComent{{$contMedio->id}}"><i class="material-icons md-48 yellow" data-toggle="tooltip" data-placement="left" title="Problemas Encontrados">report_problem</i></a><br/>
                                    @else
                                        @if($contMedio->conferido==1) 
                                        <td style="text-align: center;"><i class="material-icons md-48 green" data-toggle="tooltip" data-placement="left" title="Conferido e Liberado">check_circle</i><br/>
                                        @else
                                        <td style="text-align: center;"><i class="material-icons md-48 red" data-toggle="tooltip" data-placement="left" title="Não Conferido">highlight_off</i><br/>
                                        @endif
                                    @endif
                                    <a type="button" class="badge badge-success" href="/prof/simulados/download/{{$contMedio->id}}"><i class="material-icons md-18">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#exampleModalAnexar{{$contMedio->id}}"><i class="material-icons md-18">edit</i></button> <a type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$contMedio->id}}"><i class="material-icons md-18 white">delete</i></a>
                                    <!-- Modal Deletar -->
                                        <div style="text-align: center;" class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Excluir Questões do {{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Tem certeza que deseja excluir esse arquivo?</h5>
                                                        <p>Não será possivel reverter esta ação.</p>
                                                        <a href="/prof/simulados/apagar/{{$contMedio->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                                </td>
                                <!-- Modal Anexar -->
                                        <div style="text-align: center;" class="modal fade" id="exampleModalAnexar{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Anexar Questões do {{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="/prof/simulados/anexar/{{$contMedio->id}}" enctype="multipart/form-data">
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
                                        <!-- Modal Comentário -->
                                        <div class="modal fade bd-example-modal-lg" id="exampleModalComent{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">{{$simulado->descricao}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Comentário: {{$contMedio->comentario}}.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </tr>
                    @endif
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif
    </div>
    <br>
<a href="/prof/simulados/{{$ano}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
</div>
@endsection
