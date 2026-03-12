<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/admin/jeux" class="list-group-item list-group-item-action"><i class="bi bi-controller me-2"></i>Gestion des Jeux</a>
            <a href="<?= SITE_URL ?>/admin/evenements" class="list-group-item list-group-item-action active"><i class="bi bi-calendar-event me-2"></i>Gestion Événements</a>
            <a href="<?= SITE_URL ?>/admin/demandes" class="list-group-item list-group-item-action"><i class="bi bi-list-check me-2"></i>Traitement Demandes</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-calendar-event me-2"></i>Gestion des Événements</h2>
            <a href="<?= SITE_URL ?>/admin/evenements/ajouter" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Titre</th><th>Date</th><th>Lieu</th><th>Catégorie</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $catLabels = ['salle_jeudi'=>'Salle du Jeudi','jeu_jeudi'=>'Jeu du Jeudi','soiree_jeux'=>'Soirée Jeux','occasionnel'=>'Occasionnel'];
                $catColors = ['salle_jeudi'=>'primary','jeu_jeudi'=>'success','soiree_jeux'=>'warning','occasionnel'=>'danger'];
                foreach ($evenements as $evt): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($evt['titre']) ?></strong></td>
                    <td><?= date('d/m/Y H:i', strtotime($evt['date_evenement'] . ' ' . $evt['heure'])) ?></td>
                    <td><?= htmlspecialchars($evt['lieu']) ?></td>
                    <td><span class="badge bg-<?= $catColors[$evt['categorie']] ?? 'secondary' ?>"><?= $catLabels[$evt['categorie']] ?? '' ?></span></td>
                    <td>
                        <a href="<?= SITE_URL ?>/admin/evenements/modifier/<?= $evt['id'] ?>" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-outline-danger btn-sm" data-delete-url="<?= SITE_URL ?>/admin/evenements/supprimer/<?= $evt['id'] ?>" data-delete-name="<?= htmlspecialchars($evt['titre']) ?>" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
