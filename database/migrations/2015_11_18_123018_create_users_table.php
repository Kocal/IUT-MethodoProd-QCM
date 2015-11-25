<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // Identifiant
            $table->tinyInteger('type'); // Type d'utilisateur (Enseignant ou Etudiant)
            $table->string('first_name'); // Prénom
            $table->string('last_name'); // Nom de famille
            $table->string('type', 20); // Type de l'utilisateur
            $table->string('email', 176)->unique(); // Adresse e-mail
            $table->string('password', 60); // Mot de passe
            $table->rememberToken(); // Token pour se souvenir de la connexion
            $table->timestamps(); // created_at && updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }
}
