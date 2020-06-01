@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Disciplinas</h5>
            @if(count($discs)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem disciplinas cadastradas!
                </div>
            @else
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Ensino</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($discs as $disc)
                    <tr>
                        <td>{{$disc->id}}</td>
                        <td>{{$disc->nome}}</td>
                        <td>@if($disc->ensino=='fund') Fundamental @else Médio @endif</td>
                        <td><a href="/disciplinas/apagar/{{$disc->id}}" class="btn btn-sm btn-danger">Apagar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
    <br>
    <!-- Button trigger modal -->
    <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Nova Disciplina">
        <i class="material-icons blue md-60">add_circle</i>
    </a>
  
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Disciplinas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="card border">
                        <div class="card-body">
                            <form action="/disciplinas" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="nome">Nome da Disciplina</label>
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome da disciplina" required>
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
                                <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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