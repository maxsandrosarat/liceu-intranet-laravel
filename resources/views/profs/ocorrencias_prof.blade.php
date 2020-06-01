@extends('layouts.app', ["current"=>"ocorrencias"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Painel de Ocorrências</h5>
            @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{ $message }}</strong>
                </div>
            @endif
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModalNovo" data-toggle="tooltip" data-placement="bottom" title="Lançar Ocorrências">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            <div class="modal fade" id="exampleModalNovo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lançamento de Ocorrência</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Marcar</th>
                                    <th style="text-align: center;">Aluno</th>
                                </tr>
                            </thead>
                                <tbody>
                                    <form method="POST" action="/prof/ocorrencias">
                                        @csrf
                                        @foreach ($alunos as $aluno)
                                    <tr>
                                        <td><input type="checkbox" name="alunos[]" value="{{$aluno->id}}"></td>
                                        <td>{{$aluno->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" required style="width:300px;">
                            <option value="">Selecione o tipo</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->codigo}} - {{$tipo->descricao}} - @if($tipo->tipo=="despontuacao") Despontuar: @else Elogio: @endif{{$tipo->pontuacao}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="disciplina" value="{{$disciplina}}">
                        <br/><br/>
                        <label for="data">Data</label>
                        <input type="date" name="data" required>
                        <br/><br/>
                        <label for="observacao">Observação</label>
                        <textarea name="observacao" id="observacao" rows="10" cols="40" maxlength="500" placeholder="Atenção!!! Caso marque vários alunos e faça uma observação neste momento, a mesma será para todos marcados. Caso deseje apenas para alunos especificos lance sem observação e edite o lançamento para inserir a observação."></textarea> 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
            @if(count($ocorrencias)==0)
                    <div class="alert alert-danger" role="alert">
                        @if($busca=="nao")
                        Sem ocorrências lançadas!
                        @else @if($busca=="sim")
                        Sem resultados da busca!
                        <a href="/prof/atividade/{{$disciplina->id}}" class="btn btn-success">Voltar</a>
                        @endif
                        @endif
                    </div>
            @else
            <div class="card">
                <div class="card border">
                    <h5 class="card-title">Filtros:</h5>
                    <form class="form-inline my-2 my-lg-0" method="GET" action="/prof/ocorrencias/filtro/{{$disciplina}}/{{$turma}}">
                        @csrf
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" style="width:170px;">
                            <option value="">Selecione o tipo</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->codigo}} - {{$tipo->descricao}} - @if($tipo->tipo=="despontuacao") Despontuar: @else Elogio: @endif{{$tipo->pontuacao}}</option>
                            @endforeach
                        </select>
                        <label for="aluno">Aluno</label>
                        <select id="aluno" name="aluno" style="width:170px;">
                            <option value="">Selecione o aluno</option>
                            @foreach ($alunos as $aluno)
                            <option value="{{$aluno->id}}">{{$aluno->name}}</option>
                            @endforeach
                        </select>
                        <label for="dataInicio">Data Início</label>
                        <input class="form-control mr-sm-2" type="date" name="dataInicio">
                        <label for="dataFim">Data Fim</label>
                        <input class="form-control mr-sm-2" type="date" name="dataFim">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
                    </form>
                </div>
            </div>
            <br/>
            <table class="table table-striped table-ordered table-hover" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Aluno</th>
                        <th>Tipo de Ocorrencia</th>
                        <th>Data</th>
                        <th>Observação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ocorrencias as $ocorrencia)
                    @if($ocorrencia->aluno->turma_id==$turma)
                    <tr>
                        <td>{{$ocorrencia->tipo_ocorrencia->codigo}}</td>
                        <td>{{$ocorrencia->aluno->name}}</td>
                        <td>{{$ocorrencia->tipo_ocorrencia->descricao}}</td>
                        <td>{{date("d/m/Y", strtotime($ocorrencia->data))}}</td>
                        <td>
                            @if($ocorrencia->observacao=="")
                            <h6 style="color: red;">Sem observação</h6>
                            @else
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalOb{{$ocorrencia->id}}">
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$ocorrencia->id}}">
                                Editar
                            </button>
                            
                            <div class="modal fade" id="exampleModal{{$ocorrencia->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Ocorrência</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/prof/ocorrencias/editar/{{$ocorrencia->id}}" method="POST">
                                            @csrf
                                            <h6><b>Aluno: {{$ocorrencia->aluno->name}}</b></h6>
                                            <label for="tipo">Tipo</label>
                                            <select id="tipo" name="tipo" required style="width:300px;">
                                                <option value="{{$ocorrencia->tipo_ocorrencia_id}}">Selecione o tipo</option>
                                                @foreach ($tipos as $tipo)
                                                <option value="{{$tipo->id}}">{{$tipo->codigo}} - {{$tipo->descricao}} - @if($tipo->tipo=="despontuacao") Despontuar: @else Elogio: @endif{{$tipo->pontuacao}}</option>
                                                @endforeach
                                            </select>
                                            <br/><br/>
                                            <label for="data">Data</label>
                                            <input type="date" name="data" value="{{$ocorrencia->data}}" required>
                                            <br/><br/>
                                            <label for="observacao">Observação</label>
                                            <textarea name="observacao" id="observacao" rows="10" cols="40" maxlength="500">{{$ocorrencia->observacao}}</textarea> 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                    </div>
                                </form>
                                </div>
                                </div>
                            </div>
                            <a href="/prof/ocorrencias/apagar/{{$disciplina}}/{{$turma}}/{{$ocorrencia->id}}" class="btn btn-sm btn-danger">Apagar</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$ocorrencias->links() }}
            </div>
            @endif
        </div>
    </div>
    <br>
    <a href="/prof/ocorrencias/{{$disciplina}}" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection