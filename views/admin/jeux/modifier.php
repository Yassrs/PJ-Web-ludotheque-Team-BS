<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="<?= SITE_URL ?>/admin/jeux" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        <h2 class="mb-4"><i class="bi bi-pencil me-2"></i>Modifier : <?= htmlspecialchars($jeu['nom']) ?></h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= SITE_URL ?>/admin/jeux/modifier/<?= $jeu['id'] ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom du jeu *</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($jeu['nom']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($jeu['description']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Joueurs min</label>
                            <input type="number" name="nb_joueurs_min" class="form-control" value="<?= $jeu['nb_joueurs_min'] ?>" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Joueurs max</label>
                            <input type="number" name="nb_joueurs_max" class="form-control" value="<?= $jeu['nb_joueurs_max'] ?>" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Temps (min)</label>
                            <input type="number" name="temps_jeu_minutes" class="form-control" value="<?= $jeu['temps_jeu_minutes'] ?>" min="5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <?php foreach (['en_stock'=>'En stock','emprunte'=>'Emprunté','loue'=>'Loué','perdu'=>'Perdu'] as $k=>$v): ?>
                                <option value="<?= $k ?>" <?= $jeu['statut'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Difficulté apprentissage</label>
                            <select name="difficulte_apprentissage" class="form-select">
                                <?php foreach (['facile','moyen','difficile'] as $d): ?>
                                <option value="<?= $d ?>" <?= $jeu['difficulte_apprentissage'] === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Difficulté de jeu</label>
                            <select name="difficulte_jeu" class="form-select">
                                <?php foreach (['facile','moyen','difficile'] as $d): ?>
                                <option value="<?= $d ?>" <?= $jeu['difficulte_jeu'] === $d ? 'selected' : '' ?>><?= ucfirst($d) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nouvelle image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Actuelle : <?= htmlspecialchars($jeu['image']) ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Règles</label>
                        <textarea name="regles" class="form-control" rows="4"><?= htmlspecialchars($jeu['regles']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                    <a href="<?= SITE_URL ?>/admin/jeux" class="btn btn-outline-secondary ms-2">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
