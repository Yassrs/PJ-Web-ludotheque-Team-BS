<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/admin/jeux" class="list-group-item list-group-item-action active"><i class="bi bi-controller me-2"></i>Gestion des Jeux</a>
            <a href="<?= SITE_URL ?>/admin/evenements" class="list-group-item list-group-item-action"><i class="bi bi-calendar-event me-2"></i>Gestion Événements</a>
            <a href="<?= SITE_URL ?>/admin/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Traitement Demandes</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-controller me-2"></i>Gestion des Jeux</h2>
            <a href="<?= SITE_URL ?>/admin/jeux/ajouter" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter un jeu</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Nom</th><th>Joueurs</th><th>Temps</th><th>Difficulté</th><th>Statut</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $statutBadge = ['en_stock'=>'success','emprunte'=>'warning','loue'=>'info','perdu'=>'danger'];
                $statutLabel = ['en_stock'=>'En stock','emprunte'=>'Emprunté','loue'=>'Loué','perdu'=>'Perdu'];
                foreach ($jeux as $jeu): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($jeu['nom']) ?></strong></td>
                    <td><?= $jeu['nb_joueurs_min'] ?>-<?= $jeu['nb_joueurs_max'] ?></td>
                    <td><?= $jeu['temps_jeu_minutes'] ?> min</td>
                    <td><?= ucfirst($jeu['difficulte_jeu']) ?></td>
                    <td><span class="badge bg-<?= $statutBadge[$jeu['statut']] ?>"><?= $statutLabel[$jeu['statut']] ?></span></td>
                    <td>
                        <a href="<?= SITE_URL ?>/admin/jeux/modifier/<?= $jeu['id'] ?>" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-outline-danger btn-sm" data-delete-url="<?= SITE_URL ?>/admin/jeux/supprimer/<?= $jeu['id'] ?>" data-delete-name="<?= htmlspecialchars($jeu['nom']) ?>" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
