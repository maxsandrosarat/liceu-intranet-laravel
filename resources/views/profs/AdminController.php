<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Anuncio;
use App\Banner;
use App\Categoria;
use App\CompraLivro;
use App\CupomDesconto;
use App\EntradaSaida;
use App\Entrega;
use App\FormaPagamento;
use App\Foto;
use App\Outro;
use App\Pedido;
use App\PedidoProduto;
use App\Post;
use App\Produto;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        return view('admin.home_admin');
    }

    public function cadastroAdmin()
    {
        return view('auth.admin-register');
    }

    public function novoAdmin(Request $request)
    {
        $adm = new Admin();
        $adm->name = $request->input('name');
        $adm->email = $request->input('email');
        $adm->password = Hash::make($request->input('password'));
        $adm->save();
        return back()->with('mensagem', 'Novo Administrador(a) cadastrado com Sucesso!');
    }

    //OUTROS(COLABORADOR)
    public function indexOutros()
    {
        $outros = Outro::orderBy('name')->paginate(10);
        $view = "inicial";
        return view('admin.outros', compact('view','outros'));
    }
    
    public function novoOutro(Request $request)
    {
        $outro = new Outro();
        $outro->name = $request->input('name');
        $outro->email = $request->input('email');
        $outro->password = Hash::make($request->input('password'));
        $outro->save();
        return back();
    }
    
    public function filtroOutros(Request $request)
    {
        $nome = $request->input('nome');
        if(isset($nome)){
            $outros = Outro::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(10);
        } else {
            return back();
        }
        $view = "filtro";
        return view('outros.outros', compact('view','outros'));
    }
    
    public function editarOutro(Request $request, $id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->name =$request->input('name');
            $outro->email =$request->input('email');
            if($request->input('password')!=""){
            $outro->password = Hash::make($request->input('password'));
            }
            $outro->save();
        }
        return back();
    }
    
    public function apagarOutro($id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->ativo = false;
            $outro->save();
        }
        return back();
    }
    
    //BANNER
    public function indexBanners()
    {
        $banners = Banner::all();
        return view('admin.banners',compact('banners'));
    }

    public function novoBanner(Request $request)
    {
        $banner = new Banner();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos','public');
            $banner->foto = $path;
        }
        if($request->input('titulo')!=""){
            $banner->titulo = $request->input('titulo');
        }
        if($request->input('descricao')!=""){
            $banner->descricao = $request->input('descricao');
        }
        if($request->input('ordem')!=""){
            $banner->ordem = $request->input('ordem');
        }
        if($request->input('ativo')!=""){
            $banner->ativo = $request->input('ativo');
        }
        $banner->save();
        return back();
    }

    public function editarBanner(Request $request, $id)
    {
        $banner = Banner::find($id);
        if(isset($banner)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($banner->foto);
                $path = $request->file('foto')->store('fotos','public');
                $banner->foto = $path;
            }
            if($request->input('titulo')!=""){
                $banner->titulo = $request->input('titulo');
            }
            if($request->input('descricao')!=""){
                $banner->descricao = $request->input('descricao');
            }
            if($request->input('ordem')!=""){
                $banner->ordem = $request->input('ordem');
            }
            if($request->input('ativo')!=""){
                $banner->ativo = $request->input('ativo');
            }
            $banner->save();
        }
        return back();
    }

    public function apagarBanner($id)
    {
        $banner = Banner::find($id);
        if(isset($banner)){
            Storage::disk('public')->delete($banner->foto);
            $banner->delete();
        }
        return back();
    }

    //POST
    public function indexPosts()
    {
        $posts = Post::all();
        return view('admin.posts',compact('posts'));
    }

    public function novoPost(Request $request)
    {
        $post = new Post();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos','public');
            $post->foto = $path;
        }
        if($request->input('data')!=""){
            $post->data = $request->input('data');
        }
        if($request->input('titulo')!=""){
            $post->titulo = $request->input('titulo');
        }
        if($request->input('descricao')!=""){
            $post->descricao = $request->input('descricao');
        }
        if($request->input('ativo')!=""){
            $post->ativo = $request->input('ativo');
        }
        $post->save();
        return back();
    }

    public function editarPost(Request $request, $id)
    {
        $post = Post::find($id);
        if(isset($post)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($post->foto);
                $path = $request->file('foto')->store('fotos','public');
                $post->foto = $path;
            }
            if($request->input('data')!=""){
                $post->data = $request->input('data');
            }
            if($request->input('titulo')!=""){
                $post->titulo = $request->input('titulo');
            }
            if($request->input('descricao')!=""){
                $post->descricao = $request->input('descricao');
            }
            if($request->input('ativo')!=""){
                $post->ativo = $request->input('ativo');
            }
            $post->save();
        }
        return back();
    }

    public function apagarPost($id)
    {
        $post = Post::find($id);
        if(isset($post)){
            Storage::disk('public')->delete($post->foto);
            $post->delete();
        }
        return back();
    }

    //FOTOS
    public function indexFotos()
    {
        $fotos = Foto::all();
        return view('admin.fotos',compact('fotos'));
    }

    public function novaFoto(Request $request)
    {
        $foto = new Foto();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos','public');
            $foto->foto = $path;
        }
        if($request->input('titulo')!=""){
            $foto->titulo = $request->input('titulo');
        }
        if($request->input('ativo')!=""){
            $foto->ativo = $request->input('ativo');
        }
        $foto->save();
        return back();
    }

    public function editarFoto(Request $request, $id)
    {
        $foto = Foto::find($id);
        if(isset($foto)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($foto->foto);
                $path = $request->file('foto')->store('fotos','public');
                $foto->foto = $path;
            }
            if($request->input('titulo')!=""){
                $foto->titulo = $request->input('titulo');
            }
            if($request->input('ativo')!=""){
                $foto->ativo = $request->input('ativo');
            }
            $foto->save();
        }
        return back();
    }

    public function apagarFoto($id)
    {
        $foto = Foto::find($id);
        if(isset($foto)){
            Storage::disk('public')->delete($foto->foto);
            $foto->delete();
        }
        return back();
    }

    public function indexClientes()
    {
        $clientes = User::paginate(20);
        return view('admin.clientes',compact('clientes'));
    }

    public function filtroCliente(Request $request)
    {
        $nome = $request->input('nome');
        $email = $request->input('email');
        if(isset($nome)){
            if(isset($email)){
                $clientes = User::where('name','like',"%$nome%")->where('email','like',"%$email%")->paginate(100);
            } else {
                $clientes = User::where('name','like',"%$nome%")->paginate(100);
            }
        } else {
            if(isset($email)){
                $clientes = User::where('email','like',"%$email%")->paginate(100);
            } else {
                return redirect('/admin/clientes');
            }
        }
        
        return view('admin.clientes',compact('clientes'));
    }

    public function cadastros(){
        return view('admin.home_cadastros');
    }

    public function indexCategorias()
    {
        $cats = Categoria::all();
        return view('admin.categorias',compact('cats'));
    }

    public function novaCategoria(Request $request)
    {
        $cat = new Categoria();
        $cat->nome = $request->input('nomeCategoria');
        $cat->save();
        return back();
    }

    public function editarCategoria(Request $request, $id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->nome = $request->input('nomeCategoria');
            $cat->ativo = $request->input('ativo');
            $cat->save();
        }
        return back();
    }

    public function inativarCategoria($id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->ativo = false;
            $cat->save();
        }
        return back();
    }

    public function indexCupons()
    {
        $cupons = CupomDesconto::all();
        return view('admin.cupons',compact('cupons'));
    }

    public function novoCupom(Request $request)
    {
        $cupom = new CupomDesconto();
        $cupom->nome = $request->input('nome');
        $cupom->localizador = $request->input('localizador');
        $cupom->desconto = $request->input('desconto');
        $cupom->modo_desconto = $request->input('modo_desconto');
        $cupom->limite = $request->input('limite');
        $cupom->modo_limite = $request->input('modo_limite');
        $cupom->validade = $request->input('dataValidade').' '.$request->input('horaValidade');
        $cupom->ativo = $request->input('ativo');
        $cupom->save();
        return back();
    }

    public function editarCupom(Request $request, $id)
    {
        $cupom = CupomDesconto::find($id);
        if($request->input('nome')!=""){
            $cupom->nome = $request->input('nome');
        }
        if($request->input('localizador')!=""){
            $cupom->localizador = $request->input('localizador');
        }
        if($request->input('desconto')!=""){
            $cupom->desconto = $request->input('desconto');
        }
        if($request->input('modo_desconto')!=""){
            $cupom->modo_desconto = $request->input('modo_desconto');
        }
        if($request->input('limite')!=""){
            $cupom->limite = $request->input('limite');
        }
        if($request->input('modo_limite')!="")
        {
            $cupom->modo_limite = $request->input('modo_limite');
        }
        if($request->input('dataValidade')!=""){
            $cupom->validade = $request->input('dataValidade').' '.$request->input('horaValidade');
        }
        if($request->input('ativo')!=""){
            $cupom->ativo = $request->input('ativo');
        }
        $cupom->save();
        return back();
    }

    public function inativarCupom($id)
    {
        $cupom = CupomDesconto::find($id);
        if(isset($ancupomuncio)){
            $cupom->ativo = false;
            $cupom->save();
        }
        return back();
    }

    public function indexEntregas()
    {
        $formas = Entrega::all();
        return view('admin.entregas',compact('formas'));
    }

    public function novaEntrega(Request $request)
    {
        $forma = new Entrega();
        $forma->descricao = $request->input('descricao');
        $forma->valor = $request->input('valor');
        $forma->save();
        return back();
    }

    public function editarEntrega(Request $request, $id)
    {
        $forma = Entrega::find($id);
        if(isset($forma)){
            $forma->descricao = $request->input('descricao');
            $forma->valor = $request->input('valor');
            $forma->ativo = $request->input('ativo');
            $forma->save();
        }
        return back();
    }

    public function inativarEntrega($id)
    {
        $forma = Entrega::find($id);
        if(isset($forma)){
            $forma->ativo = false;
            $forma->save();
        }
        return back();
    }

    public function indexFormas()
    {
        $formas = FormaPagamento::all();
        return view('admin.formas_pagamento',compact('formas'));
    }

    public function novaForma(Request $request)
    {
        $forma = new FormaPagamento();
        $forma->descricao = $request->input('descricao');
        $forma->save();
        return back();
    }

    public function editarForma(Request $request, $id)
    {
        $forma = FormaPagamento::find($id);
        if(isset($forma)){
            $forma->descricao = $request->input('descricao');
            $forma->ativo = $request->input('ativo');
            $forma->save();
        }
        return back();
    }

    public function inativarForma($id)
    {
        $forma = FormaPagamento::find($id);
        if(isset($forma)){
            $forma->ativo = false;
            $forma->save();
        }
        return back();
    }

    public function indexProdutos()
    {
        $prods = Produto::paginate(20);
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('admin.produtos',compact('prods','cats'));
    }

    public function novoProduto(Request $request)
    {
        $prod = new Produto();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_produtos','public');
            $prod->foto = $path;
        }
        if($request->input('nome')!=""){
            $prod->nome = $request->input('nome');
        }
        if($request->input('turma')!=""){
        $prod->turma = $request->input('turma');
        }
        if($request->input('ensino')!=""){
        $prod->ensino = $request->input('ensino');
        }
        if($request->input('marca')!=""){
        $prod->marca = $request->input('marca');
        }
        if($request->input('embalagem')!=""){
        $prod->embalagem = $request->input('embalagem');
        }
        if($request->input('preco')!=""){
        $prod->preco = $request->input('preco');
        }
        if($request->input('estoque')!=""){
        $prod->estoque = $request->input('estoque');
        }
        if($request->input('categoria')!=""){
            $prod->categoria_id = $request->input('categoria');
        }
        if($request->input('descricao')!=""){
            $prod->descricao = $request->input('descricao');
        }
        if($request->input('ativo')!=""){
            $prod->ativo = $request->input('ativo');
        }
        if($request->input('promocao')!=""){
            $prod->promocao = $request->input('promocao');
        }
        $prod->save();
        return back();
    }

    public function editarProduto(Request $request, $id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($prod->foto);
                $path = $request->file('foto')->store('fotos_produtos','public');
                $prod->foto = $path;
            }
            if($request->input('nome')!=""){
                $prod->nome = $request->input('nome');
            }
            if($request->input('turma')!=""){
            $prod->turma = $request->input('turma');
            }
            if($request->input('ensino')!=""){
            $prod->ensino = $request->input('ensino');
            }
            if($request->input('marca')!=""){
            $prod->marca = $request->input('marca');
            }
            if($request->input('embalagem')!=""){
            $prod->embalagem = $request->input('embalagem');
            }
            if($request->input('preco')!=""){
            $prod->preco = $request->input('preco');
            }
            if($request->input('estoque')!=""){
            $prod->estoque = $request->input('estoque');
            }
            if($request->input('categoria')!=""){
                $prod->categoria_id = $request->input('categoria');
            }
            if($request->input('descricao')!=""){
                $prod->descricao = $request->input('descricao');
            }
            if($request->input('ativo')!=""){
                $prod->ativo = $request->input('ativo');
            }
            if($request->input('promocao')!=""){
                $prod->promocao = $request->input('promocao');
            }
            $prod->save();
        }
        return back();
    }

    public function inativarProduto($id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $prod->ativo = false;
            $prod->save();
        }
        return back();
    }

    public function filtroProduto(Request $request)
    {
        $nome = $request->input('nome');
        $cat = $request->input('categoria');
        $tipo = $request->input('tipo');
        $fase = $request->input('fase');
        $marca = $request->input('marca');
        if(isset($nome)){
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            return redirect('/admin/produtos');
                        }
                    }
                }
            }
        }
        
        $cats = Categoria::where('ativo',true)->orderBy('nome')->get();
        return view('cadastros.produtos',compact('prods','cats'));
    }

    public function indexAnuncios()
    {
        $anuncios = Anuncio::all();
        return view('admin.anuncios',compact('anuncios'));
    }

    public function novoAnuncio(Request $request)
    {
        $anuncio = new Anuncio();
        if($request->file('foto')!=""){
            $path = $request->file('foto')->store('fotos_anuncios','public');
            $anuncio->foto = $path;
        }
        $anuncio->nome = $request->input('nome');
        $anuncio->ativo = $request->input('ativo');
        $anuncio->link = $request->input('link');
        $anuncio->save();
        return back();
    }

    public function editarAnuncio(Request $request, $id)
    {
        $anuncio = Anuncio::find($id);
        if($request->file('foto')!=""){
            Storage::disk('public')->delete($anuncio->foto);
            $path = $request->file('foto')->store('fotos_anuncios','public');
            $anuncio->foto = $path;
        }
        if($request->input('nome')!=""){
            $anuncio->nome = $request->input('nome');
        }
        if($request->input('ativo')!=""){
            $anuncio->ativo = $request->input('ativo');
        }
        if($request->input('link')!=""){
            $anuncio->link = $request->input('link');
        }
        $anuncio->save();
        return back();
    }

    public function apagarAnuncio($id)
    {
        $anuncio = Anuncio::find($id);
        if(isset($anuncio)){
            Storage::disk('public')->delete($anuncio->foto);
            $anuncio->delete();
        }
        return back();
    }

    public function pedidos(){
        return view('admin.home_pedidos');
    }

    public function pedidos_feitos()
    {
        $pedidos = Pedido::where([
            'status'  => 'FEITO',
            ])->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pedidos_feitos', compact('pedidos'));
    }

    public function pedidos_pagos()
    {
        $pedidos = Pedido::where([
            'status'  => 'PAGO',
            ])->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pedidos_pagos', compact('pedidos'));
    }

    public function pedidos_cancelados()
    {
        $cancelados = Pedido::where([
            'status'  => 'CANCEL'
            ])->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.pedidos_cancelados', compact('cancelados'));
    }

    public function pedidos_reservados()
    {
        $rels = PedidoProduto::where('status','RESERV')->orderBy('created_at','desc')->orderBy('id','desc')->get();
        return view('admin.pedidos_reservados', compact('rels'));
    }

    public function liberar_produto_reservado($id)
    {
        $pedProd = PedidoProduto::find($id);
        $ped = Pedido::find($pedProd->pedido_id);
        $ped->update([
            'status' => 'CANCEL',
            'total' => 0
        ]);
        PedidoProduto::where('pedido_id',"$ped->id")->update([
            'status' => 'CANCEL',
            'qtdGranel' => 0,
            'valor' => 0,
            'desconto' => 0
        ]);
        $produtos = PedidoProduto::where('pedido_id',"$ped->id")->get();
        foreach ($produtos as $prods) {
            $prod = Produto::find($prods->produto_id);
                if(isset($prod)){
                    if($prod->granel==1){

                    } else {
                        $user = Auth::user();
                        $es = new EntradaSaida();
                        $es->tipo = "entrada";
                        $es->produto_id = $prods->produto_id;
                        $es->quantidade = 1;
                        $es->usuario = $user->name;
                        $es->motivo = "Liberação Reservado";
                        $es->save();
                        $prod->estoque += 1;
                        $prod->save();
                    }
                    $cliente = User::find($ped->user_id);
                    $cliente->carrinho -= 1;
                    $cliente->save();
                }
        }
        return back();
    }

    public function pagar_pedido($id){
        $pedido = Pedido::find($id);
        $pedido->status = 'PAGO';
        $pedido->update();
        $pedido_produtos = PedidoProduto::where('pedido_id',"$id")->get();
        foreach($pedido_produtos as $produtos){
            if($produtos->status == 'CANCEL'){

            } else {
                $produtos->status = 'PAGO';
                $produtos->update();
            }
        }
        return back()->with('mensagem-sucesso', 'Pedido pago com sucesso!');
    }

    public function cancelar_pedido($id){
        $pedido = Pedido::find($id);
        $pedido->status = 'CANCEL';
        $pedido->update();
        $pedido_produtos = PedidoProduto::where('pedido_id',"$id")->get();
        foreach($pedido_produtos as $produtos){
            if($produtos->status == 'PAGO' || $produtos->status == 'FEITO'){
                $produto = Produto::find($produtos->produto_id);
                if($produto->granel==1){

                } else {
                    $user = Auth::user();
                    $es = new EntradaSaida();
                    $es->tipo = "entrada";
                    $es->produto_id = $produtos->produto_id;
                    $es->quantidade = 1;
                    $es->usuario = $user->name;
                    $es->motivo = "Cancelamento Pedido";
                    $es->save();
                    $produto->estoque += 1;
                    $produto->save();
                }
            }
            $produtos->status = 'CANCEL';
            $produtos->update();
        }
        return back()->with('mensagem-sucesso', 'Pedido cancelado com sucesso!');
    }

    public function indexEstoque()
    {
        $prods = Produto::paginate(20);
        $cats = Categoria::all();
        return view('admin.estoque_produtos',compact('prods','cats'));
    }

    public function entrada(Request $request, $id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            if($request->input('qtd')!=""){
                $user = Auth::user();
                $tipo = "entrada";
                $id = $request->input('produto');
                $qtd = $request->input('qtd');
                $es = new EntradaSaida();
                $es->tipo = $tipo;
                $es->produto_id = $id;
                $es->quantidade = $qtd;
                $es->usuario = $user->name;
                $es->motivo = $request->input('motivo');
                $es->save();
                $prod->estoque += $request->input('qtd');
                $prod->save();
            }
        }
        return back();
    }

    public function saida(Request $request, $id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            if($request->input('qtd')!=""){
                $user = Auth::user();
                $tipo = "saida";
                $id = $request->input('produto');
                $qtd = $request->input('qtd');
                $es = new EntradaSaida();
                $es->tipo = $tipo;
                $es->produto_id = $id;
                $es->quantidade = $qtd;
                $es->usuario = $user->name;
                $es->motivo = $request->input('motivo');
                $es->save();
                $prod->estoque -= $request->input('qtd');
                $prod->save();
            }
        }
        return back();
    }

    public function filtroEstoque(Request $request)
    {
        $nome = $request->input('nome');
        $cat = $request->input('categoria');
        if(isset($nome)){
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('nome','like',"%$nome%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                $prods = Produto::where('nome','like',"%$nome%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('categoria_id',"$cat")->where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
                }
            } else {
                if(isset($tipo)){
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_animal_id',"$tipo")->where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        $prods = Produto::where('tipo_animal_id',"$tipo")->orderBy('nome')->paginate(10);
                    }
                } else {
                    if(isset($fase)){
                        if(isset($marca)){
                            $prods = Produto::where('tipo_fase',"$fase")->where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            $prods = Produto::where('tipo_fase',"$fase")->orderBy('nome')->paginate(10); 
                        }
                    } else {
                        if(isset($marca)){
                            $prods = Produto::where('marca_id',"$marca")->orderBy('nome')->paginate(10);
                        } else {
                            return redirect('/admin/estoque');
                        }
                    }
                }
            }
        }
        
        $cats = Categoria::all();
        return view('admin.estoque_produtos',compact('prods','cats'));
    }

    public function indexCompraLivros()
    {
        $recibos = CompraLivro::paginate(10);
        $view = "inicial";
        $series = DB::table('compra_livros')->select(DB::raw("serie"))->groupBy('serie')->get();
        $turmas = DB::table('compra_livros')->select(DB::raw("turma"))->groupBy('turma')->get();
        return view('admin.compra_livros',compact('view','series','turmas','recibos'));
    }

    public function novaCompraLivro(Request $request)
    {
        $recibo = new CompraLivro();
        $recibo->nomeAluno = $request->input('nomeAluno');
        $recibo->serie = $request->input('serie');
        $recibo->turma = $request->input('turma');
        $recibo->ensino = $request->input('ensino');
        $recibo->nomeResp = $request->input('nomeResp');
        $recibo->cpf = $request->input('cpf');
        $recibo->valor = $request->input('valor');
        $recibo->formaPagamento = $request->input('formaPagamento');
        $recibo->user = Auth::user()->name;
        $recibo->save();
        return back();
    }

    public function gerarRecibo($id)
    {
        $recibo = CompraLivro::find($id);
        $pdf = PDF::loadView('admin.recibo_compralivro_pdf', compact('recibo'));
        return $pdf->setPaper('a4')->stream('Recibo '.$recibo->nomeAluno.'.pdf');
    }

    public function filtroCompraLivro(Request $request)
    {
        $nome = $request->input('nome');
        $serie = $request->input('serie');
        $turma = $request->input('turma');
        if(isset($nome)){
            if(isset($serie)){
                if(isset($turma)){
                    $recibos = CompraLivro::where('nomeAluno','like',"%$nome%")->where('serie',"$serie")->where('turma',"$turma")->orderBy('nomeAluno')->paginate(50);
                } else {
                    $recibos = CompraLivro::where('nomeAluno','like',"%$nome%")->where('serie',"$serie")->orderBy('nomeAluno')->paginate(50);
                }
            } else {
                if(isset($turma)){
                    $recibos = CompraLivro::where('nomeAluno','like',"%$nome%")->where('turma',"$turma")->orderBy('nomeAluno')->paginate(50);
                } else {
                    $recibos = CompraLivro::where('nomeAluno','like',"%$nome%")->orderBy('nomeAluno')->paginate(50);
                }
            }
        } else {
            if(isset($serie)){
                if(isset($turma)){
                    $recibos = CompraLivro::where('serie',"$serie")->where('turma',"$turma")->orderBy('nomeAluno')->paginate(50);
                } else {
                    $recibos = CompraLivro::where('serie',"$serie")->orderBy('nomeAluno')->paginate(50);
                }
            } else {
                if(isset($turma)){
                    $recibos = CompraLivro::where('turma',"$turma")->orderBy('nomeAluno')->paginate(50);
                } else {
                    return redirect('/admin/compraLivro');
                }
            }
        }
        $series = DB::table('compra_livros')->select(DB::raw("serie"))->groupBy('serie')->get();
        $turmas = DB::table('compra_livros')->select(DB::raw("turma"))->groupBy('turma')->get();
        $view = "filtro";
        return view('admin.compra_livros', compact('view','series','turmas','recibos'));
    }

    public function editarCompraLivro(Request $request, $id)
    {
        $recibo = CompraLivro::find($id);
        if($request->input('nomeAluno')!=""){
            $recibo->nomeAluno = $request->input('nomeAluno');
        }
        if($request->input('serie')!=""){
            $recibo->serie = $request->input('serie');
        }
        if($request->input('turma')!=""){
            $recibo->turma = $request->input('turma');
        }
        if($request->input('ensino')!=""){
            $recibo->ensino = $request->input('ensino');
        }
        if($request->input('nomeResp')!=""){
            $recibo->nomeResp = $request->input('nomeResp');
        }
        if($request->input('cpf')!=""){
            $recibo->cpf = $request->input('cpf');
        }
        if($request->input('valor')!=""){
            $recibo->valor = $request->input('valor');
        }
        if($request->input('formaPagamento')!=""){
            $recibo->formaPagamento = $request->input('formaPagamento');
        }
        $recibo->save();
        return back();
    }

    public function apagarCompraLivro($id)
    {
        $recibo = CompraLivro::find($id);
        if(isset($recibo)){
            $recibo->delete();
        }
        return back();
    }

    public function gerarPdfRelatorio(Request $request)
    {
        $serie = $request->input('serie');
        $turma = $request->input('turma');
        $compras = CompraLivro::where('serie',"$serie")->where('turma',"$turma")->orderBy('nomeAluno')->get();
        $pdf = PDF::loadView('admin.compras_pdf', compact('turma','serie','compras'));
        return $pdf->setPaper('a4')->stream('Relatória Compra Livro '.$serie.'º ANO '.$turma.'.pdf');
    }


    //ENTRADA & SAIDA
    public function indexRelatorios()
    {
        return view('relatorios.home_relatorios');
    }

    public function relatorioEstoque()
    {
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = EntradaSaida::orderBy('created_at','desc')->orderBy('id','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.entrada_saida_relatorio', compact('view','prods','rels'));
    }

    public function relatorioEstoqueFiltro(Request $request)
    {
        $tipo = $request->input('tipo');
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($tipo)){
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = EntradaSaida::orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        }
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.entrada_saida_relatorio', compact('view','prods','rels'));
    }

    public function indexVendas()
    {
        return view('relatorios.home_relatorios_vendas');
    }

    public function vendasProdutos()
    {
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = PedidoProduto::orderBy('created_at','desc')->orderBy('id','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.vendas_produtos_relatorio', compact('view','prods','rels'));
    }

    public function vendasProdutosFiltro(Request $request)
    {
        $status = $request->input('status');
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($codProd)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = PedidoProduto::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = PedidoProduto::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = PedidoProduto::orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        }
        $total_valor = $rels->sum('valor');
        $total_desconto = $rels->sum('desconto');
        $total_geral = $total_valor - $total_desconto;
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.vendas_produtos_relatorio', compact('view','prods','rels','total_valor','total_desconto','total_geral'));
    }

    public function vendasClientes()
    {
        $clientes = User::orderBy('name')->get();
        $rels = Pedido::orderBy('created_at','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.vendas_clientes_relatorio', compact('view','clientes','rels'));
    }

    public function vendasClientesFiltro(Request $request)
    {
        $status = $request->input('status');
        $codCliente = $request->input('cliente');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codCliente)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Pedido::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Pedido::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        } else {
            if(isset($codCliente)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Pedido::where('user_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('user_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Pedido::where('user_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('user_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = Pedido::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = Pedido::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    } else {
                        $rels = Pedido::orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                    }
                }
            }
        }
        $total_valor = $rels->sum('total');
        $clientes = User::orderBy('name')->get();
        $view = "filtro";
        return view('relatorios.vendas_clientes_relatorio', compact('view','clientes','rels','total_valor'));
    }

    public function vendasClientesProdutos()
    {
        $clientes = User::orderBy('name')->get();
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $rels = Pedido::orderBy('created_at','desc')->paginate(10);
        $view = "inicial";
        return view('relatorios.clientes_produtos', compact('view','clientes','prods','rels'));
    }

    public function vendasClientesProdutosFiltro(Request $request)
    {
        $status = $request->input('status');
        $codCliente = $request->input('cliente');
        $codProd = $request->input('produto');
        if($request->input('dataInicio')!=""){
            $dataInicio = $request->input('dataInicio').' '."00:00:00";
        }
        if($request->input('dataFim')!=""){
            $dataFim = $request->input('dataFim').' '."23:59:00";
        }
        if(isset($status)){
            if(isset($codCliente)){
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('user_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('status','like',"%$status%")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('status','like',"%$status%")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            }
        } else {
            if(isset($codCliente)){
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('user_id',"$codCliente")->where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('user_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('user_id',"$codCliente")->where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('user_id',"$codCliente")->where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('user_id',"$codCliente")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('user_id',"$codCliente")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('user_id',"$codCliente")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('user_id',"$codCliente")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            } else {
                if(isset($codProd)){
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::where('produto_id',"$codProd")->whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('produto_id',"$codProd")->where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('produto_id',"$codProd")->where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('produto_id',"$codProd")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                } else {
                    if(isset($dataInicio)){
                        if(isset($dataFim)){
                            $rels = Pedido::whereBetween('created_at',["$dataInicio", "$dataFim"])->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::where('created_at','>=',"$dataInicio")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    } else {
                        if(isset($dataFim)){
                            $rels = Pedido::where('created_at','<=',"$dataFim")->orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        } else {
                            $rels = Pedido::orderBy('created_at','desc')->orderBy('id','desc')->paginate(100);
                        }
                    }
                }
            }
        }
        $clientes = User::orderBy('name')->get();
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('relatorios.clientes_produtos', compact('view','clientes','prods','rels'));
    }

}
