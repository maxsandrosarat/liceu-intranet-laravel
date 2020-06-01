@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Turmas</h5>
            @if(count($turmas)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem turmas cadastradas!
                </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Série</th>
                        <th>Turma</th>
                        <th>Turno</th>
                        <th>Ensino</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turmas as $turma)
                    <tr>
                        <td>{{$turma->id}}</td>
                        <td>{{$turma->serie}}º ANO</td>
                        <td>{{$turma->turma}}</td>
                        <td>@if($turma->turno=='M') Matutino @else @if($turma->turno=='V') Vespertino @else Noturno @endif @endif</td>
                        <td>@if($turma->ensino=='fund') Fundamental @else Médio @endif</td>
                        <td><a href="/turmas/apagar/{{$turma->id}}" class="btn btn-sm btn-danger">Apagar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
    <br>
    <!-- Button trigger modal -->
    <a type="button" class="float-button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Turma">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
  
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Turma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="card border">
                        <div class="card-body">
                            <form action="/turmas" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="serie">Série da Turma</label>
                                    <input type="number" class="form-control" name="serie" id="serie" placeholder="Digite o numero da série" required>
                                    <br/> 
                                    <label for="turma">Turma</label>
                                    <input type="text" class="form-control" name="turma" id="turma" placeholder="Digite a turma (Exemplo: A, B,...)" required>
                                    <br/> 
                                    <label for="turno">Turno</label>
                                    <select id="turno" name="turno" required>
                                        <option value="">Selecione</option>
                                            <option value="M">Matutino</option>
                                            <option value="V">Vespertino</option>
                                            <option value="N">Noturno</option>
                                    </select>
                                    <br/> 
                                    <label for="ensino">Ensino</label>
                                    <select id="ensino" name="ensino" required>
                                        <option value="">Selecione</option>
                                            <option value="fund">Fundamental</option>
                                            <option value="medio">Médio</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sn">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>    
                </div> 
            </div>
            </div>
        </div>
    <a href="/admin/administrativo" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection