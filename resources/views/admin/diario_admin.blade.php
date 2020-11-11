@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body bg-light">
            <h3 class="card-title" style="text-align: center;">Diário de Turma - 
                @php
                $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');
                $diasemana_numero = date('w', strtotime($dia)); 
                @endphp
                Dia: {{date("d/m/Y", strtotime($dia))}} ({{$diasemana[$diasemana_numero]}}) - {{$turma->serie}}º ANO {{$turma->turma}}
            </h3>
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
            @if(count($diarios)==0)
                <div class="alert alert-dark" role="alert">
                    Sem lançamento até o momento!
                </div>
            @else
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="accordion" id="accordionExample">
                            @foreach ($diarios as $diario)
                            <div class="card">
                              <div class="card-header" id="heading{{$diario->id}}">
                                <h5 class="mb-0">
                                  <a type="button" data-toggle="collapse" data-target="#collapse{{$diario->id}}" aria-expanded="true" aria-controls="collapse{{$diario->id}}">
                                    {{$diario->tempo}}º Tempo @if($diario->segundo_tempo==1) & {{$diario->outro_tempo}}º Tempo @endif- {{$diario->disciplina->nome}} - Prof(a). {{$diario->prof->name}}
                                  </a>
                                </h5>
                              </div>
                              <div id="collapse{{$diario->id}}" class="collapse" aria-labelledby="heading{{$diario->id}}" data-parent="#accordionExample">
                                <div class="card-body">
                                    <b><p>
                                        Conteúdo: {{$diario->tema}}<br/>
                                        {{$diario->conteudo}}<br/>
                                        Referências: {{$diario->referencias}}<br/>
                                        Tarefa: {{$diario->tarefa}}<br/>
                                        Forma de Entrega da Tarefa:@if($diario->tipo_tarefa=="AULA") VISTADA EM AULA @else ENVIADA NO SCULES @endif<br/>
                                        Data da Entrega da Tarefa: {{date("d/m/Y", strtotime($diario->entrega_tarefa))}}<br/>
                                        Prof(a). {{$diario->prof->name}}
                                        <br/><br/>
                                        <a type="button" @if($diario->conferido===0) href="/admin/diario/conferido/{{$diario->id}}" class="btn btn-sm btn-warning" @else class="btn btn-sm btn-success" @endif>
                                            <b>@if($diario->conferido===0)<i class="material-icons md-18 red">highlight_off</i> NÃO CONFERIDO @else <i class="material-icons md-18 green">check_circle</i> CONFERIDO @endif</b>
                                        </a>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal{{$diario->id}}">
                                            <i class="material-icons md-18">edit</i>
                                        </button>
                                        <a type="button" href="/admin/diario/apagar/{{$diario->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Excluir Diário">
                                            <i class="material-icons md-18 white">delete</i>
                                        </a>
                                    </p></b>
                                    <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{$diario->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Editar Diário - {{$diario->tempo}}º Tempo @if($diario->segundo_tempo==1) & {{$diario->outro_tempo}}º Tempo @endif- {{$diario->disciplina->nome}}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/admin/diario/editar/{{$diario->id}}">
                                                            @csrf
                                                            <b><label for="tema">Tema da Aula</label></b>
                                                            <input class="form-control" type="text" name="tema" id="tema" @if($diario->tema!="") value="{{$diario->tema}}" @else placeholder="Exemplo: Pré-História" @endif required>
                                                            <b><label for="conteudo">Conteúdo</label></b>
                                                            <textarea class="form-control" name="conteudo" id="conteudo" rows="5" cols="40" maxlength="245" placeholder="Exemplo: Características, Períodos e Curiosidades" required>@if($diario->conteudo!=""){{$diario->conteudo}}@endif</textarea>
                                                            <b><label for="referencias">Referências</label></b>
                                                            <input class="form-control" type="text" name="referencias" id="referencias" @if($diario->referencias!="") value="{{$diario->referencias}}" @else placeholder="Exemplo: Livro 1 Cap. 3 Pág. 50" @endif required>
                                                            <b><label for="tarefa">Tarefa</label></b>
                                                            <textarea class="form-control" name="tarefa" id="tarefa" rows="5" cols="40" maxlength="245" placeholder="Exemplo: Exercícios 1 à 10 (Módulo 1)" required>@if($diario->tarefa!=""){{$diario->tarefa}}@endif</textarea>
                                                            <b><label for="tempo">Tipo de Tarefa</label></b>
                                                            <select class="custom-select" id="tipoTarefa" name="tipoTarefa" required>
                                                                @if($diario->tipo_tarefa=="")
                                                                <option value="">Selecione tipo de tarefa</option>
                                                                <option value="AULA"> VISTADA EM AULA </option>
                                                                <option value="SCULES"> ENVIADA NO SCULES </option>
                                                                @else
                                                                    <option value="{{$diario->tipo_tarefa}}">@if($diario->tipo_tarefa=="AULA") VISTADA EM AULA @else ENVIADA NO SCULES @endif</option>
                                                                    @if($diario->tipo_tarefa=="AULA")
                                                                    <option value="SCULES"> ENVIADA NO SCULES </option>
                                                                    @else
                                                                    <option value="AULA"> VISTADA EM AULA </option>
                                                                    @endif
                                                                @endif
                                                            </select>
                                                            <b><label for="data">Data da Entrega da Tarefa
                                                            <input class="form-control" type="date" name="entregaTarefa" @if($diario->entrega_tarefa!="") value="{{date("Y-m-d", strtotime($diario->entrega_tarefa))}}" @endif required></label></b>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Editar Diário</button>
                                                    </div>
                                                        </form>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                              </div>
                            </div>
                            @endforeach
                          </div>
                    </div>
                </div>
            </div>
            @endif
            <br/><br/>
                        <b><h3 class="card-title" style="text-align: center;">Ocorrências do Dia</h3></b>
                        @if(count($ocorrencias)==0)
                            <div class="alert alert-dark" role="alert">
                                Sem ocorrências lançadas!
                            </div>
                        @else
                        <div class="table-responsive-xl">
                        <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Ocorrência</th>
                                    <th>Aluno</th>
                                    <th>Disciplina</th>
                                    <th>Observação</th>
                                    <th>Ações</th>
                                    <th>Aprovação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ocorrencias as $ocorrencia)
                                @if($ocorrencia->aluno->turma_id==$turma->id)
                                <tr>
                                    <td>{{$ocorrencia->tipo_ocorrencia->codigo}} - {{$ocorrencia->tipo_ocorrencia->descricao}}</td>
                                    <td>{{$ocorrencia->aluno->name}}</td>
                                    <td>{{$ocorrencia->disciplina->nome}}</td>
                                    <td>
                                        @if($ocorrencia->observacao=="")
                                        <h6 style="color: red;">Sem observação</h6>
                                        @else
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModalOb{{$ocorrencia->id}}">
                                            Ver Observação
                                        </button>
                                        @endif
                                        <div class="modal fade" id="exampleModalOb{{$ocorrencia->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Observação</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{$ocorrencia->observacao}}</p>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($ocorrencia->aprovado==1) 
                                        @else
                                            @if($ocorrencia->aprovado!==NULL)
                                            @else
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalOcorrencia{{$ocorrencia->id}}">
                                            Editar
                                        </button>
                                        
                                        <div class="modal fade" id="exampleModalOcorrencia{{$ocorrencia->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Editar Ocorrência</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/admin/ocorrencias/editar/{{$ocorrencia->id}}" method="POST">
                                                        @csrf
                                                        <h6><b>Aluno: {{$ocorrencia->aluno->name}}</b></h6>
                                                        <h6><b>Tipo de Ocorrência: {{$ocorrencia->tipo_ocorrencia->codigo}} - {{$ocorrencia->tipo_ocorrencia->descricao}}</b></h6>
                                                        <h6><b>Disciplina: {{$ocorrencia->disciplina->nome}}</b></h6>
                                                        <br/>
                                                        <label for="observacao">Observação</label>
                                                        <textarea class="form-control" name="observacao" id="observacao" rows="10" cols="40" maxlength="500">{{$ocorrencia->observacao}}</textarea> 
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sn">Editar</button>
                                                </div>
                                            </form>
                                            </div>
                                            </div>
                                        </div>
                                    </td>
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($ocorrencia->aprovado==1)
                                            <b><p style="color: green; font-size: 50%;"><i class="material-icons green">check_circle</i> APROVADO</p></b>
                                        @else
                                            @if($ocorrencia->aprovado!==NULL)
                                                <b><p style="color: red; font-size: 50%;"><i class="material-icons green">highlight_off</i>REPROVADO</p></b>
                                            @else
                                                <a href="/admin/ocorrencias/aprovar/{{$ocorrencia->id}}" class="btn btn-sm btn-success">Aprovar</a>
                                                <a href="/admin/ocorrencias/reprovar/{{$ocorrencia->id}}" class="btn btn-sm btn-danger">Reprovar</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        @endif


        </div>
    </div>
    <br>
    <a href="/admin/diario" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
    <script type="text/javascript">
        // Remove botão de download de vídeos
        iframe::-internal-media-controls-download-button {
        display:none;
        }

        iframe::-webkit-media-controls-enclosure {
            overflow:hidden;
        }

        iframe::-webkit-media-controls-panel {
            width: calc(100% + 30px); 
        }
    </script>
@endsection