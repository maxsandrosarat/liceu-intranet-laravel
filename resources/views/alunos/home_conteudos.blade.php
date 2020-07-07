@extends('layouts.app', ["current"=>"conteudo"])

@section('body')
    <div class="card border">
        <div class="card-body">
            @if(session('mensagem'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <p>{{session('mensagem')}}</p>
            </div>
            @endif
            <h5 class="card-title">Conteúdos pelos Professores - {{$ano}}</h5>
            <div class="table-responsive-xl">
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr style="text-align: center;">
                        <th>Bimestre</th>
                        <th>Avaliação Parcial - AP</th>
                        <th>Prova Bimestral - PB</th>
                        <th>Simulado - SIM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1º</td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/1/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/1/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/1/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2º</td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/2/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/2/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/2/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3º</td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/3/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/3/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/3/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4º</td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/4/AP" class="btn btn-primary">AP</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/4/PB" class="btn btn-primary">PB</a></td>
                        <td><a href="/aluno/conteudos/painel/{{$ano}}/4/SIM" class="btn btn-primary">SIM</a></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br/>
    <a href="/aluno" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Voltar"><i class="material-icons white">reply</i></a>
@endsection