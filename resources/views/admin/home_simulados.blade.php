@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <p>{{session('mensagem')}}</p>
            </div>
            @endif
            @if(count($simulados)==0)
                <div class="alert alert-danger" role="alert">
                    Sem provas cadastradas!
                </div>
            @else
            <form action="/admin/simulados" method="GET">
                @csrf
                <label for="ano">Selecione o ano:
                <select class="custom-select" id="ano" name="ano">
                    <option value="">Selecione</option>
                    @foreach ($anos as $an)
                    <option value="{{$an->ano}}">{{$an->ano}}</option>
                    @endforeach
                  </select></label>
                <input type="submit" class="btn btn-primary" value="Selecionar">
            </form>
            <h5 class="card-title">Questões para Provas - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Prova</th>
                        <th>Descrição</th>
                        <th>Bimestre</th>
                        <th>Ano</th>
                        <th>Série(s)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($simulados as $sim)
                    <tr>
                        <td>{{$sim->id}}</td>
                        <td>{{$sim->descricao}}</td>
                        <td>{{$sim->bimestre}}° Bimestre</td>
                        <td>{{$sim->ano}}</td>
                        <td>
                            <ul>
                            @foreach ($sim->series as $serie)
                                <li>{{$serie->serie}}º ANO</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="/admin/simulados/painel/{{$sim->id}}" class="badge badge-primary" data-toggle="tooltip" data-placement="right" title="Painel"><i class="material-icons md-48">attach_file</i></a>
                            <button type="button" class="badge badge-danger" data-toggle="modal" data-target="#exampleModalDelete{{$sim->id}}"><i class="material-icons md-48">delete</i></button></td>
                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="exampleModalDelete{{$sim->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Excluir Prova: {{$sim->descricao}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>Tem certeza que deseja excluir essa prova?</h5>
                                            <p>Não será possivel reverter esta ação.</p>
                                            <a href="/admin/simulados/apagar/{{$sim->id}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="right" title="Inativar">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
    <br>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="habilitarSubmit();">
        Gerar Painel
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Gerar Painel</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <form id="form-sim" action="/admin/simulados/gerar" method="POST">
                    @csrf
                <label for="descricao">Descrição da Prova: </label>
                <input class="form-control" type="text"  name="descricao" id="descricao" required>
                <label for="ano">Ano: </label>
                <input class="form-control" type="number"  name="ano" id="ano" required>
                <label for="bimestre">Bimestre: </label>
                <select class="custom-select" id="bimestre" name="bimestre" required>
                    <option value="">Selecione</option>
                    <option value="1">1º</option>
                    <option value="2">2º</option>
                    <option value="3">3º</option>
                    <option value="4">4º</option>
                </select>
                <br/><br/>
                <button class="btn btn-primary btn-sm" id="botao" type="button" data-toggle="tooltip" data-placement="bottom" title="Marcar Todas as Séries" onclick="marcacao()">Marcar Todas</button>
                <br/>
                <div class="checkbox-group required">
                @foreach ($turmas as $turma)
                <br/>
                <input type="checkbox" class="check" id="turma{{$turma->id}}" name="turmas[]" value="{{$turma->id}}">
                <label for="turma{{$turma->id}}">{{$turma->serie}}º ANO (@if($turma->ensino == "fund") Fundamental @else Médio @endif)</label>
                @endforeach
                </div>
                <br/><br/>
                <button type="submit" id="processamento" class="btn btn-primary">Gerar</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    </div>
    <br><br>
    <a href="/admin/pedagogico" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
    <script type="text/javascript">
        function marcacao(){
            let checkbox = document.querySelectorAll('.check')

            var botao = document.getElementById("botao");
            if(botao.innerHTML=="Marcar Todas"){
                for(let current of checkbox){
                    current.checked = true
                }
                botao.innerHTML = "Desmarcar Todas";
                botao.className = "btn btn-warning btn-sm";
                botao.title = "Desmarcar Todas as Séries";
            } else {
                for(let current of checkbox){
                    current.checked = false
                }
                botao.innerHTML = "Marcar Todas";
                botao.className = "btn btn-primary btn-sm";
                botao.title = "Marcar Todas as Séries";
            }
        }
    </script>
@endsection