<a href="<?= SITE_URL ?>/ludotheque" class="text-decoration-none text-muted mb-3 d-inline-block">
    <i class="bi bi-arrow-left me-1"></i>Retour au catalogue
</a>

<div class="row g-4">
    <!-- Image -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-5 bg-light">
                <?php if ($jeu['image'] && $jeu['image'] !== 'default_jeu.png'): ?>
                    <img src="<?= SITE_URL ?>/public/img/jeux/<?= htmlspecialchars($jeu['image']) ?>" alt="<?= htmlspecialchars($jeu['nom']) ?>" class="img-fluid">
                <?php else: ?>
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <p class="text-muted mt-2">Image non disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Informations -->
    <div class="col-md-8">
        <div class="d-flex align-items-center gap-3 mb-3">
            <h1 class="mb-0"><?= htmlspecialchars($jeu['nom']) ?></h1>
            <?php
            $statutBadge = ['en_stock'=>'success', 'emprunte'=>'warning', 'loue'=>'info', 'perdu'=>'danger'];
            $statutLabel = ['en_stock'=>'En stock', 'emprunte'=>'Emprunté', 'loue'=>'Loué', 'perdu'=>'Indisponible'];
            ?>
            <span class="badge bg-<?= $statutBadge[$jeu['statut']] ?? 'secondary' ?> fs-6">
                <?= $statutLabel[$jeu['statut']] ?? $jeu['statut'] ?>
            </span>
        </div>

        <!-- Caractéristiques -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card bg-light text-center p-3">
                    <i class="bi bi-people fs-4 text-primary"></i>
                    <div class="fw-bold"><?= $jeu['nb_joueurs_min'] ?> — <?= $jeu['nb_joueurs_max'] ?></div>
                    <small class="text-muted">Joueurs</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card bg-light text-center p-3">
                    <i class="bi bi-clock fs-4 text-primary"></i>
                    <div class="fw-bold"><?= $jeu['temps_jeu_minutes'] ?> min</div>
                    <small class="text-muted">Temps de jeu</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card bg-light text-center p-3">
                    <i class="bi bi-mortarboard fs-4 text-primary"></i>
                    <div class="fw-bold"><?= ucfirst($jeu['difficulte_apprentissage']) ?></div>
                    <small class="text-muted">Apprentissage</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card bg-light text-center p-3">
                    <i class="bi bi-trophy fs-4 text-primary"></i>
                    <div class="fw-bold"><?= ucfirst($jeu['difficulte_jeu']) ?></div>
                    <small class="text-muted">Difficulté</small>
                </div>
            </div>
        </div>

        <!-- Description -->
        <?php if ($jeu['description']): ?>
        <h5>Description</h5>
        <p><?= nl2br(htmlspecialchars($jeu['description'])) ?></p>
        <?php endif; ?>

        <!-- Règles -->
        <?php if ($jeu['regles']): ?>
        <h5>Règles du jeu</h5>
        <p><?= nl2br(htmlspecialchars($jeu['regles'])) ?></p>
        <?php endif; ?>

        <!-- Actions -->
        <div class="mt-4">
            <?php if (!isLoggedIn()): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <a href="<?= SITE_URL ?>/connexion">Connectez-vous</a> pour emprunter, louer ou réserver ce jeu.
                </div>

            <?php elseif ($hasActiveRequest): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Vous avez déjà une demande en cours pour ce jeu.
                    <a href="<?= SITE_URL ?>/mon-compte/demandes">Voir mes demandes</a>
                </div>

            <?php elseif ($jeu['statut'] === STATUT_EN_STOCK): ?>
                <div class="d-flex flex-wrap gap-2">

                    <?php if (isMembre()): ?>
                    <!-- EMPRUNT — Membres uniquement (gratuit, 1-2 semaines) -->
                    <form method="POST" action="<?= SITE_URL ?>/demande/emprunt/<?= $jeu['id'] ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <div class="input-group">
                            <select name="duree" class="form-select form-select-sm" style="max-width:140px;">
                                <option value="7">1 semaine</option>
                                <option value="14">2 semaines</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-box-arrow-up me-1"></i>Emprunter (gratuit)
                            </button>
                        </div>
                    </form>

                    <?php else: ?>
                    <!-- LOCATION — Non-membres uniquement (payant, 1-2 semaines) -->
                    <form method="POST" action="<?= SITE_URL ?>/demande/location/<?= $jeu['id'] ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <div class="input-group">
                            <select name="duree" class="form-select form-select-sm" style="max-width:140px;">
                                <option value="7">1 semaine</option>
                                <option value="14">2 semaines</option>
                            </select>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-cart me-1"></i>Louer
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>

                    <!-- RÉSERVATION — Tous les connectés (Jeu du Jeudi) -->
                    <form method="POST" action="<?= SITE_URL ?>/demande/reservation/<?= $jeu['id'] ?>" class="d-inline" id="formReservation">
                        <?= csrf_field() ?>
                        <div class="input-group">
                            <input type="date" name="date_reservation" id="dateReservation"
                                   class="form-control form-control-sm" style="max-width:160px;"
                                   min="<?= date('Y-m-d', strtotime('next Thursday')) ?>" required>
                            <button type="submit" class="btn btn-success btn-sm" id="btnReservation">
                                <i class="bi bi-calendar-check me-1"></i>Réserver (Jeu du Jeudi)
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1" id="reservationHint">
                            <i class="bi bi-info-circle me-1"></i>Sélectionnez un jeudi hors vacances universitaires.
                        </small>
                    </form>
                </div>

                <!-- Info contextuelle selon le statut -->
                <?php if (isMembre()): ?>
                    <div class="alert alert-light border mt-3 py-2 small">
                        <i class="bi bi-person-check me-1 text-success"></i>
                        <strong>Membre</strong> — Vous pouvez emprunter ce jeu gratuitement pour 1 à 2 semaines.
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border mt-3 py-2 small">
                        <i class="bi bi-person me-1 text-primary"></i>
                        <strong>Non-membre</strong> — Vous pouvez louer ce jeu. 
                        <a href="<?= SITE_URL ?>/a-propos">Devenez membre</a> pour emprunter gratuitement !
                    </div>
                <?php endif; ?>

                <!-- Script de validation côté client pour les réservations -->
                <script>
                $(document).ready(function() {
                    // Périodes de vacances universitaires (injectées depuis PHP)
                    var vacances = [
                        <?php
                        $vacancesModel = new VacancesUniversitaires();
                        $periodes = $vacancesModel->getByAnneeScolaire('2025-2026');
                        $jsArray = [];
                        foreach ($periodes as $p) {
                            $jsArray[] = '{debut:"' . $p['date_debut'] . '",fin:"' . $p['date_fin'] . '",libelle:"' . addslashes($p['libelle']) . '"}';
                        }
                        echo implode(",\n                        ", $jsArray);
                        ?>
                    ];

                    function isInVacances(dateStr) {
                        for (var i = 0; i < vacances.length; i++) {
                            if (dateStr >= vacances[i].debut && dateStr <= vacances[i].fin) {
                                return vacances[i];
                            }
                        }
                        return null;
                    }

                    function isThursday(dateStr) {
                        return new Date(dateStr).getDay() === 4; // 0=dim, 4=jeudi
                    }

                    $('#dateReservation').on('change', function() {
                        var val = $(this).val();
                        var $hint = $('#reservationHint');
                        var $btn = $('#btnReservation');

                        if (!val) {
                            $hint.html('<i class="bi bi-info-circle me-1"></i>Sélectionnez un jeudi hors vacances universitaires.')
                                 .removeClass('text-danger text-success').addClass('text-muted');
                            $btn.prop('disabled', false);
                            return;
                        }

                        // Vérifier que c'est un jeudi
                        if (!isThursday(val)) {
                            $hint.html('<i class="bi bi-exclamation-triangle me-1"></i>Cette date n\'est pas un jeudi. Veuillez choisir un jeudi.')
                                 .removeClass('text-muted text-success').addClass('text-danger');
                            $btn.prop('disabled', true);
                            return;
                        }

                        // Vérifier les vacances
                        var vac = isInVacances(val);
                        if (vac) {
                            $hint.html('<i class="bi bi-exclamation-triangle me-1"></i>Ce jeudi tombe pendant les ' + vac.libelle + '. Choisissez un autre jeudi.')
                                 .removeClass('text-muted text-success').addClass('text-danger');
                            $btn.prop('disabled', true);
                            return;
                        }

                        // Tout est OK
                        $hint.html('<i class="bi bi-check-circle me-1"></i>Date valide — jeudi hors vacances.')
                             .removeClass('text-muted text-danger').addClass('text-success');
                        $btn.prop('disabled', false);
                    });

                    // Validation au submit en sécurité
                    $('#formReservation').on('submit', function(e) {
                        var val = $('#dateReservation').val();
                        if (!val || !isThursday(val) || isInVacances(val)) {
                            e.preventDefault();
                            alert('Veuillez sélectionner un jeudi valide hors vacances universitaires.');
                        }
                    });
                });
                </script>
            <?php else: ?>
                <div class="alert alert-secondary">
                    <i class="bi bi-x-circle me-2"></i>
                    Ce jeu n'est pas disponible actuellement (<?= $statutLabel[$jeu['statut']] ?? $jeu['statut'] ?>).
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
