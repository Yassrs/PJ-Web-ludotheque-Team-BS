<!-- Hero Section -->
<div class="bg-primary text-white rounded-4 p-5 mb-5 text-center hero-section">
    <h1 class="display-5 fw-bold mb-3"><i class="bi bi-dice-5-fill me-2"></i>Bienvenue à la Ludo<span style="color:#FFD93D;">thèque</span></h1>
    <p class="lead mb-4">L'association étudiante qui anime votre campus avec des jeux de société, des événements et bien plus encore !</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= SITE_URL ?>/ludotheque" class="btn btn-light btn-lg"><i class="bi bi-controller me-2"></i>Découvrir nos jeux</a>
        <a href="<?= SITE_URL ?>/evenements" class="btn btn-outline-light btn-lg"><i class="bi bi-calendar-event me-2"></i>Voir les événements</a>
    </div>
</div>

<!-- Événements par catégorie — Carrousels -->
<h2 class="mb-4"><i class="bi bi-calendar-event me-2"></i>Nos Événements</h2>
<div class="row g-4 mb-5">
    <?php
    $categories = [
        'salle_jeudi' => ['label' => 'Salle du Jeudi', 'icon' => 'bi-door-open', 'color' => 'primary', 'events' => $salleJeudi],
        'jeu_jeudi' => ['label' => 'Jeu du Jeudi', 'icon' => 'bi-joystick', 'color' => 'success', 'events' => $jeuJeudi],
        'soiree_jeux' => ['label' => 'Soirée Jeux', 'icon' => 'bi-moon-stars', 'color' => 'warning', 'events' => $soireeJeux],
        'occasionnel' => ['label' => 'Événement Occasionnel', 'icon' => 'bi-star', 'color' => 'danger', 'events' => $occasionnel],
    ];
    foreach ($categories as $key => $cat):
        $events = $cat['events'];
        $carouselId = 'carousel-' . $key;
    ?>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-<?= $cat['color'] ?> card-hover position-relative">
            <!-- Badge catégorie en overlay -->
            <span class="badge bg-<?= $cat['color'] ?> position-absolute top-0 start-0 m-2 z-1"><?= $cat['label'] ?></span>

            <?php if (!empty($events)): ?>
                <?php if (count($events) > 1): ?>
                <!-- CARROUSEL : plusieurs événements -->
                <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    <!-- Indicateurs -->
                    <div class="carousel-indicators">
                        <?php for ($i = 0; $i < count($events); $i++): ?>
                            <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $i ?>"
                                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                                aria-label="Événement <?= $i + 1 ?>">
                            </button>
                        <?php endfor; ?>
                    </div>

                    <div class="carousel-inner">
                        <?php foreach ($events as $idx => $event): ?>
                        <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                            <div class="bg-<?= $cat['color'] ?> bg-opacity-10 text-center py-4">
                                <?php if ($event['image'] && $event['image'] !== 'default_event.png'): ?>
                                    <img src="<?= SITE_URL ?>/public/img/evenements/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['titre']) ?>" class="img-fluid" style="max-height:120px;">
                                <?php else: ?>
                                    <i class="bi <?= $cat['icon'] ?> display-3 text-<?= $cat['color'] ?>"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title mb-1"><?= htmlspecialchars($event['titre']) ?></h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($event['date_evenement'])) ?>
                                    à <?= date('H\hi', strtotime($event['heure'])) ?><br>
                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($event['lieu']) ?>
                                </p>
                                <p class="card-text small text-truncate-3"><?= htmlspecialchars(mb_substr($event['description'], 0, 80)) ?>...</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?= SITE_URL ?>/evenements/<?= $event['id'] ?>" class="btn btn-outline-<?= $cat['color'] ?> btn-sm w-100">Voir les détails</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Flèches de navigation -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-<?= $cat['color'] ?> rounded-circle p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-<?= $cat['color'] ?> rounded-circle p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>

                <?php else: ?>
                <!-- UNIQUE : un seul événement, pas de carrousel -->
                <?php $event = $events[0]; ?>
                <div class="bg-<?= $cat['color'] ?> bg-opacity-10 text-center py-4">
                    <?php if ($event['image'] && $event['image'] !== 'default_event.png'): ?>
                        <img src="<?= SITE_URL ?>/public/img/evenements/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['titre']) ?>" class="img-fluid" style="max-height:120px;">
                    <?php else: ?>
                        <i class="bi <?= $cat['icon'] ?> display-3 text-<?= $cat['color'] ?>"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1"><?= htmlspecialchars($event['titre']) ?></h6>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($event['date_evenement'])) ?>
                        à <?= date('H\hi', strtotime($event['heure'])) ?><br>
                        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($event['lieu']) ?>
                    </p>
                    <p class="card-text small"><?= htmlspecialchars(mb_substr($event['description'], 0, 80)) ?>...</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?= SITE_URL ?>/evenements/<?= $event['id'] ?>" class="btn btn-outline-<?= $cat['color'] ?> btn-sm w-100">Voir les détails</a>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- AUCUN événement -->
                <div class="bg-light text-center py-4">
                    <i class="bi <?= $cat['icon'] ?> display-3 text-muted"></i>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-0">Aucun événement prévu</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Présentation -->
<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <h2 class="mb-3"><i class="bi bi-info-circle me-2"></i>Notre Association</h2>
        <p>Notre association étudiante propose une ludothèque de jeux de société, accessible à tous les étudiants du campus. Que vous soyez membre ou non, venez découvrir nos jeux, participer à nos événements et rejoindre notre communauté !</p>
        <div class="row g-3 mb-3">
            <div class="col-sm-4">
                <div class="card text-center p-3 bg-primary bg-opacity-10 border-0">
                    <i class="bi bi-person-check fs-3 text-primary"></i>
                    <strong class="mt-1">Membres</strong>
                    <small class="text-muted">Emprunt gratuit 1-2 sem.</small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center p-3 bg-warning bg-opacity-10 border-0">
                    <i class="bi bi-cart fs-3 text-warning"></i>
                    <strong class="mt-1">Non-membres</strong>
                    <small class="text-muted">Location à petit prix</small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center p-3 bg-success bg-opacity-10 border-0">
                    <i class="bi bi-calendar-check fs-3 text-success"></i>
                    <strong class="mt-1">Tous</strong>
                    <small class="text-muted">Réservation Jeu du Jeudi</small>
                </div>
            </div>
        </div>
        <a href="<?= SITE_URL ?>/a-propos" class="btn btn-outline-primary">En savoir plus sur l'association</a>
    </div>
    <div class="col-lg-4">
        <div class="card border-0" style="background: linear-gradient(135deg, var(--primary-light, #EDE7FB) 0%, #fff 100%);">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-chat-dots display-5 text-primary"></i>
                </div>
                <h5 class="card-title">Contactez-nous</h5>
                <p class="mb-2"><i class="bi bi-envelope me-1"></i> contact@ludotheque.fr</p>
                <div class="d-flex gap-2 justify-content-center mb-3">
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-instagram me-1"></i>Instagram</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-discord me-1"></i>Discord</a>
                </div>
                <a href="<?= SITE_URL ?>/contact" class="btn btn-primary btn-sm w-100">Nous écrire</a>
            </div>
        </div>
    </div>
</div>
