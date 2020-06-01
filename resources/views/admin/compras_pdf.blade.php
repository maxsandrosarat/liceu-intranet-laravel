<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
    <div class="card border">
        <div class="card-body">
            <h3 class="card-title">Lista de Compras</h3>
            <table class="table table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center;">Produto</th>
                        <th style="text-align: center;">Estoque</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rels as $rel)
                    <tr>
                        <td>{{$rel->produto->nome}}</td>
                        <td style="text-align: center;">{{$rel->estoque}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>