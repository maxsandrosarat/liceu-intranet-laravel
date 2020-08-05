<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date("dia");
            $table->unsignedBigInteger('turma_id');
            $table->unsignedBigInteger('disciplina_id');
            $table->unsignedBigInteger('prof_id');
            $table->integer('tempo')->nullable();
            $table->boolean('segundo_tempo')->default(false);
            $table->integer('outro_tempo')->nullable();
            $table->string('tema')->nullable();
            $table->string('conteudo')->nullable();
            $table->string('referencias')->nullable();
            $table->enum('tipo_tarefa',['AULA','SCULES'])->nullable();
            $table->string('tarefa')->nullable();
            $table->date("entrega_tarefa")->nullable();
            $table->boolean('conferido')->default(false);
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->foreign('prof_id')->references('id')->on('profs');
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
        Schema::dropIfExists('diarios');
    }
}
