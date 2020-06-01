<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/estoque', function () {
    return view('admin.home_estoque');
})->middleware('auth:admin');
Route::get('/admin/administrativo', function () {
    return view('admin.home_administrativo');
})->middleware('auth:admin');
Route::get('/admin/pedagogico', function () {
    return view('admin.home_pedagogico');
})->middleware('auth:admin');
Route::get('/admin', 'AdminController@index')->name('admin.dashboard');
Route::get('/admin/novo', 'AdminController@create');
Route::post('/admin', 'AdminController@store');
Route::get('/admin/login', 'Auth\AdminLoginController@index')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
Route::get('/admin/atividade', 'AdminController@painelAtividades');
Route::post('/admin/atividade', 'AdminController@novaAtividade');
Route::get('/admin/atividade/download/{id}', 'AdminController@downloadAtividade');
Route::post('/admin/atividade/editar/{id}', 'AdminController@editarAtividade');
Route::get('/admin/atividade/apagar/{id}', 'AdminController@apagarAtividade');
Route::get('/admin/atividade/filtro', 'AdminController@filtro_atividade');
Route::get('/admin/listaAtividade', function () {
    return view('admin.home_las_admin');
})->middleware('auth:admin');
Route::get('/admin/listaAtividade/painel/{data}', 'AdminController@painel_lista_atividade');
Route::post('/admin/listaAtividade/anexar/{id}', 'AdminController@anexar');
Route::get('/admin/listaAtividade/download/{id}', 'AdminController@download');
Route::get('/admin/listaAtividade/apagar/{id}', 'AdminController@apagar');
Route::get('/admin/ocorrencias', 'AdminController@indexOcorrencias');
Route::get('/admin/ocorrencias/apagar/{id}', 'AdminController@apagarOcorrencia');
Route::get('/admin/ocorrencias/filtro', 'AdminController@filtroOcorrencias');
Route::get('/admin/conteudos/{a}', 'AdminController@index_conteudos');
Route::get('/admin/conteudos', 'AdminController@index_conteudos_ano');
Route::get('/admin/conteudos/painel/{a}/{b}/{t}', 'AdminController@painel_conteudo');
Route::post('/admin/conteudos/gerar', 'AdminController@gerar_conteudo');
Route::post('/admin/conteudos/anexar/{id}', 'AdminController@anexar_conteudo');
Route::get('/admin/conteudos/download/{id}', 'AdminController@download_conteudo');
Route::get('/admin/conteudos/apagar/{id}', 'AdminController@apagar_conteudo');
Route::get('/atividadeExtra', function () {
    return view('admin.home_aes');
})->middleware('auth:admin');
Route::get('/atividadeExtra/{a}/{b}/{t}', 'AdminController@painel_ae');
Route::post('/atividadeExtra/gerar', 'AdminController@gerar_ae');
Route::post('/atividadeExtra/anexar/{id}', 'AdminController@anexar_ae');
Route::get('/atividadeExtra/download/{id}', 'AdminController@download_ae');
Route::get('/atividadeExtra/apagar/{id}', 'AdminController@apagar_ae');

Route::get('/aluno/login', 'Auth\AlunoLoginController@index')->name('aluno.login');
Route::post('/aluno/login', 'Auth\AlunoLoginController@login')->name('aluno.login.submit');
Route::get('/aluno', 'AlunoController@index')->name('aluno.dashboard')->middleware('auth:aluno');
Route::get('/aluno/consulta', 'AlunoController@consulta')->middleware('auth:admin');
Route::post('/aluno', 'AlunoController@store')->middleware('auth:admin');
Route::get('/aluno/filtro', 'AlunoController@filtro')->middleware('auth:admin');
Route::post('/aluno/editar/{id}', 'AlunoController@update')->middleware('auth:admin');
Route::get('/aluno/apagar/{id}', 'AlunoController@destroy')->middleware('auth:admin');
Route::post('/aluno/file', 'AlunoController@file')->middleware('auth:admin');
Route::get('/aluno/disciplinas', 'AlunoController@disciplinas')->middleware('auth:aluno');
Route::get('/aluno/atividade/{d}', 'AlunoController@painelAtividades')->middleware('auth:aluno');
Route::get('/aluno/atividade/download/{id}', 'AlunoController@downloadAtividade')->middleware('auth:aluno');
Route::post('/aluno/atividade/retorno/{id}', 'AlunoController@retornoAtividade')->middleware('auth:aluno');
Route::post('/aluno/atividade/retorno/editar/{id}', 'AlunoController@editarRetornoAtividade')->middleware('auth:aluno');

