    </div><!-- /.container -->
</main>

<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="bi bi-dice-5-fill me-2"></i>Ludo<span style="color:#FFD93D;">thèque</span></h5>
                <p class="text-muted small">Association étudiante dédiée aux jeux de société. Emprunts, locations et événements pour tous les étudiants du campus ECE.</p>
                <div class="d-flex gap-3 mt-2">
                    <a href="#" class="text-muted fs-5" data-bs-toggle="tooltip" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-muted fs-5" data-bs-toggle="tooltip" title="Discord"><i class="bi bi-discord"></i></a>
                    <a href="mailto:contact@ludotheque.fr" class="text-muted fs-5" data-bs-toggle="tooltip" title="Email"><i class="bi bi-envelope-fill"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h6>Liens rapides</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?= SITE_URL ?>/ludotheque" class="text-muted text-decoration-none">Nos jeux</a></li>
                    <li><a href="<?= SITE_URL ?>/evenements" class="text-muted text-decoration-none">Événements</a></li>
                    <li><a href="<?= SITE_URL ?>/a-propos" class="text-muted text-decoration-none">À propos</a></li>
                    <li><a href="<?= SITE_URL ?>/contact" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Contact & Infos</h6>
                <p class="text-muted small mb-1">
                    <i class="bi bi-envelope me-2"></i>contact@ludotheque.fr
                </p>
                <p class="text-muted small mb-1">
                    <i class="bi bi-geo-alt me-2"></i>Campus ECE Paris
                </p>
                <p class="text-muted small mb-1">
                    <i class="bi bi-clock me-2"></i>Ouvert les jeudis 17h-21h
                </p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-controller me-2"></i><?php $jeuCountFooter = new Jeu(); echo $jeuCountFooter->countAll() ?? '10'; ?> jeux disponibles
                </p>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center text-muted small mb-0">&copy; 2026 Ludothèque — Association Étudiante ECE. Projet Web APP ING3.</p>
    </div>
</footer>

<!-- ============================================================
     MODAL : Confirmation de suppression (réutilisable globale)
     Déclenchée par [data-delete-url] / main.js section 2
     ============================================================ -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer <strong id="deleteModalName"></strong> ?</p>
                <p class="text-muted small mb-0">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="deleteModalConfirmBtn" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL : Refus de demande avec motif (admin)
     Déclenchée par [data-refuse-id] / main.js section 3
     ============================================================ -->
<div class="modal fade" id="refuseDemandeModal" tabindex="-1" aria-labelledby="refuseDemandeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="refuseModalForm" method="POST" action="#">
                <?= csrf_field() ?>
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="refuseDemandeLabel"><i class="bi bi-x-circle me-2"></i>Refuser la demande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Refuser la demande de <strong id="refuseModalUser"></strong> pour <strong id="refuseModalJeu"></strong> ?</p>
                    <div class="mb-3">
                        <label for="refuseMotif" class="form-label">Motif du refus <small class="text-muted">(optionnel)</small></label>
                        <textarea class="form-control" id="refuseMotif" name="motif_refus" rows="3" placeholder="Ex : jeu réservé pour un événement, stock insuffisant..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-x-circle me-1"></i>Refuser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= SITE_URL ?>/public/js/main.js"></script>
</body>
</html>
