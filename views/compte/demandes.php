<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/mon-compte" class="list-group-item list-group-item-action"><i class="bi bi-person me-2"></i>Mon Profil</a>
            <a href="<?= SITE_URL ?>/mon-compte/emprunts" class="list-group-item list-group-item-action"><i class="bi bi-box-seam me-2"></i><?= isMembre() ? 'Mes Emprunts' : 'Mes Locations' ?></a>
            <a href="<?= SITE_URL ?>/mon-compte/demandes" class="list-group-item list-group-item-action active"><i class="bi bi-list-check me-2"></i>Mes Demandes</a>
            <a href="<?= SITE_URL ?>/deconnexion" class="list-group-item list-group-item-action text-danger"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4">Mes Demandes</h2>

        <?php if (empty($demandes)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-4"></i>
                <p class="mt-3">Vous n'avez aucune demande.</p>
                <a href="<?= SITE_URL ?>/ludotheque" class="btn btn-primary">Parcourir les jeux</a>
            </div>
        <?php else: ?>

        <!-- Filtres rapides côté client -->
        <div class="btn-group mb-3" role="group" id="demandeFilters">
            <button type="button" class="btn btn-outline-secondary btn-sm active" data-filter="all">Toutes (<?= count($demandes) ?>)</button>
            <?php
            $countByStatut = ['en_attente'=>0,'validee'=>0,'refusee'=>0];
            foreach ($demandes as $d) { if (isset($countByStatut[$d['statut']])) $countByStatut[$d['statut']]++; }
            ?>
            <button type="button" class="btn btn-outline-warning btn-sm" data-filter="en_attente">En attente (<?= $countByStatut['en_attente'] ?>)</button>
            <button type="button" class="btn btn-outline-success btn-sm" data-filter="validee">Validées (<?= $countByStatut['validee'] ?>)</button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-filter="refusee">Refusées (<?= $countByStatut['refusee'] ?>)</button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="demandesTable">
                <thead class="table-light">
                    <tr><th>Jeu</th><th>Type</th><th>Date début</th><th>Date retour</th><th>Statut</th><th>Demandé le</th></tr>
                </thead>
                <tbody>
                <?php
                $statutBadge = ['en_attente'=>'warning','validee'=>'success','refusee'=>'danger'];
                $statutLabel = ['en_attente'=>'En attente','validee'=>'Validée','refusee'=>'Refusée'];
                $typeLabel = ['emprunt'=>'Emprunt','location'=>'Location','reservation'=>'Réservation'];
                foreach ($demandes as $d): ?>
                <tr class="demande-row" data-statut="<?= $d['statut'] ?>">
                    <td>
                        <a href="<?= SITE_URL ?>/ludotheque/jeu/<?= $d['id_jeu'] ?>" class="text-decoration-none fw-bold">
                            <?= htmlspecialchars($d['jeu_nom']) ?>
                        </a>
                    </td>
                    <td><span class="badge bg-light text-dark"><?= $typeLabel[$d['type_demande']] ?? ucfirst($d['type_demande']) ?></span></td>
                    <td><?= date('d/m/Y', strtotime($d['date_debut'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                    <td>
                        <span class="badge bg-<?= $statutBadge[$d['statut']] ?? 'secondary' ?>"><?= $statutLabel[$d['statut']] ?? $d['statut'] ?></span>
                        <?php if ($d['statut'] === 'refusee' && !empty($d['motif_refus'])): ?>
                            <br><small class="text-danger"><i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($d['motif_refus']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($d['date_demande'])) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script de filtrage rapide des demandes -->
<script>
$(document).ready(function() {
    $('#demandeFilters button').on('click', function() {
        var filter = $(this).data('filter');
        $('#demandeFilters button').removeClass('active');
        $(this).addClass('active');

        if (filter === 'all') {
            $('.demande-row').show();
        } else {
            $('.demande-row').hide();
            $('.demande-row[data-statut="' + filter + '"]').show();
        }
    });
});
</script>
