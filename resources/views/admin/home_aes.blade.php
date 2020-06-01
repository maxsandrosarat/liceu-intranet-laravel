@extends('layouts.app', ["current"=>"pedagogico"])

@section('body')
    <div class="card border">
        <div class="card-body">
            <h5 class="card-title">Atividades Extras com Data de Entrega pelos Professores - {{date("Y")}}</h5>
            <table class="table table-striped table-ordered table-hover">
                <thead class="thead-dark">
                    <tr style="text-align: center;">
                        <th>Bimestre</th>
                        <th>AE01</th>
                        <th>AE02</th>
                        <th>AE03</th>
                        <th>AE04</th>
                        <th>AE05</th>
                        <th>AE06</th>
                        <th>AE07</th>
                        <th>AE08</th>
                        <th>AE09</th>
                        <th>AE10</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1º</td>
                        <td><a href="/atividadeExtra/2020/1/1" class="btn btn-primary">AE01</a></td>
                        <td><a href="/atividadeExtra/2020/2/1" class="btn btn-primary">AE02</a></td>
                        <td><a href="/atividadeExtra/2020/3/1" class="btn btn-primary">AE03</a></td>
                        <td><a href="/atividadeExtra/2020/4/1" class="btn btn-primary">AE04</a></td>
                        <td><a href="/atividadeExtra/2020/5/1" class="btn btn-primary">AE05</a></td>
                        <td><a href="/atividadeExtra/2020/6/1" class="btn btn-primary">AE06</a></td>
                        <td><a href="/atividadeExtra/7/1" class="btn btn-primary">AE07</a></td>
                        <td><a href="/atividadeExtra/8/1" class="btn btn-primary">AE08</a></td>
                        <td><a href="/atividadeExtra/9/1" class="btn btn-primary">AE09</a></td>
                        <td><a href="/atividadeExtra/10/1" class="btn btn-primary">AE10</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2º</td>
                        <td><a href="/atividadeExtra/1/2" class="btn btn-primary">AE01</a></td>
                        <td><a href="/atividadeExtra/2/2" class="btn btn-primary">AE02</a></td>
                        <td><a href="/atividadeExtra/3/2" class="btn btn-primary">AE03</a></td>
                        <td><a href="/atividadeExtra/4/2" class="btn btn-primary">AE04</a></td>
                        <td><a href="/atividadeExtra/5/2" class="btn btn-primary">AE05</a></td>
                        <td><a href="/atividadeExtra/6/2" class="btn btn-primary">AE06</a></td>
                        <td><a href="/atividadeExtra/7/2" class="btn btn-primary">AE07</a></td>
                        <td><a href="/atividadeExtra/8/2" class="btn btn-primary">AE08</a></td>
                        <td><a href="/atividadeExtra/9/2" class="btn btn-primary">AE09</a></td>
                        <td><a href="/atividadeExtra/10/2" class="btn btn-primary">AE10</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3º</td>
                        <td><a href="/atividadeExtra/1/3" class="btn btn-primary">AE01</a></td>
                        <td><a href="/atividadeExtra/2/3" class="btn btn-primary">AE02</a></td>
                        <td><a href="/atividadeExtra/3/3" class="btn btn-primary">AE03</a></td>
                        <td><a href="/atividadeExtra/4/3" class="btn btn-primary">AE04</a></td>
                        <td><a href="/atividadeExtra/5/3" class="btn btn-primary">AE05</a></td>
                        <td><a href="/atividadeExtra/6/3" class="btn btn-primary">AE06</a></td>
                        <td><a href="/atividadeExtra/7/3" class="btn btn-primary">AE07</a></td>
                        <td><a href="/atividadeExtra/8/3" class="btn btn-primary">AE08</a></td>
                        <td><a href="/atividadeExtra/9/3" class="btn btn-primary">AE09</a></td>
                        <td><a href="/atividadeExtra/10/3" class="btn btn-primary">AE10</a></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4º</td>
                        <td><a href="/atividadeExtra/1/4" class="btn btn-primary">AE01</a></td>
                        <td><a href="/atividadeExtra/2/4" class="btn btn-primary">AE02</a></td>
                        <td><a href="/atividadeExtra/3/4" class="btn btn-primary">AE03</a></td>
                        <td><a href="/atividadeExtra/4/4" class="btn btn-primary">AE04</a></td>
                        <td><a href="/atividadeExtra/5/4" class="btn btn-primary">AE05</a></td>
                        <td><a href="/atividadeExtra/6/4" class="btn btn-primary">AE06</a></td>
                        <td><a href="/atividadeExtra/7/4" class="btn btn-primary">AE07</a></td>
                        <td><a href="/atividadeExtra/8/4" class="btn btn-primary">AE08</a></td>
                        <td><a href="/atividadeExtra/9/4" class="btn btn-primary">AE09</a></td>
                        <td><a href="/atividadeExtra/10/4" class="btn btn-primary">AE10</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    @if(session('mensagem'))
        <div class="alert alert-success" role="alert">
            <p>{{session('mensagem')}}</p>
        </div>
    @endif
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Gerar AEs
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="/atividadeExtra/gerar" method="POST">
            @csrf
            <div class="form-group">
                <label for="bimestre">Bimestre</label>
                <select id="bimestre" name="bimestre" required>
                    <option value="">Selecione</option>
                    <option value="1">1º</option>
                    <option value="2">2º</option>
                    <option value="3">3º</option>
                    <option value="4">4º</option>
                </select>
                <br/><br/>
                <label for="qtd">Quantidade de AEs</label>
                <input type="number" min="1" max="10" class="form-control" name="qtd" id="qtd" placeholder="Digite a quantidade de AEs do bimestre" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Gerar</button>
        </div>
        </form>
        </div>
    </div>
    </div>

    <a href="/admin" class="btn btn-success">Voltar</a>
@endsection