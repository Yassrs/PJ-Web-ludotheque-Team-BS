<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/admin/jeux" class="list-group-item list-group-item-action"><i class="bi bi-controller me-2"></i>Gestion des Jeux</a>
            <a href="<?= SITE_URL ?>/admin/evenements" class="list-group-item list-group-item-action"><i class="bi bi-calendar-event me-2"></i>Gestion Événements</a>
            <a href="<?= SITE_URL ?>/admin/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Traitement Demandes</a>
            <a href="<?= SITE_URL ?>/president" class="list-group-item list-group-item-action active text-warning"><i class="bi bi-star-fill me-2"></i>Gestion Bureau</a>
            <a href="<?= SITE_URL ?>/president/infos" class="list-group-item list-group-item-action"><i class="bi bi-info-circle me-2"></i>Infos Internes</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4"><i class="bi bi-star-fill me-2 text-warning"></i>Gestion du Bureau</h2>

        <!-- Countdown mandat -->
        <?php if (isset($joursRestants)): ?>
        <div class="card bg-light border-warning mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-warning mb-0">Fin du mandat</h6>
                    <small class="text-muted"><?= $dateFin ? date('d/m/Y', strtotime($dateFin)) : 'Non définie' ?></small>
                </div>
                <div class="text-end">
                    <span class="fs-2 fw-bold text-dark"><?= $joursRestants ?></span>
                    <span class="text-muted d-block small">jours restants</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Membres du bureau -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Membres du Bureau</h4>
            <a href="<?= SITE_URL ?>/president/ajouter-admin" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter un admin</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Nom</th><th>Email</th><th>Rôle Bureau</th><th>Rôle Système</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $roleBureauLabels = ['president'=>'Président(e)','vice_president'=>'Vice-Président(e)','tresorier'=>'Trésorier(ère)','secretaire'=>'Secrétaire','responsable_jeux'=>'Resp. Jeux','responsable_comm'=>'Resp. Comm.'];
                foreach ($membres as $m): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></strong></td>
                    <td class="text-muted small"><?= htmlspecialchars($m['email']) ?></td>
                    <td><?= $roleBureauLabels[$m['role_bureau']] ?? $m['role_bureau'] ?></td>
                    <td><span class="badge bg-<?= $m['role'] === 'president' ? 'warning' : 'info' ?>"><?= ucfirst($m['role']) ?></span></td>
                    <td>
                        <?php if ($m['role'] !== 'president'): ?>
                        <button class="btn btn-outline-danger btn-sm"
                                data-delete-url="<?= SITE_URL ?>/president/retirer-admin/<?= $m['id_utilisateur'] ?>"
                                data-delete-name="<?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?> du bureau">
                            <i class="bi bi-person-x"></i> Retirer
                        </button>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
