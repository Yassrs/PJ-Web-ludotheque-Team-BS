<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="<?= SITE_URL ?>/admin/jeux" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        <h2 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Ajouter un Jeu</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= SITE_URL ?>/admin/jeux/ajouter" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nom du jeu *</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Joueurs min</label>
                            <input type="number" name="nb_joueurs_min" class="form-control" value="2" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Joueurs max</label>
                            <input type="number" name="nb_joueurs_max" class="form-control" value="4" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Temps (min)</label>
                            <input type="number" name="temps_jeu_minutes" class="form-control" value="30" min="5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Difficulté d'apprentissage</label>
                            <select name="difficulte_apprentissage" class="form-select">
                                <option value="facile">Facile</option>
                                <option value="moyen" selected>Moyen</option>
                                <option value="difficile">Difficile</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Difficulté de jeu</label>
                            <select name="difficulte_jeu" class="form-select">
                                <option value="facile">Facile</option>
                                <option value="moyen" selected>Moyen</option>
                                <option value="difficile">Difficile</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Règles du jeu</label>
                        <textarea name="regles" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Ajouter</button>
                    <a href="<?= SITE_URL ?>/admin/jeux" class="btn btn-outline-secondary ms-2">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