Route::get('/prof/consulta', 'ProfController@consultaProf')->middleware('auth:admin');
Route::post('/prof', 'ProfController@novoProf')->middleware('auth:admin');
Route::get('/prof/filtro', 'ProfController@filtroProf')->middleware('auth:admin');
Route::post('/prof/editar/{id}', 'ProfController@editarProf')->middleware('auth:admin');
Route::get('/prof/apagar/{id}', 'ProfController@apagarProf')->middleware('auth:admin');
Route::get('/prof/apagarDisciplina/{p}/{d}', 'ProfController@apagarDisciplina')->middleware('auth:admin');
Route::get('/prof/login', 'Auth\ProfLoginController@index')->name('prof.login');
Route::post('/prof/login', 'Auth\ProfLoginController@login')->name('prof.login.submit');
Route::get('/prof', 'ProfController@index')->name('prof.dashboard')->middleware('auth:prof');
Route::get('/prof/disciplinasAtividades', 'ProfController@disciplinasAtividades')->middleware('auth:prof');
Route::get('/prof/atividade/{id}', 'ProfController@painelAtividades')->middleware('auth:prof');
Route::post('/prof/atividade', 'ProfController@novaAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/download/{id}', 'ProfController@downloadAtividade')->middleware('auth:prof');
Route::post('/prof/atividade/editar/{id}', 'ProfController@editarAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/apagar/{id}', 'ProfController@apagarAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/filtro/{id}', 'ProfController@filtroAtividade')->middleware('auth:prof');
Route::get('/prof/atividade/retornos/{id}', 'ProfController@retornos')->middleware('auth:prof');
Route::get('/prof/atividade/retorno/download/{id}', 'ProfController@downloadRetorno')->middleware('auth:prof');
Route::get('/prof/listaAtividade', function () {
    return view('profs.home_las');
})->middleware('auth:prof');
Route::get('/prof/listaAtividade/painel/{data}', 'ProfController@painelListaAtividades')->middleware('auth:prof');
Route::post('/prof/listaAtividade/anexar/{id}', 'ProfController@anexarListaAtividade')->middleware('auth:prof');
Route::get('/prof/listaAtividade/download/{id}', 'ProfController@downloadListaAtividade')->middleware('auth:prof');
Route::get('/prof/listaAtividade/apagar/{id}', 'ProfController@apagarListaAtividade')->middleware('auth:prof');
Route::get('/prof/ocorrencias', 'ProfController@disciplinasOcorrencias')->middleware('auth:prof');
Route::get('/prof/ocorrencias/{id}', 'ProfController@turmasOcorrencias')->middleware('auth:prof');
Route::get('/prof/ocorrencias/{disc}/{turma}', 'ProfController@indexOcorrencias')->middleware('auth:prof');
Route::post('/prof/ocorrencias', 'ProfController@novasOcorrencias')->middleware('auth:prof');
Route::post('/prof/ocorrencias/editar/{id}', 'ProfController@editarOcorrencia')->middleware('auth:prof');
Route::get('/prof/ocorrencias/apagar/{disc}/{turma}/{id}', 'ProfController@apagarOcorrencia')->middleware('auth:prof');
Route::get('/prof/ocorrencias/filtro/{disc}/{turma}', 'ProfController@filtroOcorrencias')->middleware('auth:prof');
Route::get('/prof/conteudos', 'ProfController@painelConteudosAno')->middleware('auth:prof');
Route::get('/prof/conteudos/{a}', 'ProfController@painelConteudos')->middleware('auth:prof');
Route::get('/prof/conteudos/painel/{a}/{b}/{t}', 'ProfController@conteudos')->middleware('auth:prof');
Route::post('/prof/conteudos/anexar/{id}', 'ProfController@anexarConteudo')->middleware('auth:prof');
Route::get('/prof/conteudos/download/{id}', 'ProfController@downloadConteudo')->middleware('auth:prof');
Route::get('/prof/conteudos/apagar/{id}', 'ProfController@apagarConteudo')->middleware('auth:prof');

