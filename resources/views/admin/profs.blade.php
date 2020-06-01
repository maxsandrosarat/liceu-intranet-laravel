@extends('layouts.app', ["current"=>"administrativo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Lista de Professores</h5>
            <a type="button" class="float-button" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="bottom" title="Adicionar Novo Professor(a)">
                <i class="material-icons blue md-60">add_circle</i>
            </a>
            @if(count($profs)==0)
                <br/><br/>
                <div class="alert alert-danger" role="alert">
                    Sem professores cadastrados!
                </div>
            @else
            <div class="card border">
                <h5>Filtros: </h5>
            <form class="form-inline my-2 my-lg-0" method="GET" action="/prof/filtro">
                @csrf
                <input class="form-control mr-sm-2" type="text" placeholder="Nome do Professor" name="nome">
                <select id="disciplina" name="disciplina">
                    <option value="">Selecione uma disciplina</option>
                    @foreach ($discs as $disc)
                        <option value="{{$disc->id}}">{{$disc->nome}} (@if($disc->ensino=="fund") Fundamental @else Médio @endif)</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
            </form>
            </div>
            <br>
            <h5>Exibindo {{$profs->count()}} de {{$profs->total()}} de Professor(es) ({{$profs->firstItem()}} a {{$profs->lastItem()}})</h5>
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Disciplinas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($profs as $prof)
                    <tr>
                        <td>{{$prof->id}}</td>
                        <td>{{$prof->name}}</td>
                        <td>{{$prof->email}}</td>
                        <td>
                            <ul>
                                @foreach ($prof->disciplinas as $disciplina)
                                <li>{{$disciplina->nome}} (@if($disciplina->ensino=='fund') Fundamental @else Médio @endif) <a href="/prof/apagarDisciplina/{{$prof->id}}/{{$disciplina->id}}" class="btn btn-danger btn-sm">Apagar</a></li>
                                <br/>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$prof->id}}">
                                Editar
                            </button>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$prof->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edição de Professor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card-body">
                                            <form method="POST" action="/prof/editar/{{$prof->id}}">
                                                @csrf
                                                <div class="form-group row">
                                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$prof->name}}" required autocomplete="name" autofocus>
                        
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$prof->email}}" required autocomplete="email">
                        
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                        
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                        
                                                <div class="form-group row">
                                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                        
                                                    <div class="col-md-6">
                                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                                    </div>
                                                </div>
                        
                                                <hr/>
                                                <div class="form-group row">
                                                    <h3>Disciplinas</h3>
                                                    <br>
                                                    <ul>
                                                    @foreach ($discs as $disc)
                                                    <li><input type="checkbox" id="disciplina{{$disc->id}}" name="disciplinas[]" value="{{$disc->id}}" @foreach ($prof->disciplinas as $disciplina) @if($disciplina->id==$disc->id) checked @endif @endforeach>
                                                    <label for="disciplina{{$disc->id}}">{{$disc->nome}} (@if($disc->ensino=="fund") Fundamental @else Médio @endif)</label></li>
                                                    @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-6 offset-md-4">
                                                            <button type="submit" class="btn btn-primary">
                                                                {{ __('Salvar') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <a href="/prof/apagar/{{$prof->id}}" class="btn btn-sm btn-danger">Excluir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{$profs->links() }}
            </div>
            @endif
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cadastro de Professor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form method="POST" action="/prof">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Login') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmação Senha') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row">
                            <h3>Disciplinas</h3>
                            <br>
                            <ul>
                            @foreach ($discs as $disc)
                            <li><input type="checkbox" id="disciplina{{$disc->id}}" name="disciplinas[]" value="{{$disc->id}}">
                            <label for="disciplina{{$disc->id}}">{{$disc->nome}} (@if($disc->ensino=="fund") Fundamental @else Médio @endif)</label></li>
                            @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Cadastrar') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    <br/>
    <a href="/admin/administrativo" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection
