<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnexoPlanejamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anexo_planejamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('planejamento_id');
            $table->foreign('planejamento_id')->references('id')->on('planejamentos');
            $table->integer('serie');
            $table->string('ensino');
            $table->unsignedBigInteger('disciplina_id');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->string('arquivo')->nullable();
            $table->boolean('conferido')->default(false);
            $table->longText('comentario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anexo_planejamentos');
    }
}
