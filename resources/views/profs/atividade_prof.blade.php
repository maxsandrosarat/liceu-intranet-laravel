@extends('layouts.app', ["current"=>"atividade"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades - {{$disciplina->nome}} (@if($disciplina->ensino=='fund') Fundamental @else Médio @endif)</h5>
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                </div>
            @endif
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalNovo" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Atividade">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <div class="modal fade" id="exampleModalNovo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Criar Atividade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                    <form method="POST" action="/prof/atividade" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="disciplina" value="{{$disciplina->id}}">
                        <label for="turma">Turma</label>
                        <select class="custom-select" id="turma" name="turma" required>
                            <option value="">Selecione</option>
                            @foreach ($turmas as $turma)
                            <option value="{{$turma->turma->id}}">{{$turma->turma->serie}}º ANO {{$turma->turma->turma}} (@if($turma->turma->turno=='M') Matutino @else @if($turma->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                            @endforeach
                        </select>
                        <br/><br/>
                        <label for="dataPublicacao">Publicação</label>
                        <input type="date" name="dataPublicacao" id="dataPublicacao" required>
                        <input type="time" name="horaPublicacao" id="horaPublicacao" required>
                        <br/>
                        <label for="dataExpiracao">Expiração</label>
                        <input type="date" name="dataExpiracao" id="dataExpiracao" required>
                        <input type="time" name="horaExpiracao" id="horaExpiracao" required>
                        <br/>
                        <label for="descricao">Descrição</label>
                        <input class="form-control" type="text" name="descricao" id="descricao" required>
                        <br/>
                        <label for="link">Link Videoaula</label>
                        <input class="form-control" type="text" name="link" id="link">
                        <br/>
                        <input class="form-control" type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                        <br/>
                        <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                        <br/><br/>
                        <h6>Permitir que os alunos dêem retorno desta Atividade?</h6>
                        <input type="radio" id="sim" name="retorno" value="sim" required>
                        <label for="sim">Sim</label>
                        <input type="radio" id="nao" name="retorno" value="nao" required>
                        <label for="nao">Não</label>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
            @if(count($atividades)==0)
                    <div class="alert alert-dark" role="alert">
                        @if($view=="inicial")
                        Sem atividades cadastradas! Faça novo cadastro no botão    <a type="button" href="#"><i class="material-icons blue">add_circle</i></a>   no canto inferior direito.
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/prof/atividade/{{$disciplina->id}}" class="btn btn-sm btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/prof/atividade/filtro/{{$disciplina->id}}">
                        @csrf
                        <label for="turma">Turma</label>
                            <select class="custom-select" id="turma" name="turma">
                                <option value="">Selecione</option>
                                @foreach ($turmas as $turma)
                                <option value="{{$turma->turma->id}}">{{$turma->turma->serie}}º ANO {{$turma->turma->turma}} (@if($turma->turma->turno=='M') Matutino @else @if($turma->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                @endforeach
                            </select>
                        <label for="descricao">Descrição Atividade</label>
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao">
                        <label for="data">Data Criação</label>
                            <input class="form-control mr-sm-2" type="date" name="data">
                        <button class="btn btn-sm btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <h5>Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}}) de {{$disciplina->nome}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Turma</th>
                        <th>Atividade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($atividades as $atividade)
                    <tr>
                        <td>{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}}</td>
                        <td style="text-align: center;">
                            <div class="card">
                                <div class="card-header">
                                    {{$atividade->descricao}} (Retorno: @if($atividade->retorno=="sim") Sim @else Não @endif)
                                </div>
                                <div class="card-body">
                                  <p class="card-text">Visualizações: {{$atividade->visualizacoes}} | Data Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}} @if($atividade->data_publicacao!="") | Data Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} @endif @if($atividade->data_expiracao!="") | Data Expiração: {{date("d/m/Y H:i", strtotime($atividade->data_expiracao))}}@endif</p>
                                  <a href="{{$atividade->link}}" target="_blank" class="btn btn-sm btn-primary">Link Vídeo-Aula</a>
                                  <a type="button" class="btn btn-sm btn-success" href="/prof/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditar{{$atividade->id}}"><i class="material-icons md-48">edit</i></button> <a type="button" class="btn btn-sm btn-danger" href="/prof/atividade/apagar/{{$atividade->id}}"><i class="material-icons md-48">delete</i></a>
                                  @if($atividade->retorno=="sim") <a href="/prof/atividade/retornos/{{$atividade->id}}" target="_blank" class="btn btn-sm btn-info">Retornos</a> @endif
                                </div>
                            </div>
                        </td>
                            <!-- Modal -->
                            <div class="modal fade" id="modalEditar{{$atividade->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Atividade</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>    
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/prof/atividade/editar/{{$atividade->id}}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="disciplina" value="{{$disciplina->id}}">
                                        <label for="turma" class="col-md-4 col-form-label text-md-right">Turma</label>
                                        <select class="custom-select" id="turma" name="turma" required>
                                            <option value="{{$atividade->turma_id}}">{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} (@if($atividade->turma->turno=='M') Matutino @else @if($atividade->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @foreach ($turmas as $turma)
                                            @if($turma->turma->id==$atividade->turma_id)
                                            @else
                                            <option value="{{$turma->turma->id}}">{{$turma->turma->serie}}º ANO {{$turma->turma->turma}} (@if($turma->turma->turno=='M') Matutino @else @if($turma->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <br/><br/>
                                        <label for="dataPublicacao">Data Publicação</label><br/>
                                        <input type="date" name="dataPublicacao" id="dataPublicacao" value="{{date("Y-m-d", strtotime($atividade->data_publicacao))}}">
                                        <input type="time" name="horaPublicacao" id="horaPublicacao" value="{{date("H:i", strtotime($atividade->data_publicacao))}}">
                                        <br/>
                                        <label for="dataExpiracao">Data Expiração</label><br/>
                                        <input type="date" name="dataExpiracao" id="dataExpiracao" value="{{date("Y-m-d", strtotime($atividade->data_expiracao))}}" required>
                                        <input type="time" name="horaExpiracao" id="horaExpiracao" value="{{date("H:i", strtotime($atividade->data_expiracao))}}" required>
                                        <br/><br/>
                                        <label for="descricao">Descrição</label>
                                        <input class="form-control" type="text" name="descricao" id="descricao" value="{{$atividade->descricao}}" required>
                                        <br/>
                                        <label for="link">Link da Videoaula</label>
                                        <input class="form-control" type="text" name="link" id="link" value="{{$atividade->link}}"><br/>
                                        <input class="form-control" type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf"><br/>
                                        <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b><br/><br/>
                                        <h6>Permitir que os alunos dêem retorno desta Atividade?</h6>
                                        <input type="radio" id="sim" name="retorno" value="sim" required @if($atividade->retorno=="sim") checked @endif>
                                        <label for="sim">Sim</label>
                                        <input type="radio" id="nao" name="retorno" value="nao" required @if($atividade->retorno=="nao") checked @endif>
                                        <label for="nao">Não</label>
                                        
                                    </div>
                                    <div class="modal-footer">
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
    <br/>
    <a href="/prof/disciplinasAtividades" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection