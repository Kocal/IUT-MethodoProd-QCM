<?php

use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            'Introduction aux systèmes informatiques',
            "Introduction à l'algorithmique et à la programmation",
            "Structure de données et algorithmes fondamentaux",
            "Introductions aux bases de données",
            "Conception de documents et d'interfaces numériques",
            "Projet tutoré – Découverte",

            "Mathématiques discrètes",
            "Algèbre linéaire",
            "Environnement économique",
            "Fonctionnement des organisations",
            "Expression-Communication – Fondamentaux de la communication",
            "Anglais",
            "PPP – Connaître le monde professionnel",

            "Architecture et programmation des mécanismes de base d'un système informatique",
            "Architecture des réseaux",
            "Bases de la programmation orientée objet",
            "Bases de la conception orientée objet",
            "Introduction aux interfaces homme-machine (IHM)",
            "Programmation et administration des bases de données",
            "Projet tutoré – Description et planification de projet",

            "Graphes et langages",
            "Analyse et méthodes numériques",
            "Environnement comptable, financier, juridique et social",
            "Gestion de projet informatique",
            "Expression-Communication – Communication, information et argumentation",
            "Communiquer en anglais",
            "PPP – Identifier ses compétences",
        ];

        foreach($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject,
                'slug' => str_slug($subject)
            ]);
        }
    }
}
