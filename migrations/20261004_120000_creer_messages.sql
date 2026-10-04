-- La table des messages reçus par le formulaire de contact.
--
-- Ce fichier est une MIGRATION : un changement de la structure de la base.
-- « wazi db:migrate » l'applique une fois, et s'en souvient.
--
-- Pour changer cette table plus tard (ajouter une colonne, par exemple), ne
-- modifiez pas ce fichier : créez une nouvelle migration.
--
--     wazi make:migration ajouter_email_aux_messages
--
-- Ce SQL est écrit pour SQLite, la base de départ du projet. Avec MySQL, « id »
-- s'écrit « id INT AUTO_INCREMENT PRIMARY KEY » ; avec PostgreSQL,
-- « id SERIAL PRIMARY KEY ».

CREATE TABLE messages (
    -- Un numéro que la base donne elle-même à chaque message.
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(80) NOT NULL,
    texte TEXT NOT NULL,
    -- La date de réception, écrite ainsi : 2026-10-04 15:30:00
    recu_le VARCHAR(19) NOT NULL
);
