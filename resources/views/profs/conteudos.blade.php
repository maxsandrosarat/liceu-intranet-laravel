@extends('layouts.app', ["current"=>"conteudos"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Fundamental - {{$tipo}} - {{$bim}}º Bimestre</h5>
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
                                                @if($contFund->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal6{{$contFund->id}}"><i class="material-icons md-48">cloud_upload</i></button>  @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-sm btn-success" href="/prof/conteudos/download/{{$contFund->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal6{{$contFund->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-sm btn-danger" href="/prof/conteudos/apagar/{{$contFund->id}}"><i class="material-icons md-48">delete</i></a> @endif </td>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="modal6{{$contFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar Conteúdo da {{$tipo}} - {{$contFund->disciplina->nome}} - {{$contFund->serie}}º ANO</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="POST" action="/prof/conteudos/anexar/{{$contFund->id}}" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx" required>
                                                                            <br/>
                                                                            <b style="font-size: 90%;">Aceito apenas extensões do Word (".doc", ".docx")</b>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </form>
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
        <div class="card-body">
            <h5 class="card-title">Painel de Conteúdos - Ensino Médio - {{$tipo}} - {{$bim}}º Bimestre</h5>
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
                                                @if($contMedio->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal6{{$contMedio->id}}"><i class="material-icons md-48">cloud_upload</i></button>  @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="btn btn-sm btn-success" href="/prof/conteudos/download/{{$contMedio->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal6{{$contMedio->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-sm btn-danger" href="/prof/conteudos/apagar/{{$contMedio->id}}"><i class="material-icons md-48">delete</i></a> @endif </td>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="modal6{{$contMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar Conteúdo da {{$tipo}} - {{$contMedio->disciplina->nome}} - {{$contMedio->serie}}º ANO</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="POST" action="/prof/conteudos/anexar/{{$contMedio->id}}" enctype="multipart/form-data">
                                                                            @csrf
                                                                            <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx" required>
                                                                            <br/>
                                                                            <b style="font-size: 90%;">Aceito apenas extensões do Word (".doc", ".docx")</b>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </form>
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
    </div>
    <br>
    <a href="/prof/conteudos/{{$ano}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
