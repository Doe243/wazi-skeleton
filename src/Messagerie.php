<?php

declare(strict_types=1);

namespace App;

use Wazi\Database\Database;

/**
 * Un service : il garde les messages reçus, et ne sait rien du web.
 *
 * Les messages sont rangés dans la base de données, dans la table « messages »
 * (créée par migrations/20261004_120000_creer_messages.sql).
 *
 * C'est le seul endroit du projet qui écrit du SQL sur les messages : le jour
 * où vous voudrez aussi les envoyer par courriel, seule cette classe changera.
 */
final readonly class Messagerie
{
    public function __construct(private Database $db) {}

    public function garder(string $nom, string $texte): void
    {
        // Les valeurs sont données À PART du SQL : quoi qu'un visiteur écrive
        // dans son message, cela reste un texte, jamais une requête.
        $this->db->insert('messages', [
            'nom' => $nom,
            'texte' => $texte,
            'recu_le' => new \DateTimeImmutable(),
        ]);
    }

    /**
     * Les derniers messages reçus, du plus récent au plus ancien.
     *
     * @return list<array{date: string, nom: string, texte: string}>
     */
    public function derniers(int $combien): array
    {
        // Le « ? » est un marqueur : la base y met la valeur donnée à côté.
        $lignes = $this->db->select('SELECT nom, texte, recu_le FROM messages ORDER BY id DESC LIMIT ?', [$combien]);

        $messages = [];

        foreach ($lignes as $ligne) {
            // Pour PHP, ce qui revient d'une base est de type inconnu : on le vérifie.
            if (is_string($ligne['recu_le']) && is_string($ligne['nom']) && is_string($ligne['texte'])) {
                $messages[] = ['date' => $ligne['recu_le'], 'nom' => $ligne['nom'], 'texte' => $ligne['texte']];
            }
        }

        return $messages;
    }
}
