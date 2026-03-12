<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?= SITE_URL ?>/admin" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="<?= SITE_URL ?>/president" class="list-group-item list-group-item-action"><i class="bi bi-star-fill me-2"></i>Gestion Bureau</a>
            <a href="<?= SITE_URL ?>/president/infos" class="list-group-item list-group-item-action active"><i class="bi bi-info-circle me-2"></i>Infos Internes</a>
        </div>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4"><i class="bi bi-info-circle me-2"></i>Informations Internes</h2>
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Membres actuels du bureau</h5></div>
            <div class="card-body">
                <?php
                $roleBureauLabels = ['president'=>'Président(e)','vice_president'=>'Vice-Président(e)','tresorier'=>'Trésorier(ère)','secretaire'=>'Secrétaire','responsable_jeux'=>'Resp. Jeux','responsable_comm'=>'Resp. Comm.'];
                foreach ($membres as $m): ?>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></strong>
                        <span class="text-muted ms-2"><?= htmlspecialchars($m['email']) ?></span>
                    </div>
                    <span class="badge bg-primary"><?= $roleBureauLabels[$m['role_bureau']] ?? $m['role_bureau'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if ($dateFin): 
            $diff = (new DateTime())->diff(new DateTime($dateFin));
            $jours = $diff->invert ? 0 : $diff->days;
        ?>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Mandat en cours</h5></div>
            <div class="card-body text-center">
                <div class="display-4 fw-bold text-primary"><?= $jours ?></div>
                <p class="text-muted">jours restants avant la fin du mandat</p>
                <p>Date de fin : <strong><?= date('d/m/Y', strtotime($dateFin)) ?></strong></p>
                <div class="progress" style="height:20px;">
                    <?php 
                    $total = 365;
                    $pct = max(0, min(100, round((($total - $jours) / $total) * 100)));
                    ?>
                    <div class="progress-bar bg-warning" style="width:<?= $pct ?>%"><?= $pct ?>%</div>
                </div>
                <small class="text-muted mt-2 d-block"><?= $pct ?>% du mandat écoulé</small>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
