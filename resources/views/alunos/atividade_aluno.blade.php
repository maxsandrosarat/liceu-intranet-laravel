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
        @if(count($atividades)==0)
                <div class="alert alert-dark" role="alert">
                    @if($view=="inicial")
                    Sem atividades cadastradas!
                    @endif
                    @if($view=="filtro")
                    Sem resultados da busca!
                    <a href="/aluno/atividade/{{$disciplina->id}}" class="btn btn-sm btn-success">Voltar</a>
                    @endif
                </div>
        @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/aluno/atividade/filtro/{{$disciplina->id}}">
                        @csrf
                        <input type="hidden" name="disc" value="{{$disciplina->id}}">
                        <label for="descricao">Descrição Atividade
                            <input class="form-control mr-sm-2" type="text" placeholder="Digite a descrição" name="descricao"></label>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
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
                    $contador=0;
                    foreach ($retornos as $retorno){
                        if($retorno->atividade_id==$atividade->id){
                            $contador++;
                        }
                    }
                @endphp
                <a class="fill-div" data-toggle="modal" data-target="#exampleModal{{$atividade->id}}">
                    <div id="my-div" class="bd-callout @if($contador>0) bd-callout-success @else @if($dataAtiv==$data && $atividade->data_entrega>$dataHora) bd-callout-warning @else @if($atividade->data_entrega>$dataHora) bd-callout-info @else @if($atividade->data_entrega<$dataHora) bd-callout-danger @endif @endif @endif @endif ">
                        <h4>{{$atividade->descricao}} - Entrega: {{date("d/m/Y H:i", strtotime($atividade->data_entrega))}}</h4>
                        <p>Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}}</p>
                    </div>
                </a>
                <!-- Modal Atividade -->
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
                                    Professor(a): {{$atividade->prof->name}} - Disciplina: {{$atividade->disciplina->nome}} <br/> <hr/>
                                    Turma: {{$atividade->turma->serie}}º ANO {{$atividade->turma->turma}} <br/> <hr/>
                                    Descrição: {{$atividade->descricao}} <br/> <hr/>
                                    Data Publicação: {{date("d/m/Y H:i", strtotime($atividade->data_publicacao))}} <br/> <hr/>
                                    Data de Entrega: {{date("d/m/Y H:i", strtotime($atividade->data_entrega))}} <br/> <hr/>
                                    Criado por: {{$atividade->usuario}}<br/>
                                    Data da Criação: {{date("d/m/Y H:i", strtotime($atividade->created_at))}}<br/>
                                    Última Alteração: {{date("d/m/Y H:i", strtotime($atividade->updated_at))}}
                                </p>
                            </div>
                            <div class="modal-footer">
                                @if($atividade->link!="")<a href="{{$atividade->link}}" target="_blank" class="btn btn-primary btn-sm">Link Vídeo-Aula</a>@endif
                                <a type="button" class="badge badge-success" href="/aluno/atividade/download/{{$atividade->id}}"><i class="material-icons md-48">cloud_download</i></a>
                                @if($atividade->retorno=="1")
                                    @if(count($retornos)==0)
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalCreate{{$atividade->id}}">
                                            Enviar Retorno
                                        </button>
                                    @else
                                        @if($contador>0)
                                            <i class="material-icons md-48 green600">check_circle</i>
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalEdit{{$atividade->id}}">
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
                                                        <br/>
                                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                                    </form>    
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
                                                <div class="modal-body">
                                                @foreach ($retornos as $retorno)
                                                @if($retorno->atividade_id==$atividade->id)
                                                <form method="POST" action="/aluno/atividade/retorno/editar/{{$atividade->id}}" enctype="multipart/form-data">
                                                    @csrf
                                                        <label for="comentario">Comentário
                                                        <textarea class="form-control" name="comentario" id="comentario" rows="10" cols="40" maxlength="500">{{$retorno->comentario}}</textarea></label>
                                                        <br/>
                                                        <input class="form-control-file" type="file" id="arquivo" name="arquivo" accept=".doc,.docx,.pdf">
                                                        <br/>
                                                        <b style="font-size: 80%;">Aceito apenas extensões do Word e PDF (".doc", ".docx" e ".pdf")</b>
                                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                                </form>
                                                @endif
                                                @endforeach
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
<br/>
<a href="/aluno/atividade/disciplinas" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection