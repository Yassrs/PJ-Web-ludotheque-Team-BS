<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="<?= SITE_URL ?>/president" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>Ajouter un Administrateur</h2>
        <div class="card"><div class="card-body">
            <form method="POST" action="<?= SITE_URL ?>/president/ajouter-admin">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Utilisateur *</label>
                    <select name="id_utilisateur" class="form-select" required>
                        <option value="">-- Choisir un utilisateur --</option>
                        <?php foreach ($utilisateurs as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?> (<?= $u['email'] ?>) — <?= ucfirst($u['role']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rôle au bureau *</label>
                    <select name="role_bureau" class="form-select" required>
                        <option value="vice_president">Vice-Président(e)</option>
                        <option value="tresorier">Trésorier(ère)</option>
                        <option value="secretaire">Secrétaire</option>
                        <option value="responsable_jeux">Responsable Jeux</option>
                        <option value="responsable_comm">Responsable Communication</option>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col"><label class="form-label">Début mandat</label><input type="date" name="date_debut_mandat" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                    <div class="col"><label class="form-label">Fin mandat</label><input type="date" name="date_fin_mandat" class="form-control" value="<?= date('Y-m-d', strtotime('+1 year')) ?>"></div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Ajouter</button>
                <a href="<?= SITE_URL ?>/president" class="btn btn-outline-secondary ms-2">Annuler</a>
            </form>
        </div></div>
    </div>
</div>
