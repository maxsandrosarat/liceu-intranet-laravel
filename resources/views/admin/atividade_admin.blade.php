@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades</h5>
            @if(count($atividades)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem atividades cadastradas!
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/atividade" class="btn btn-sm btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/atividade/filtro">
                        @csrf
                        <label for="turma">Turma
                            <select class="custom-select" id="turma" name="turma">
                                <option value="">Selecione</option>
                                @foreach ($turmas as $turma)
                                <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                @endforeach
                            </select></label>
                            <label for="disciplina">Disciplina
                            <select class="custom-select" id="disciplina" name="disciplina">
                                <option value="">Selecione</option>
                                @foreach ($discs as $disciplina)
                                <option value="{{$disciplina->id}}">{{$disciplina->nome}}@if($disciplina->ensino=='fund') (Fundamental) @else @if($disciplina->ensino=='medio') (Médio) @endif @endif</option>
                                @endforeach
                            </select></label>
                        <label for="descricao">Descrição Atividade</label>
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao">
                        <label for="data">Data Criação
                            <input class="form-control mr-sm-2" type="date" name="data"></label>
                        <button class="btn btn-sm btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <h5>Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}})</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Turma</th>
                        <th>Disciplina</th>
                        <th>Atividade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atividades as $atividade)
                    <tr>
                        <td>{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}}</td>
                        <td>{{$atividade->disciplina->nome}}</td>
                        <td style="text-align: center;">
                            <div class="card">
                                <div class="card-header">
                                    {{$atividade->descricao}}
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Visualizações: {{$atividade->visualizacoes}} | Data Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}} <br>@if($atividade->data_publicacao!="")Data Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} @endif @if($atividade->data_expiracao!="") | Data Expiração: {{date("d/m/Y H:i", strtotime($atividade->data_expiracao))}}@endif</p>
                                  @if($atividade->link!="")<a href="{{$atividade->link}}" target="_blank" class="badge badge-primary">Link Vídeo-Aula</a>@endif
                                  <a type="button" class="badge badge-success" href="/admin/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modalEditar{{$atividade->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="badge badge-danger" href="/admin/atividade/apagar/{{$atividade->id}}"><i class="material-icons md-48">delete</i></a>
                                  @if($atividade->retorno=="sim") <a href="/admin/atividade/retornos/{{$atividade->id}}" class="btn btn-info btn-sm">Retornos <span class="badge badge-light">{{count($atividade->retornos)}}</span></a> @endif
                                </div>
                            </div>
                        </td>
                            <!-- Modal -->
                            <div class="modal fade" id="modalEditar{{$atividade->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Atividade</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/admin/atividade/editar/{{$atividade->id}}" enctype="multipart/form-data">
                                        @csrf
                                        <label for="disciplina">Disciplina</label>
                                        <select id="disciplina" class="form-control" name="disciplina" required>
                                            <option value="{{$atividade->disciplina->id}}">{{$atividade->disciplina->nome}} (@if($atividade->disciplina->ensino=='fund') Fundamental @else Médio @endif)</option>
                                            @foreach ($discs as $disc)
                                            @if($disc->id!=$atividade->disciplina->id)
                                            <option value="{{$disc->id}}">{{$disc->nome}} (@if($disc->ensino=='fund') Fundamental @else Médio @endif)</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <label for="turma">Turma</label>
                                        <select id="turma" class="form-control" name="turma" required>
                                            <option value="{{$atividade->turma->id}}">{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} (@if($atividade->turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @foreach ($turmas as $turma)
                                            @if($turma->id!=$atividade->turma->id)
                                            <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="dataPublicacao" class="col-md-4 col-form-label text-md-right">Data Publicação</label>
                                        <input type="date" name="dataPublicacao" id="dataPublicacao" value="{{date("Y-m-d", strtotime($atividade->data_publicacao))}}">
                                        <input type="time" name="horaPublicacao" id="horaPublicacao" value="{{date("H:i", strtotime($atividade->data_publicacao))}}">
                                        <br/>
                                        <label for="dataExpiracao" class="col-md-4 col-form-label text-md-right">Data Expiração</label>
                                        <input type="date" name="dataExpiracao" id="dataExpiracao" value="{{date("Y-m-d", strtotime($atividade->data_expiracao))}}" required>
                                        <input type="time" name="horaExpiracao" id="horaExpiracao" value="{{date("H:i", strtotime($atividade->data_expiracao))}}" required>
                                        <br/>
                                        <label for="descricao">Descrição</label>
                                        <input type="text" class="form-control" name="descricao" id="descricao" value="{{$atividade->descricao}}" required>
                                        <br/>
                                        <label for="link">Link da Vídeo-Aula</label>
                                        <input type="text" class="form-control" name="link" id="link" value="{{$atividade->link}}">
                                        <br/>
                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf">
                                        <br/>
                                        <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$atividades->links() }}
            </div>
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/admin/pedagogico" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection