-- ============================================================
-- LUDOTHÈQUE - Script de création de la base de données
-- Projet Web APP 2026 - ECE ING3
-- ============================================================

DROP DATABASE IF EXISTS ludotheque_db;
CREATE DATABASE ludotheque_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ludotheque_db;

-- ============================================================
-- TABLE : UTILISATEUR
-- ============================================================
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('non_membre', 'membre', 'admin', 'president') NOT NULL DEFAULT 'non_membre',
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    INDEX idx_role (role),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ============================================================
-- TABLE : JEU
-- ============================================================
CREATE TABLE jeu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(500) DEFAULT 'default_jeu.png',
    nb_joueurs_min INT NOT NULL DEFAULT 1,
    nb_joueurs_max INT NOT NULL DEFAULT 4,
    difficulte_apprentissage ENUM('facile', 'moyen', 'difficile') NOT NULL DEFAULT 'moyen',
    difficulte_jeu ENUM('facile', 'moyen', 'difficile') NOT NULL DEFAULT 'moyen',
    temps_jeu_minutes INT NOT NULL DEFAULT 30,
    regles TEXT,
    statut ENUM('en_stock', 'emprunte', 'loue', 'perdu') NOT NULL DEFAULT 'en_stock',
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_difficulte (difficulte_apprentissage, difficulte_jeu)
) ENGINE=InnoDB;

-- ============================================================
-- TABLE : EVENEMENT
-- ============================================================
CREATE TABLE evenement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_evenement DATE NOT NULL,
    heure TIME NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    image VARCHAR(500) DEFAULT 'default_event.png',
    categorie ENUM('salle_jeudi', 'jeu_jeudi', 'soiree_jeux', 'occasionnel') NOT NULL,
    id_createur INT,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categorie (categorie),
    INDEX idx_date (date_evenement),
    FOREIGN KEY (id_createur) REFERENCES utilisateur(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE : DEMANDE (emprunt, location, réservation)
-- ============================================================
CREATE TABLE demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_jeu INT NOT NULL,
    type_demande ENUM('emprunt', 'location', 'reservation') NOT NULL,
    statut ENUM('en_attente', 'validee', 'refusee') NOT NULL DEFAULT 'en_attente',
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    date_demande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_traitement DATETIME NULL,
    id_admin INT NULL,
    motif_refus TEXT NULL,
    INDEX idx_statut (statut),
    INDEX idx_type (type_demande),
    INDEX idx_utilisateur (id_utilisateur),
    INDEX idx_jeu (id_jeu),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jeu) REFERENCES jeu(id) ON DELETE CASCADE,
    FOREIGN KEY (id_admin) REFERENCES utilisateur(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABLE : BUREAU (membres du bureau de l'association)
-- ============================================================
CREATE TABLE bureau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    role_bureau ENUM('president', 'vice_president', 'tresorier', 'secretaire', 'responsable_jeux', 'responsable_comm') NOT NULL,
    date_debut_mandat DATE NOT NULL,
    date_fin_mandat DATE NOT NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    INDEX idx_actif (actif),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABLE : MESSAGE_CONTACT
-- ============================================================
CREATE TABLE message_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lu TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;


-- ============================================================
-- TABLE : VACANCES_UNIVERSITAIRES
-- ============================================================
CREATE TABLE vacances_universitaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    annee_scolaire VARCHAR(9) NOT NULL DEFAULT '2025-2026',
    INDEX idx_dates (date_debut, date_fin)
) ENGINE=InnoDB;


-- ============================================================
-- DONNÉES DE TEST
-- ============================================================

-- Vacances universitaires 2025-2026 (zone C / Île-de-France)
INSERT INTO vacances_universitaires (libelle, date_debut, date_fin, annee_scolaire) VALUES
('Vacances de la Toussaint', '2025-10-18', '2025-11-03', '2025-2026'),
('Vacances de Noël', '2025-12-20', '2026-01-05', '2025-2026'),
('Vacances d''hiver', '2026-02-14', '2026-03-02', '2025-2026'),
('Vacances de printemps', '2026-04-11', '2026-04-27', '2025-2026'),
('Vacances d''été', '2026-07-04', '2026-09-01', '2025-2026');

-- Mot de passe : "password123" hashé avec password_hash()
-- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES
('Dupont', 'Marie', 'president@ludotheque.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'president'),
('Martin', 'Lucas', 'admin1@ludotheque.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Bernard', 'Sophie', 'admin2@ludotheque.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Petit', 'Thomas', 'membre1@ludotheque.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'membre'),
('Leroy', 'Emma', 'membre2@ludotheque.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'membre'),
('Moreau', 'Hugo', 'user1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'non_membre'),
('Garcia', 'Léa', 'user2@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'non_membre');

INSERT INTO bureau (id_utilisateur, role_bureau, date_debut_mandat, date_fin_mandat, actif) VALUES
(1, 'president', '2025-09-01', '2026-08-31', 1),
(2, 'vice_president', '2025-09-01', '2026-08-31', 1),
(3, 'tresorier', '2025-09-01', '2026-08-31', 1);

INSERT INTO jeu (nom, description, image, nb_joueurs_min, nb_joueurs_max, difficulte_apprentissage, difficulte_jeu, temps_jeu_minutes, regles, statut) VALUES
('Catan', 'Jeu de stratégie et de commerce où les joueurs colonisent une île en collectant et échangeant des ressources.', 'catan.jpg', 3, 4, 'moyen', 'moyen', 75, 'Chaque joueur place des colonies sur les intersections du plateau. À chaque tour, on lance les dés pour produire des ressources. Utilisez-les pour construire routes, colonies et villes.', 'en_stock'),
('Dixit', 'Jeu de créativité et d''imagination basé sur l''interprétation d''illustrations poétiques.', 'dixit.jpg', 3, 6, 'facile', 'facile', 30, 'Le conteur choisit une carte et donne un indice. Les autres joueurs choisissent une carte correspondant à l''indice. Tous votent pour deviner la carte du conteur.', 'en_stock'),
('Pandemic', 'Jeu coopératif où les joueurs travaillent ensemble pour éradiquer des maladies qui menacent le monde.', 'pandemic.jpg', 2, 4, 'moyen', 'difficile', 60, 'Les joueurs incarnent des spécialistes luttant contre 4 épidémies. Déplacez-vous, traitez les infections et trouvez les remèdes avant que la situation ne devienne incontrôlable.', 'en_stock'),
('Azul', 'Jeu abstrait de placement de tuiles inspiré des azulejos portugais.', 'azul.jpg', 2, 4, 'facile', 'moyen', 45, 'Choisissez des tuiles dans les fabriques et placez-les sur votre plateau personnel. Marquez des points en complétant des lignes et des motifs.', 'emprunte'),
('7 Wonders', 'Jeu de cartes et de civilisation où chaque joueur développe sa cité antique.', '7wonders.jpg', 3, 7, 'moyen', 'moyen', 30, 'En 3 âges, choisissez une carte, jouez-la, et passez votre main au voisin. Développez science, militaire, commerce et merveilles.', 'en_stock'),
('Ticket to Ride', 'Jeu de collection de cartes pour construire des lignes de train à travers l''Europe.', 'ticket_to_ride.jpg', 2, 5, 'facile', 'facile', 60, 'Collectez des cartes wagon pour revendiquer des routes ferroviaires sur la carte. Complétez vos destinations secrètes pour marquer des points bonus.', 'en_stock'),
('Terraforming Mars', 'Jeu de stratégie complexe où les joueurs rendent Mars habitable.', 'terraforming_mars.jpg', 1, 5, 'difficile', 'difficile', 120, 'Gérez vos ressources et jouez des cartes projet pour augmenter la température, l''oxygène et les océans sur Mars. Le joueur avec le plus de points de terraformation gagne.', 'loue'),
('Codenames', 'Jeu d''association de mots en équipes.', 'codenames.jpg', 4, 8, 'facile', 'moyen', 20, 'Deux équipes s''affrontent. Le maître-espion donne un indice d''un mot pour faire deviner plusieurs mots à son équipe. Évitez le mot assassin !', 'en_stock'),
('Wingspan', 'Jeu de moteur de cartes sur le thème des oiseaux.', 'wingspan.jpg', 1, 5, 'moyen', 'moyen', 70, 'Attirez des oiseaux dans vos habitats en utilisant de la nourriture. Chaque oiseau possède un pouvoir unique qui s''active lors des tours suivants.', 'en_stock'),
('Splendor', 'Jeu de collection de gemmes et de développement de prestige.', 'splendor.jpg', 2, 4, 'facile', 'moyen', 30, 'Collectez des jetons gemmes pour acheter des cartes développement. Les cartes vous offrent des réductions permanentes et des points de prestige.', 'en_stock');

INSERT INTO evenement (titre, description, date_evenement, heure, lieu, image, categorie, id_createur) VALUES
('Salle Ouverte - Jeudi Détente', 'La salle de jeux est ouverte à tous pour jouer librement avec les jeux de la ludothèque.', '2026-03-12', '17:00:00', 'Salle B204 - Campus ECE', 'salle_jeudi.jpg', 'salle_jeudi', 1),
('Jeu du Jeudi : Catan', 'Venez découvrir ou redécouvrir Catan lors de notre session hebdomadaire !', '2026-03-12', '18:00:00', 'Salle B204 - Campus ECE', 'jeu_jeudi_catan.jpg', 'jeu_jeudi', 2),
('Soirée Jeux de Printemps', 'Grande soirée jeux de société pour fêter le printemps. Snacks et boissons offerts !', '2026-03-21', '19:00:00', 'Amphi A - Campus ECE', 'soiree_printemps.jpg', 'soiree_jeux', 1),
('Tournoi Azul', 'Tournoi spécial Azul avec prix pour les 3 premiers. Inscription obligatoire.', '2026-04-05', '14:00:00', 'Salle C101 - Campus ECE', 'tournoi_azul.jpg', 'occasionnel', 3),
('Salle Ouverte - Jeudi Classiques', 'Session spéciale jeux classiques : échecs, dames, backgammon et plus encore.', '2026-03-19', '17:00:00', 'Salle B204 - Campus ECE', 'salle_classiques.jpg', 'salle_jeudi', 2),
('Jeu du Jeudi : Pandemic', 'Sauvez le monde ensemble ! Session coopérative Pandemic.', '2026-03-19', '18:00:00', 'Salle B204 - Campus ECE', 'jeu_jeudi_pandemic.jpg', 'jeu_jeudi', 2),
('Soirée Jeux d''Été', 'Une soirée estivale autour de jeux légers et rapides. Ambiance décontractée et conviviale.', '2026-04-18', '19:30:00', 'Terrasse Campus ECE', 'soiree_ete.jpg', 'soiree_jeux', 1),
('Soirée Jeux en Duo', 'Soirée spéciale jeux à 2 joueurs. Parfait pour les duels stratégiques !', '2026-05-09', '19:00:00', 'Salle B204 - Campus ECE', 'soiree_duo.jpg', 'soiree_jeux', 2),
('Marathon Jeux 24h', 'Le défi ultime : 24 heures de jeux non-stop ! Équipes de 4 joueurs.', '2026-04-25', '10:00:00', 'Hall Principal - Campus ECE', 'marathon_24h.jpg', 'occasionnel', 1),
('Brocante Jeux de Société', 'Vendez et achetez des jeux de société d''occasion entre étudiants.', '2026-05-15', '14:00:00', 'Parvis Campus ECE', 'brocante.jpg', 'occasionnel', 3);

INSERT INTO demande (id_utilisateur, id_jeu, type_demande, statut, date_debut, date_fin) VALUES
(4, 4, 'emprunt', 'validee', '2026-03-01', '2026-03-15'),
(6, 7, 'location', 'validee', '2026-03-03', '2026-03-10'),
(5, 1, 'reservation', 'en_attente', '2026-03-12', '2026-03-12'),
(4, 8, 'emprunt', 'en_attente', '2026-03-10', '2026-03-24'),
(6, 5, 'location', 'refusee', '2026-03-05', '2026-03-12');
