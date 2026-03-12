<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="<?= SITE_URL ?>/admin/evenements" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        <h2 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Ajouter un Événement</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= SITE_URL ?>/admin/evenements/ajouter" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date_evenement" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heure *</label>
                            <input type="time" name="heure" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Catégorie *</label>
                            <select name="categorie" class="form-select" required>
                                <option value="salle_jeudi">Salle du Jeudi</option>
                                <option value="jeu_jeudi">Jeu du Jeudi</option>
                                <option value="soiree_jeux">Soirée Jeux</option>
                                <option value="occasionnel">Occasionnel</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lieu *</label>
                        <input type="text" name="lieu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image / Poster</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Créer</button>
                    <a href="<?= SITE_URL ?>/admin/evenements" class="btn btn-outline-secondary ms-2">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
