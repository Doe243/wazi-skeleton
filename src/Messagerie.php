<?php

declare(strict_types=1);

namespace App;

/**
 * Un service : il garde les messages reçus, et ne sait rien du web.
 *
 * Ici, il les ajoute à un fichier, un message par ligne. Le jour où vous
 * voudrez les envoyer par courriel ou les ranger dans une base de données,
 * seule cette classe changera : le contrôleur n'en saura rien.
 */
final readonly class Messagerie
{
    /**
     * @param string $fichier où garder les messages, hors du dossier public
     */
    public function __construct(private string $fichier) {}

    public function garder(string $nom, string $texte): void
    {
        if (!is_dir(dirname($this->fichier))) {
            mkdir(dirname($this->fichier), 0o700, true);
        }

        // Un message par ligne, écrit en JSON : les retours à la ligne saisis
        // par le visiteur y sont notés « \n », ils ne coupent pas la ligne.
        $ligne = json_encode(['date' => date('c'), 'nom' => $nom, 'texte' => $texte], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        // FILE_APPEND ajoute à la fin ; LOCK_EX empêche deux requêtes d'écrire en même temps.
        if (file_put_contents($this->fichier, $ligne . "\n", FILE_APPEND | LOCK_EX) === false) {
            throw new \RuntimeException('Le message n\'a pas pu être enregistré. Vérifiez que PHP a le droit d\'écrire dans le dossier var/.');
        }
    }

    /**
     * Les messages gardés, du plus ancien au plus récent.
     *
     * @return list<array{date: string, nom: string, texte: string}>
     */
    public function tous(): array
    {
        if (!is_file($this->fichier)) {
            return [];
        }

        $messages = [];

        foreach (file($this->fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $ligne) {
            $message = json_decode($ligne, true);

            // Ce qu'on relit d'un fichier se vérifie : une ligne abîmée est ignorée.
            if (is_array($message)
                && is_string($message['date'] ?? null)
                && is_string($message['nom'] ?? null)
                && is_string($message['texte'] ?? null)
            ) {
                $messages[] = ['date' => $message['date'], 'nom' => $message['nom'], 'texte' => $message['texte']];
            }
        }

        return $messages;
    }
}
