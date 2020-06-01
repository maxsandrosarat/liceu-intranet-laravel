@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades</h5>
            @if(count($atividades)==0)
                    <div class="alert alert-danger" role="alert">
                        @if($tipo=="painel")
                        Sem atividades cadastradas!
                        @endif
                        @if($tipo=="filtro")
                        Sem resultados da busca!
                        <a href="/admin/atividade" class="btn btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/admin/atividade/filtro">
                        @csrf
                        <label for="turma">Turma</label>
                            <select id="turma" name="turma">
                                <option value="">Selecione</option>
                                @foreach ($turmas as $turma)
                                <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                @endforeach
                            </select>
                        <label for="descricao">Descrição Atividade</label>
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao">
                        <label for="data">Data Criação</label>
                            <input class="form-control mr-sm-2" type="date" name="data">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <h5>Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}})</h5>
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Turmas</th>
                        <th>Disciplinas</th>
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
                                  <p class="card-text">Visualizações: {{$atividade->visualizacoes}} | Data Criação: {{date("d/m/Y", strtotime($atividade->data_criacao))}} @if($atividade->data_publicacao!="") | Data Publicação: {{date("d/m/Y", strtotime($atividade->data_publicacao))}} @endif @if($atividade->data_expiracao!="") | Data Expiração: {{date("d/m/Y", strtotime($atividade->data_expiracao))}}@endif</p>
                                  <a href="{{$atividade->link}}" target="_blank" class="btn btn-primary">Link Vídeo-Aula</a>
                                  <a type="button" class="btn btn-success" href="/admin/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalEditar{{$atividade->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-danger" href="/admin/atividade/apagar/{{$atividade->id}}"><i class="material-icons md-48">delete</i></a>
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
                                        <label for="disciplina" class="col-md-4 col-form-label text-md-right">Disciplina</label>
                                        <select id="disciplina" name="disciplina" required>
                                            <option value="{{$atividade->disciplina_id}}">Selecione</option>
                                            @foreach ($discs as $disc)
                                            <option value="{{$disc->id}}">{{$disc->nome}}</option>
                                            @endforeach
                                        </select>
                                        <label for="turma" class="col-md-4 col-form-label text-md-right">Turma</label>
                                        <select id="turma" name="turma" required>
                                            <option value="{{$atividade->turma_id}}">Selecione</option>
                                            @foreach ($turmas as $turma)
                                            <option value="{{$turma->id}}">{{$turma->serie}}º ANO {{$turma->turma}} (@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="dataPublicacao" class="col-md-4 col-form-label text-md-right">Data Publicação</label>
                                        <input type="date" name="dataPublicacao" id="dataPublicacao" value="{{$atividade->data_publicacao}}">
                                        <br/>
                                        <label for="dataExpiracao" class="col-md-4 col-form-label text-md-right">Data Expiração</label>
                                        <input type="date" name="dataExpiracao" id="dataExpiracao" value="{{$atividade->data_expiracao}}">
                                        <br/>
                                        <label for="descricao" class="col-md-4 col-form-label text-md-right">Descrição</label>
                                        <input type="text" name="descricao" id="descricao" value="{{$atividade->descricao}}" required>
                                        <br/>
                                        <label for="link" class="col-md-4 col-form-label text-md-right">Link da Vídeo-Aula</label>
                                        <input type="text" name="link" id="link" value="{{$atividade->link}}">
                                        <input type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf">
                                        <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
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
            @endif
        </div>
    </div>
    <br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection