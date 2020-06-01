<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Liceu</title>
    <link rel="shortcut icon" href="/storage/favicon.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body{
            padding: 20px;
            background-image: url("/storage/liceu.svg");
            background-color: #cccccc; 
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
        .container{
            margin-top: 20px;
        }
        table tbody tr td{
			text-align: center;
        }
        table thead tr th{
			text-align: center;
		}
        #navLogin {
            color:red;
        }
        .float-button{
			position: fixed;
			bottom: 40px;
			right: 40px;
		}
		.material-icons.blue { 
			color:#0000CD;
		}

		.material-icons.white { 
			color: white;
		}

		.material-icons.md-60 { 
			font-size: 60px; 
		}

		.material-icons.md-18 { font-size: 14px; }                              
    </style>
</head>
<body>
    <div class="container">
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
</body>
</html>
