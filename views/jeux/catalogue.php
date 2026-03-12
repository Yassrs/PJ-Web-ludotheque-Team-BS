<h2 class="mb-4"><i class="bi bi-controller me-2"></i>Nos Jeux de Société</h2>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filtresForm" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Nb joueurs</label>
                <select name="nb_joueurs" class="form-select form-select-sm filtre">
                    <option value="">Tous</option>
                    <option value="2">2 joueurs</option>
                    <option value="3">3 joueurs</option>
                    <option value="4">4 joueurs</option>
                    <option value="5">5 joueurs</option>
                    <option value="6">6+ joueurs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Apprentissage</label>
                <select name="difficulte_apprentissage" class="form-select form-select-sm filtre">
                    <option value="">Toutes</option>
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="difficile">Difficile</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Difficulté</label>
                <select name="difficulte_jeu" class="form-select form-select-sm filtre">
                    <option value="">Toutes</option>
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="difficile">Difficile</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Temps max</label>
                <select name="temps_max" class="form-select form-select-sm filtre">
                    <option value="">Tous</option>
                    <option value="30">≤ 30 min</option>
                    <option value="60">≤ 1h</option>
                    <option value="90">≤ 1h30</option>
                    <option value="120">≤ 2h</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Recherche</label>
                <input type="text" name="recherche" class="form-control form-control-sm filtre" placeholder="Nom du jeu...">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Trier par</label>
                <select name="tri" class="form-select form-select-sm filtre">
                    <option value="nom">Nom (A-Z)</option>
                    <option value="temps_jeu_minutes">Temps de jeu</option>
                    <option value="nb_joueurs_min">Nb joueurs</option>
                    <option value="date_ajout">Date d'ajout</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Compteur de résultats -->
<p class="text-muted mb-3" id="compteurJeux"><?= count($jeux) ?> jeu(x) trouvé(s)</p>

<!-- Grille des jeux -->
<div class="row g-4" id="grilleJeux">
    <?php foreach ($jeux as $jeu): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 card-hover">
            <div class="card-img-top bg-light text-center py-4">
                <?php if ($jeu['image'] && $jeu['image'] !== 'default_jeu.png'): ?>
                    <img src="<?= SITE_URL ?>/public/img/jeux/<?= htmlspecialchars($jeu['image']) ?>" alt="<?= htmlspecialchars($jeu['nom']) ?>" class="img-fluid" style="max-height:160px;">
                <?php else: ?>
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($jeu['nom']) ?></h5>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge bg-light text-dark"><i class="bi bi-people me-1"></i><?= $jeu['nb_joueurs_min'] ?>-<?= $jeu['nb_joueurs_max'] ?></span>
                    <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i><?= $jeu['temps_jeu_minutes'] ?> min</span>
                    <span class="badge bg-light text-dark"><i class="bi bi-mortarboard me-1"></i><?= ucfirst($jeu['difficulte_apprentissage']) ?></span>
                </div>
                <?php
                $statutBadge = ['en_stock'=>'success', 'emprunte'=>'warning', 'loue'=>'info', 'perdu'=>'danger'];
                $statutLabel = ['en_stock'=>'En stock', 'emprunte'=>'Emprunté', 'loue'=>'Loué', 'perdu'=>'Indisponible'];
                ?>
                <span class="badge bg-<?= $statutBadge[$jeu['statut']] ?? 'secondary' ?>">
                    <?= $statutLabel[$jeu['statut']] ?? $jeu['statut'] ?>
                </span>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $jeu['id'] ?>" class="btn btn-outline-primary btn-sm w-100">Voir la fiche</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($jeux)): ?>
<div class="text-center py-5 text-muted">
    <i class="bi bi-search display-4"></i>
    <p class="mt-3">Aucun jeu ne correspond à vos critères.</p>
</div>
<?php endif; ?>

<script>
$(document).ready(function() {
    // Filtrage AJAX
    $('.filtre').on('change keyup', function() {
        clearTimeout(window.filtreTimeout);
        window.filtreTimeout = setTimeout(function() {
            var params = $('#filtresForm').serialize();
            $.ajax({
                url: '<?= SITE_URL ?>/ludotheque/api',
                data: params,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#compteurJeux').text(data.count + ' jeu(x) trouvé(s)');
                        var html = '';
                        if (data.jeux.length === 0) {
                            html = '<div class="col-12 text-center py-5 text-muted"><i class="bi bi-search display-4"></i><p class="mt-3">Aucun jeu ne correspond à vos critères.</p></div>';
                        }
                        data.jeux.forEach(function(jeu) {
                            var statutBadge = {en_stock:'success', emprunte:'warning', loue:'info', perdu:'danger'};
                            var statutLabel = {en_stock:'En stock', emprunte:'Emprunté', loue:'Loué', perdu:'Indisponible'};
                            html += '<div class="col-md-6 col-lg-4">';
                            html += '<div class="card h-100 card-hover">';
                            html += '<div class="card-img-top bg-light text-center py-4"><i class="bi bi-box-seam display-1 text-muted"></i></div>';
                            html += '<div class="card-body">';
                            html += '<h5 class="card-title">' + jeu.nom + '</h5>';
                            html += '<div class="d-flex flex-wrap gap-1 mb-2">';
                            html += '<span class="badge bg-light text-dark"><i class="bi bi-people me-1"></i>' + jeu.nb_joueurs_min + '-' + jeu.nb_joueurs_max + '</span>';
                            html += '<span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>' + jeu.temps_jeu_minutes + ' min</span>';
                            html += '</div>';
                            html += '<span class="badge bg-' + (statutBadge[jeu.statut]||'secondary') + '">' + (statutLabel[jeu.statut]||jeu.statut) + '</span>';
                            html += '</div>';
                            html += '<div class="card-footer bg-transparent"><a href="<?= SITE_URL ?>/ludotheque/jeu/' + jeu.id + '" class="btn btn-outline-primary btn-sm w-100">Voir la fiche</a></div>';
                            html += '</div></div>';
                        });
                        $('#grilleJeux').html(html);
                    }
                }
            });
        }, 300);
    });
});
</script>
