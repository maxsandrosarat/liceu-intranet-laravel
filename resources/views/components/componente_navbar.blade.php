<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <img src="/storage/liceu.png" alt="logo_liceu" width="10%">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto">
            <!--ADMIN-->
            @auth("admin")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/admin">Home</a>
            </li>
            <li @if($current=="estoque") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/admin/estoque">Estoque</a>
            </li>
            <li @if($current=="administrativo") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/admin/administrativo">Administrativo</a>
            </li>
            <li @if($current=="pedagogico") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/admin/pedagogico">Pedagógico</a>
            </li>
            @endauth

            <!--ALUNO-->
            @auth("aluno")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/aluno">Home</a>
            </li>
            <li @if($current=="atividade") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/aluno/disciplinas">Atividades</a>
            </li>
            @endauth

            <!--PROF-->
            @auth("prof")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/prof">Home</a>
            </li>
            <li @if($current=="la") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/prof/listaAtividade">Listas Atividades</a>
            </li>
            <li @if($current=="ocorrencias") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/prof/ocorrencias">Ocorrências</a>
            </li>
            <li @if($current=="conteudos") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/prof/conteudos/{{date("Y")}}">Conteúdos</a>
            </li>
            <li @if($current=="atividade") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/prof/disciplinasAtividades">Atividades</a>
            </li>
            @endauth

            <!--OUTRO-->
            @auth("outro")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/outro">Home</a>
            </li>
            @endauth

            <!--WEB
            @auth("web")
            <li @if($current=="home") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/home">Home</a>
            </li>
            <li @if($current=="la") class="nav-item active" @else class="nav-item" @endif>
                <a class="nav-link" href="/listaAtividade">Lista Atividades</a>
            </li>
            @endauth-->

            <!--DESLOGADO-->
            @guest
            <!--<li class="nav-item">
                //<a class="nav-link" href="{{ route('login') }}">{{ __('Login(Usuário)') }}</a>
            </li>-->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('aluno.login') }}">{{ __('Login(Aluno)') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('prof.login') }}">{{ __('Login(Prof)') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('outro.login') }}">{{ __('Login(Outro)') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.login') }}">{{ __('Login(Admin)') }}</a>
            </li>
            <!--@if (Route::has('register'))
            <li class="nav-item">
               <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastre-se') }}</a>
           </li>
            @endif-->

            <!--LOGADO-->
            @else
            <!--LOGOUT-->
            <li class="nav-item dropdown" class="nav-item">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown" class="nav-item">
                @if(Auth::user()->foto!="")
                <img style="border-radius: 20px;" src="/storage/{{Auth::user()->foto}}" alt="foto_perfil" width="10%">
                @endif
            </li>
            @endguest
        </ul>
    </div>
  </nav>