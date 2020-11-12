@extends('layouts.app', ["current"=>"diario"])

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
                                    </p></b>
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
                                            <b><p style="color: green; font-size: 50%;"><i class="material-icons green">check_circle</i> APROVADO</p></b>
                                        @else
                                            @if($ocorrencia->aprovado!==NULL)
                                                <b><p style="color: red; font-size: 50%;"><i class="material-icons green">highlight_off</i>REPROVADO</p></b>
                                            @else
                                                <p><i class="material-icons">update</i> Aguardando Análise</p>
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
    <a href="/outro/diario" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection