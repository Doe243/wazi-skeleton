<?php

declare(strict_types=1);

namespace App;

use Wazi\Console\DetailedCommand;
use Wazi\Console\Input;
use Wazi\Console\Option;
use Wazi\Console\Output;

/**
 * Une commande de votre projet : « wazi messages ».
 *
 * Elle affiche les messages reçus par le formulaire de contact. C'est un
 * modèle : copiez-la pour écrire vos propres commandes, puis déclarez la
 * vôtre dans le fichier « wazi », à la racine du projet.
 *
 * Une commande déclare son nom, sa description, ses arguments et ses options.
 * Ce qu'elle n'a pas déclaré est refusé avant même qu'elle s'exécute.
 *
 * Elle implémente DetailedCommand : « wazi messages --help » montre alors aussi
 * des exemples et un texte d'aide. Une commande plus simple implémente Command,
 * et se passe de help() et de examples().
 *
 * Ces messages ont été écrits par des visiteurs. Output nettoie tout ce qu'il
 * affiche : un message piégé ne peut rien faire à votre terminal.
 */
final readonly class MessagesCommand implements DetailedCommand
{
    public function __construct(private Messagerie $messagerie) {}

    public function name(): string
    {
        return 'messages';
    }

    public function description(): string
    {
        return 'Affiche les messages reçus par le formulaire de contact.';
    }

    public function arguments(): array
    {
        return [];
    }

    public function options(): array
    {
        return [
            // Une option à valeur : --derniers=5. Sa valeur par défaut est un texte.
            new Option('derniers', 'Combien de messages afficher, en partant du plus récent', '10'),
        ];
    }

    public function help(): string
    {
        // Le texte d'aide peut tenir sur plusieurs lignes : les retours à la ligne sont gardés.
        return implode("\n", [
            'Lit les messages gardés dans la base de données, du plus récent au plus ancien.',
            'Rien n\'est modifié : la commande ne fait que lire.',
        ]);
    }

    public function examples(): array
    {
        // Ce qu'on tape après « wazi messages » => ce que cela fait.
        return [
            '' => 'Les dix derniers messages',
            '--derniers=3' => 'Les trois derniers',
        ];
    }

    public function run(Input $input, Output $output): int
    {
        $combien = $input->option('derniers');

        // Ce qui est tapé se vérifie, comme ce qui vient d'un formulaire.
        if (!ctype_digit($combien) || (int) $combien < 1 || (int) $combien > 1000) {
            $output->error('L\'option --derniers attend un nombre entre 1 et 1000, par exemple --derniers=5.');

            // 2 : la commande a été mal écrite.
            return 2;
        }

        $messages = $this->messagerie->derniers((int) $combien);

        if ($messages === []) {
            $output->line('Aucun message pour l\'instant.');

            return 0;
        }

        $output->title(count($messages) . ' message(s), du plus récent au plus ancien');

        foreach ($messages as $message) {
            $output->line($message['date'] . ' — ' . $message['nom']);
            $output->line('  ' . str_replace("\n", "\n  ", $message['texte']));
            $output->line();
        }

        // 0 : tout s'est bien passé.
        return 0;
    }
}
