<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Intranet - Colégio Liceu II</title>
    <link rel="shortcut icon" href="/storage/favicon.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#00008B">
	<meta name="apple-mobile-web-app-status-bar-style" content="#00008B">
	<meta name="msapplication-navbutton-color" content="#00008B">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container-xl">
            <header>
                @component('components.componente_navbar', ["current"=>$current ?? ''])
                @endcomponent
            </header>
            <main>
                @hasSection ('body')
                    @yield('body')   
                @endif
            </main>
            @component('components.componente_footer')
            @endcomponent
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function id(campo){
            return document.getElementById(campo);
        }

        function validarSenhaForca(){
            var senha = document.getElementById('senhaForca').value;
            var forca = 0;
        
            if((senha.length >= 4) && (senha.length <= 8)){
                forca += 10;
            }else if(senha.length > 8){
                forca += 25;
            }
        
            if((senha.length >= 5) && (senha.match(/[a-z]+/))){
                forca += 10;
            }
        
            if((senha.length >= 6) && (senha.match(/[A-Z]+/))){
                forca += 20;
            }
        
            if((senha.length >= 7) && (senha.match(/[@#$%&;*]/))){
                forca += 25;
            }
        
            if(senha.match(/([1-9]+)\1{1,}/)){
                forca += -25;
            }
        
            mostrarForca(forca);
        }
        
        function mostrarForca(forca){
            if(forca < 30 ){
                document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 30) && (forca < 50)){
                document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 50) && (forca < 70)){
                document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }else if((forca >= 70) && (forca < 100)){
                document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>';
            }
        }
        
        function processar(){
            $('#form-importar-excel').submit();
            $('#form-gerar-conteudo').submit();
            document.getElementById("processamento").innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Processando...</button>';
        }

        $(document).ready(function(){
            //OPÇÕES DE LOGIN
            $('#principal').children('div').hide();
            $('#tipoLogin').on('change', function(){
                
                var selectValor = '#'+$(this).val();
                $('#principal').children('div').hide();
                $('#principal').children(selectValor).show();

            });

            //OPÇÕES DE RECADOS
            $('#principalSelect').children('div').hide();
            $('#selectGeral').on('change', function(){
                
                var selectValorGeral = '#'+$(this).val();
                $('#principalSelect').children('div').hide();
                $('#principalSelect').children(selectValorGeral).show();

            });

        });

        function formataNumeroTelefone() {
            var numero = document.getElementById('telefone').value;
            var length = numero.length;
            var telefoneFormatado;
            
            if (length == 10) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
            } else if (length == 11) {
            telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
            } else {
                telefoneFormatado = 'Número Inválido, digite número com DDD';
            }
            id('telefone').value = telefoneFormatado;
        }

        function mostrarSenha(){
            var tipo = document.getElementById("senha");
            if(tipo.type=="password"){
                tipo.type = "text";
                id('icone-senha').innerHTML = "visibility_off";
                id('botao-senha').className = "btn btn-warning btn-sm";
                id('botao-senha').title = "Ocultar Senha";
            } else {
                tipo.type = "password";
                id('icone-senha').innerHTML = "visibility";
                id('botao-senha').className = "btn btn-success btn-sm";
                id('botao-senha').title = "Exibir Senha";
            }
        }

        function mostrarSenhaProf(){
            var tipo = document.getElementById("senha-prof");
            if(tipo.type=="password"){
                tipo.type = "text";
                id('icone-senha-prof').innerHTML = "visibility_off";
                id('botao-senha-prof').className = "btn btn-warning btn-sm";
                id('botao-senha-prof').title = "Ocultar Senha";
            } else {
                tipo.type = "password";
                id('icone-senha-prof').innerHTML = "visibility";
                id('botao-senha-prof').className = "btn btn-success btn-sm";
                id('botao-senha-prof').title = "Exibir Senha";
            }
        }

        function mostrarSenhaAdmin(){
            var tipo = document.getElementById("senha-admin");
            if(tipo.type=="password"){
                tipo.type = "text";
                id('icone-senha-admin').innerHTML = "visibility_off";
                id('botao-senha-admin').className = "btn btn-warning btn-sm";
                id('botao-senha-admin').title = "Ocultar Senha";
            } else {
                tipo.type = "password";
                id('icone-senha-admin').innerHTML = "visibility";
                id('botao-senha-admin').className = "btn btn-success btn-sm";
                id('botao-senha-admin').title = "Exibir Senha";
            }
        }
    </script>
</body>
</html>
