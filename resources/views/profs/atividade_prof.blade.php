@extends('layouts.app', ["current"=>"atividade"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades - Disciplina: {{$disciplina->nome}} - {{date("d/m/Y H:i")}}</h5>
            @if(session('mensagem'))
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <p>{{session('mensagem')}}</p>
                        </div>
                    </div>
                </div>
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
                        <label for="dataRemocao">Remoção</label>
                        <input type="date" name="dataRemocao" id="dataRemocao" required>
                        <input type="time" name="horaRemocao" id="horaRemocao" required>
                        <br/>
                        <label for="dataEntrega">Entrega</label>
                        <input type="date" name="dataEntrega" id="dataEntrega" required>
                        <input type="time" name="horaEntrega" id="horaEntrega" required>
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
                        <input type="radio" id="sim" name="retorno" value="1" required>
                        <label for="sim">Sim</label>
                        <input type="radio" id="nao" name="retorno" value="0" required>
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
                        Sem atividades cadastradas!
                        @endif
                        @if($view=="filtro")
                        Sem resultados da busca!
                        <a href="/prof/atividade/{{$disciplina->id}}" class="btn btn-sm btn-success">Voltar</a>
                        @endif
                    </div>
            @else
            <div class="card border">
                <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/prof/atividade/filtro/{{$disciplina->id}}">
                        @csrf
                        <label for="turma">Turma
                            <select class="custom-select" id="turma" name="turma">
                                <option value="">Selecione</option>
                                @foreach ($turmas as $turma)
                                <option value="{{$turma->turma->id}}">{{$turma->turma->serie}}º ANO {{$turma->turma->turma}} (@if($turma->turma->turno=='M') Matutino @else @if($turma->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                @endforeach
                            </select></label>
                        <label for="descricao">Descrição Atividade</label>
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao">
                        <button class="btn btn-sm btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
            </div>
            <hr/>
            <b><h5 class="font-italic">Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}})</u></h5></b>
            <hr/>
            <div class="table-responsive-xl">
                @foreach ($atividades as $atividade)
                @php
                    $dataHora = date("Y-m-d H:i");
                    $data = date("Y-m-d");
                    $dataAtiv = date("Y-m-d", strtotime($atividade->data_entrega));
                @endphp
                    <a class="fill-div" data-toggle="modal" data-target="#exampleModal{{$atividade->id}}"><div id="my-div" class="bd-callout @if($dataAtiv==$data && $atividade->data_entrega>$dataHora) bd-callout-warning @else @if($atividade->data_entrega>$dataHora) bd-callout-info @else @if($atividade->data_entrega<$dataHora) bd-callout-danger @endif @endif @endif">
                        <h4>{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} - {{$atividade->descricao}} - Entrega: {{date("d/m/Y H:i", strtotime($atividade->data_entrega))}}</h4>
                        <p>Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}}</p>
                    </div></a>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{$atividade->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Atividade: {{$atividade->descricao}} <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Visualizações"><i class="material-icons">visibility</i><span class="badge badge-light">{{$atividade->visualizacoes}}</span></button></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="font-weight-bolder">
                                Disciplina: {{$atividade->disciplina->nome}} <br/> <hr/>
                                Turma: {{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} <br/> <hr/>
                                Descrição: {{$atividade->descricao}} <br/> <hr/>
                                Data Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} <br/> <hr/>
                                Data Remoção: {{date("d/m/Y H:i", strtotime($atividade->data_remocao))}} <br/> <hr/>
                                Data de Entrega: {{date("d/m/Y H:i", strtotime($atividade->data_entrega))}} <br/> <hr/>
                                Criado por: {{$atividade->usuario}}<br/>
                                Data da Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}}<br/>
                                Última Alteração: {{date("d/m/Y H:i", strtotime($atividade->updated_at))}}
                            </p>
                        </div>
                        <div class="modal-footer">
                            @if($atividade->link!="")<a href="{{$atividade->link}}" target="_blank" class="btn btn-primary btn-sm">Link Vídeo-Aula</a>@endif
                            <a type="button" class="badge badge-success" href="/prof/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a> <button type="button" class="badge badge-warning" data-toggle="modal" data-target="#modalEditar{{$atividade->id}}"><i class="material-icons md-48">edit</i></button>
                            @if(count($atividade->retornos)==0)
                            <a type="button" class="badge badge-danger" href="#" data-toggle="modal" data-target="#exampleModalDelete{{$atividade->id}}" data-toggle="tooltip" data-placement="bottom" title="Excluir Atividade"><i class="material-icons md-48">delete</i></a> <br/> <hr/>
                            @endif
                            @if($atividade->retorno=="1")
                            <a href="/prof/atividade/retornos/{{$atividade->id}}" class="btn btn-info btn-sm">Retornos <span class="badge badge-light">{{count($atividade->retornos)}}</span></a> <br/> <hr/>
                            @endif
                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEditar{{$atividade->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Atividade: {{$atividade->descricao}} - {{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/prof/atividade/editar/{{$atividade->id}}" enctype="multipart/form-data">
                                        @csrf
                                        <label for="turma">Turma</label>
                                        <select id="turma" class="form-control" name="turma" required>
                                            <option value="{{$atividade->turma->id}}">{{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} (@if($atividade->turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @foreach ($turmas as $turma)
                                            @if($turma->turma->id!=$atividade->turma->id)
                                            <option value="{{$turma->turma->id}}">{{$turma->turma->serie}}º ANO {{$turma->turma->turma}} (@if($turma->turma->turno=='M') Matutino @else @if($turma->turma->turno=='V') Vespertino @else Noturno @endif @endif)</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <br/>
                                        <label for="dataPublicacao" class="col-md-4 col-form-label text-md-right">Data Publicação</label>
                                        <input type="date" name="dataPublicacao" id="dataPublicacao" value="{{date("Y-m-d", strtotime($atividade->data_publicacao))}}" required>
                                        <input type="time" name="horaPublicacao" id="horaPublicacao" value="{{date("H:i", strtotime($atividade->data_publicacao))}}" required>
                                        <br/>
                                        <label for="dataRemocao" class="col-md-4 col-form-label text-md-right">Data Remoção</label>
                                        <input type="date" name="dataRemocao" id="dataRemocao" value="{{date("Y-m-d", strtotime($atividade->data_remocao))}}" required>
                                        <input type="time" name="horaRemocao" id="horaRemocao" value="{{date("H:i", strtotime($atividade->data_remocao))}}" required>
                                        <br/>
                                        <label for="dataEntrega" class="col-md-4 col-form-label text-md-right">Data Entrega</label>
                                        <input type="date" name="dataEntrega" id="dataEntrega" value="{{date("Y-m-d", strtotime($atividade->data_entrega))}}" required>
                                        <input type="time" name="horaEntrega" id="horaEntrega" value="{{date("H:i", strtotime($atividade->data_entrega))}}" required>
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
                                        <br/><br/>
                                        <h6>Permitir que os alunos dêem retorno desta Atividade?</h6>
                                        <input type="radio" id="sim" name="retorno" value="1" @if($atividade->retorno=="1") checked @endif required>
                                        <label for="sim">Sim</label>
                                        <input type="radio" id="nao" name="retorno" value="0" @if($atividade->retorno=="0") checked @endif required>
                                        <label for="nao">Não</label>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="submit" class="btn btn-sm btn-primary">Enviar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                            <!-- Modal Deletar -->
                            <div class="modal fade" id="exampleModalDelete{{$atividade->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Excluir Atividade: {{$atividade->descricao}} - {{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>Tem certeza que deseja excluir essa atividade?</h5>
                                </div>
                                <div class="modal-footer">
                                    <a type="button" class="btn btn-danger" href="/prof/atividade/apagar/{{$atividade->id}}">Excluir</a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                @endforeach
            <div class="card-footer">
                {{ $atividades->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
<br>
<a href="/prof/atividade/disciplinas" class="btn btn-success"data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
