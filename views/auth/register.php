<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <i class="bi bi-dice-5-fill display-3" style="color: var(--primary, #6C3CE1);"></i>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Créer un compte</h2>
                <form method="POST" action="<?= SITE_URL ?>/inscription">
                    <?= csrf_field() ?>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" minlength="6" required>
                        <div class="form-text">Minimum 6 caractères</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirm" name="confirm_mot_de_passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Créer mon compte</button>
                </form>
                <hr>
                <p class="text-center mb-0">Déjà un compte ? <a href="<?= SITE_URL ?>/connexion">Se connecter</a></p>
            </div>
        </div>
    </div>
</div>