Route::get('/outro/login', 'Auth\OutroLoginController@index')->name('outro.login');
Route::post('/outro/login', 'Auth\OutroLoginController@login')->name('outro.login.submit');
Route::get('/outro', 'OutroController@index')->name('outro.dashboard')->middleware('auth:outro');
Route::get('/outro/novo', 'OutroController@create')->middleware('auth:outro');
Route::post('/outro', 'OutroController@store')->middleware('auth:admin');
Route::get('/outro/consulta', 'OutroController@consulta')->middleware('auth:admin');
Route::get('/outro/filtro', 'OutroController@filtro')->middleware('auth:admin');
Route::post('/outro/editar/{id}', 'OutroController@update')->middleware('auth:admin');
Route::get('/outro/apagar/{id}', 'OutroController@destroy')->middleware('auth:admin');
Route::post('/outro/file', 'OutroController@file')->middleware('auth:admin');

Route::get('/produtos', 'ProdutoController@index');
Route::post('/produtos', 'ProdutoController@store');
Route::post('/produtos/editar/{id}', 'ProdutoController@update');
Route::get('/produtos/apagar/{id}', 'ProdutoController@destroy');
Route::get('/produtos/filtro', 'ProdutoController@filtro');
Route::get('/produtos/pdf', 'ProdutoController@gerarPdf');

Route::get('/categorias', 'CategoriaController@index');
Route::post('/categorias', 'CategoriaController@store');
Route::post('/categorias/editar/{id}', 'CategoriaController@update');
Route::get('/categorias/apagar/{id}', 'CategoriaController@destroy');


Route::get('/entradaSaida', 'EntradaSaidaController@index');
Route::post('/entradaSaida', 'EntradaSaidaController@store');
Route::get('/entradaSaida/filtro_entradaSaida', 'EntradaSaidaController@filtro_entradaSaidaRel');
Route::get('/entradaSaida/pdf', 'EntradaSaidaController@gerarPdf');

Route::get('/listaCompras', 'ListaCompraController@index');
Route::post('/listaCompras', 'ListaCompraController@store');
Route::get('/listaCompras/pdf/{id}', 'ListaCompraController@gerarPdf');

Route::get('/disciplinas', 'DisciplinaController@index');
Route::post('/disciplinas', 'DisciplinaController@store');
Route::get('/disciplinas/apagar/{id}', 'DisciplinaController@destroy');

Route::get('/turmas', 'TurmaController@index');
Route::post('/turmas', 'TurmaController@store');
Route::get('/turmas/apagar/{id}', 'TurmaController@destroy');

Route::get('/turmasDiscs', 'TurmaDisciplinaController@index');
Route::post('/turmasDiscs', 'TurmaDisciplinaController@store');
Route::get('/turmasDiscs/apagar/{t}/{d}', 'TurmaDisciplinaController@destroy');

Route::get('/tiposOcorrencias', 'AdminController@tipoOcorrencia');
Route::post('/tiposOcorrencias', 'AdminController@tipoOcorrenciaNovo');
Route::post('/tiposOcorrencias/editar/{id}', 'AdminController@tipoOcorrenciaEdit');
Route::get('/tiposOcorrencias/apagar/{id}', 'AdminController@tipoOcorrenciaDelete');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
