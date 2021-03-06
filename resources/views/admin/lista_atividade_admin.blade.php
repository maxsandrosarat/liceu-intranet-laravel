@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Listas de Atividades - Ensino Fundamental - Data: {{date("d/m/Y", strtotime($data))}}</h5>
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
                        <td>{{$fundDisc->nome}}</td>
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
                            @foreach ($laFunds as $laFund)
                                @if($laFund->disciplina->nome == $fundDisc->nome && $laFund->serie==$turma->serie)
                                @if($fundPertence===0) <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                @if($laFund->arquivo=='')
                                    <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modal6{{$laFund->id}}"><i class="material-icons md-48">cloud_upload</i></button>  
                                        @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="badge badge-success" href="/admin/listaAtividade/download/{{$laFund->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modal6{{$laFund->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="badge badge-danger" href="/admin/listaAtividade/apagar/{{$laFund->id}}"><i class="material-icons md-48">delete</i></a> 
                                        @endif </td>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal6{{$laFund->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar LA - {{$laFund->disciplina->nome}} - {{$laFund->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/admin/listaAtividade/anexar/{{$laFund->id}}" enctype="multipart/form-data">
                                                            @csrf
                                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                        <br/>
                                                        <b style="font-size: 60%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
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
        <h5 class="card-title">Painel de Listas de Atividades - Ensino Médio - Data: {{date("d/m/Y", strtotime($data))}}</h5>
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
                        <td>{{$medioDisc->nome}}</td>
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
                            @foreach ($laMedios as $laMedio)
                                @if($laMedio->disciplina->nome == $medioDisc->nome && $laMedio->serie==$turma->serie)
                                    @if($medioPertence===0) <td style="color:blue; text-align: center; font-weight:bold;">Não se <br/>Aplica</td> @else
                                        @if($laMedio->arquivo=='') <td style="color:red; text-align: center;"> Pendente <br/> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modal6{{$laMedio->id}}"><i class="material-icons md-48">cloud_upload</i></button>  
                                        @else <td style="color:green; text-align: center;"><i class="material-icons md-48 green600">check_circle</i><br/> <a type="button" class="badge badge-success" href="/admin/listaAtividade/download/{{$laMedio->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modal6{{$laMedio->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="badge badge-danger" href="/admin/listaAtividade/apagar/{{$laMedio->id}}"><i class="material-icons md-48">delete</i></a> @endif </td>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal6{{$laMedio->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Anexar AE - {{$laMedio->disciplina->nome}} - {{$laMedio->serie}}º ANO</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/admin/listaAtividade/anexar/{{$laMedio->id}}" enctype="multipart/form-data">
                                                            @csrf
                                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                                        <br/>
                                                        <b style="font-size: 60%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
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
    </div>
    <br>
    <a href="/admin/listaAtividade/{{date("Y")}}" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection