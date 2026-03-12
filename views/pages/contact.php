<h2 class="text-center mb-4"><i class="bi bi-chat-dots me-2"></i>Contactez-nous</h2>
<div class="row g-4 justify-content-center">
    <div class="col-md-5">
        <h4 class="mb-3">Nos coordonnées</h4>
        <div class="card mb-3"><div class="card-body"><i class="bi bi-envelope me-2 text-primary"></i><strong>contact@ludotheque.fr</strong></div></div>
        <div class="card mb-3"><div class="card-body"><i class="bi bi-instagram me-2 text-danger"></i><strong>@ludotheque_ece</strong></div></div>
        <div class="card mb-3"><div class="card-body"><i class="bi bi-discord me-2 text-info"></i><strong>Ludothèque ECE</strong></div></div>
        <div class="card"><div class="card-body"><i class="bi bi-geo-alt me-2 text-success"></i><strong>Campus ECE Paris</strong><br><small class="text-muted">37 Quai de Grenelle, 75015 Paris</small></div></div>
    </div>
    <div class="col-md-5">
        <h4 class="mb-3">Envoyer un message</h4>
        <div class="card"><div class="card-body">
            <form method="POST" action="<?= SITE_URL ?>/contact">
                <?= csrf_field() ?>
                <div class="mb-3"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-control" required value="<?= isLoggedIn() ? htmlspecialchars(currentUser()['nom']) : '' ?>"></div>
                <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required value="<?= isLoggedIn() ? htmlspecialchars(currentUser()['email']) : '' ?>"></div>
                <div class="mb-3"><label class="form-label">Sujet *</label><input type="text" name="sujet" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Message *</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send me-1"></i>Envoyer</button>
            </form>
        </div></div>
    </div>
</div>
