@extends('layouts.app', ["current"=>"atividade"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Atividades - Disciplina: {{$disciplina->nome}}</h5>
            @if(count($atividades)==0)
                <div class="alert alert-danger" role="alert">
                    Sem atividades para exibir!
                </div>
            @else
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/aluno/atividade/filtro">
                        @csrf
                        <input type="hidden" name="disc" value="{{$disciplina->id}}">
                        <label for="descricao">Descrição Atividade
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao"></label>
                        <br>
                            <label for="data">Data Expiração 
                            - De <input class="form-control mr-sm-2" type="date" name="expiracaoDe">
                            Até <input class="form-control mr-sm-2" type="date" name="expiracaoAte"></label>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <h6>Exibindo {{$atividades->count()}} de {{$atividades->total()}} de Atividades ({{$atividades->firstItem()}} a {{$atividades->lastItem()}})</h6>
            @foreach ($atividades as $atividade)
                <div class="d-flex justify-content-center centralizado">
                    <div class="card border-primary text-center" style="width: 20rem;">
                        <div class="card-body">
                            <h5>{{$atividade->descricao}}</h5>
                            <p class="card-text">
                                <ul>
                                    <li>Visualizações: {{$atividade->visualizacoes}}</li>
                                    <li>Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}}</li>
                                    <li>@if($atividade->data_publicacao!="")Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} @endif</li>
                                    <li>@if($atividade->data_expiracao!="") <b style="color:red;">Expiração: {{date("d/m/Y H:i", strtotime($atividade->data_expiracao))}}</b> @endif</li>
                                </ul>
                            </p>
                            @if($atividade->link!="")<a href="{{$atividade->link}}" target="_blank" class="btn btn-primary">Link Vídeo-Aula</a>@endif
                                  <a type="button" class="btn btn-success" href="/aluno/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a>
                                  @if($atividade->retorno=="sim")
                                    @if(count($retornos)==0)
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalCreate{{$atividade->id}}">
                                            Enviar Retorno
                                        </button>
                                    @else
                                        <?php $contador=0; ?>
                                        @foreach ($retornos as $retorno)
                                            @if($retorno->atividade_id==$atividade->id)
                                                <?php $contador++; ?>
                                            @endif
                                        @endforeach
                                        @if($contador>0)
                                            <i class="material-icons md-48 green600">check_circle</i>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalEdit{{$atividade->id}}">
                                                <i class="material-icons md-48">edit</i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModalCreate{{$atividade->id}}">
                                                Enviar Retorno
                                            </button>
                                        @endif
                                    @endif
                                @endif
                        </div>
                    </div>
                </div>
                    
                                    
                                  
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCreate{{$atividade->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Retorno da Atividade - {{$atividade->descricao}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/aluno/atividade/retorno/{{$atividade->id}}" enctype="multipart/form-data">
                                            @csrf
                                            <label for="comentario">Comentário
                                            <textarea class="form-control" name="comentario" id="comentario" rows="10" cols="40" maxlength="500"></textarea></label>
                                            <br/>
                                            <input class="form-control-file" type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                            <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                        </div>
                                    </form>    
                                    </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                            <!-- Modal -->
                                <div class="modal fade" id="exampleModalEdit{{$atividade->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar Retorno da Atividade - {{$atividade->descricao}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @foreach ($retornos as $retorno)
                                    @if($retorno->atividade_id==$atividade->id)
                                    <div class="modal-body">
                                        <form method="POST" action="/aluno/atividade/retorno/editar/{{$retorno->id}}" enctype="multipart/form-data">
                                            @csrf
                                            <label for="comentario">Comentário
                                            <textarea class="form-control" name="comentario" id="comentario" rows="10" cols="40" maxlength="500">{{$retorno->comentario}}</textarea></label>
                                            <br/>
                                            <input class="form-control-file" type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf" required>
                                            <br/>
                                            <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                    </div>
                                    @endif
                                    @endforeach   
                                    <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                    </form>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                    @endforeach
            <div class="card-footer">
                {{$atividades->links() }}
            </div>
            @endif
        </div>
    </div>
    <br/>
    <a href="/aluno/atividade/disciplinas" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection