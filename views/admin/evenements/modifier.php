<div class="row justify-content-center">
    <div class="col-md-8">
        <a href="<?= SITE_URL ?>/admin/evenements" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        <h2 class="mb-4"><i class="bi bi-pencil me-2"></i>Modifier : <?= htmlspecialchars($evenement['titre']) ?></h2>
        <div class="card"><div class="card-body">
            <form method="POST" action="<?= SITE_URL ?>/admin/evenements/modifier/<?= $evenement['id'] ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3"><label class="form-label">Titre *</label><input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($evenement['titre']) ?>" required></div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4"><label class="form-label">Date</label><input type="date" name="date_evenement" class="form-control" value="<?= $evenement['date_evenement'] ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Heure</label><input type="time" name="heure" class="form-control" value="<?= substr($evenement['heure'],0,5) ?>" required></div>
                    <div class="col-md-4"><label class="form-label">Catégorie</label>
                        <select name="categorie" class="form-select">
                            <?php foreach (['salle_jeudi'=>'Salle du Jeudi','jeu_jeudi'=>'Jeu du Jeudi','soiree_jeux'=>'Soirée Jeux','occasionnel'=>'Occasionnel'] as $k=>$v): ?>
                            <option value="<?= $k ?>" <?= $evenement['categorie']===$k?'selected':'' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3"><label class="form-label">Lieu</label><input type="text" name="lieu" class="form-control" value="<?= htmlspecialchars($evenement['lieu']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($evenement['description']) ?></textarea></div>
                <div class="mb-3"><label class="form-label">Nouvelle image</label><input type="file" name="image" class="form-control" accept="image/*"><small class="text-muted">Actuelle : <?= htmlspecialchars($evenement['image']) ?></small></div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                <a href="<?= SITE_URL ?>/admin/evenements" class="btn btn-outline-secondary ms-2">Annuler</a>
            </form>
        </div></div>
    </div>
</div>
