<html>
    <body>
        <h4>Seja bem-vindo(a) {{$nome}}</h4>
        <p>Logou com seu email: {{$email}}</p>
        <p>Data e Hora de Acesso: {{$dataHora}}</p>
        <div>
            <img width="10%" height="10%" src="{{$message->embed(public_path().'/storage/liceu.png')}}" alt="Logo Laravel">
        </div>
    </body>
</html>