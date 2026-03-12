<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <i class="bi bi-dice-5-fill display-3" style="color: var(--primary, #6C3CE1);"></i>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Connexion</h2>
                <form method="POST" action="<?= SITE_URL ?>/connexion">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
                <hr>
                <p class="text-center mb-0">
                    Pas encore de compte ? <a href="<?= SITE_URL ?>/inscription">Créer un compte</a>
                </p>
            </div>
        </div>
        <!-- Comptes de test -->
        <div class="card mt-3 bg-light">
            <div class="card-body small">
                <h6><i class="bi bi-key me-1"></i>Comptes de test</h6>
                <p class="mb-1"><strong>Président :</strong> president@ludotheque.fr</p>
                <p class="mb-1"><strong>Admin :</strong> admin1@ludotheque.fr</p>
                <p class="mb-1"><strong>Membre :</strong> membre1@ludotheque.fr</p>
                <p class="mb-1"><strong>Non-membre :</strong> user1@email.com</p>
                <p class="mb-0 text-muted">Mot de passe : <code>password123</code></p>
            </div>
        </div>
    </div>
</div>
