/**
 * Ludothèque — Scripts principaux
 * Projet Web APP 2026 - ECE ING3
 * Technologies : jQuery 3.7, Bootstrap 5.3, AJAX
 */

$(document).ready(function() {

    // ========================================================
    // 1. FLASH MESSAGES — Auto-dismiss avec animation
    // ========================================================
    $('.alert:not(.alert-permanent)').each(function() {
        var $alert = $(this);
        setTimeout(function() {
            $alert.fadeOut(600, function() { $(this).remove(); });
        }, 5000);
    });

    // ========================================================
    // 2. MODAL DE CONFIRMATION DE SUPPRESSION
    //    Remplace les confirm() natifs par une vraie modal Bootstrap
    //    Usage : data-delete-url="/admin/jeux/supprimer/3" data-delete-name="Catan"
    // ========================================================
    $(document.body).on('click', '[data-delete-url]', function(e) {
        e.preventDefault();
        var url = $(this).data('delete-url');
        var name = $(this).data('delete-name') || 'cet élément';

        $('#deleteModalName').text(name);
        $('#deleteModalConfirmBtn').attr('href', url);
        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    });

    // ========================================================
    // 3. MODAL DE REFUS DE DEMANDE (avec motif)
    //    Usage : data-refuse-url="..." data-refuse-jeu="Catan" data-refuse-user="Thomas Petit"
    // ========================================================
    $(document.body).on('click', '[data-refuse-id]', function(e) {
        e.preventDefault();
        var jeu = $(this).data('refuse-jeu') || '';
        var user = $(this).data('refuse-user') || '';

        $('#refuseModalJeu').text(jeu);
        $('#refuseModalUser').text(user);
        $('#refuseModalForm').attr('action', $(this).data('refuse-url'));
        $('#refuseMotif').val('');
        var modal = new bootstrap.Modal(document.getElementById('refuseDemandeModal'));
        modal.show();
    });

    // ========================================================
    // 4. VALIDATION TEMPS RÉEL — Formulaire d'inscription
    // ========================================================
    var $registerForm = $('form[action*="inscription"]');
    if ($registerForm.length) {

        // Validation email en temps réel
        $registerForm.find('[name="email"]').on('blur', function() {
            var $input = $(this);
            var val = $input.val().trim();
            var $feedback = $input.next('.invalid-feedback');

            if (!$feedback.length) {
                $feedback = $('<div class="invalid-feedback"></div>').insertAfter($input);
            }

            if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                $input.addClass('is-invalid').removeClass('is-valid');
                $feedback.text('Adresse email invalide.');
            } else if (val) {
                $input.addClass('is-valid').removeClass('is-invalid');
            } else {
                $input.removeClass('is-valid is-invalid');
            }
        });

        // Indicateur de force du mot de passe
        $registerForm.find('[name="mot_de_passe"]').on('input', function() {
            var val = $(this).val();
            var $bar = $('#passwordStrengthBar');
            var $text = $('#passwordStrengthText');

            if (!$bar.length) {
                $(this).after(
                    '<div class="mt-1">' +
                    '<div class="progress" style="height:4px;">' +
                    '<div id="passwordStrengthBar" class="progress-bar" style="width:0%"></div>' +
                    '</div>' +
                    '<small id="passwordStrengthText" class="text-muted"></small></div>'
                );
                $bar = $('#passwordStrengthBar');
                $text = $('#passwordStrengthText');
            }

            var strength = 0;
            if (val.length >= 6) strength += 25;
            if (val.length >= 10) strength += 25;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength += 25;
            if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) strength += 25;

            var color = strength <= 25 ? 'bg-danger' : strength <= 50 ? 'bg-warning' : strength <= 75 ? 'bg-info' : 'bg-success';
            var label = strength <= 25 ? 'Faible' : strength <= 50 ? 'Moyen' : strength <= 75 ? 'Bon' : 'Fort';

            $bar.css('width', strength + '%').removeClass('bg-danger bg-warning bg-info bg-success').addClass(color);
            $text.text(val.length > 0 ? 'Force : ' + label : '');
        });

        // Confirmation mot de passe en temps réel
        $registerForm.find('[name="confirm_mot_de_passe"]').on('input', function() {
            var pwd = $registerForm.find('[name="mot_de_passe"]').val();
            var confirmVal = $(this).val();
            var $feedback = $(this).next('.invalid-feedback');

            if (!$feedback.length) {
                $feedback = $('<div class="invalid-feedback"></div>').insertAfter($(this));
            }

            if (confirmVal && pwd !== confirmVal) {
                $(this).addClass('is-invalid').removeClass('is-valid');
                $feedback.text('Les mots de passe ne correspondent pas.');
            } else if (confirmVal && pwd === confirmVal) {
                $(this).addClass('is-valid').removeClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        // Bloquer soumission si erreurs
        $registerForm.on('submit', function(e) {
            var pwd = $(this).find('[name="mot_de_passe"]').val();
            var confirmVal = $(this).find('[name="confirm_mot_de_passe"]').val();
            if (pwd && confirmVal && pwd !== confirmVal) {
                e.preventDefault();
                $(this).find('[name="confirm_mot_de_passe"]').addClass('is-invalid').focus();
                return false;
            }
            if (pwd && pwd.length < 6) {
                e.preventDefault();
                $(this).find('[name="mot_de_passe"]').addClass('is-invalid').focus();
                return false;
            }
        });
    }

    // ========================================================
    // 5. RECHERCHE LIVE — Page événements publique
    //    Filtrage côté client instantané
    // ========================================================
    var $eventSearch = $('#eventSearchInput');
    if ($eventSearch.length) {
        $eventSearch.on('keyup', function() {
            var query = $(this).val().toLowerCase().trim();
            var $cards = $('.event-card');
            var visibleCount = 0;

            $cards.each(function() {
                var titre = ($(this).data('titre') || '').toString().toLowerCase();
                var lieu = ($(this).data('lieu') || '').toString().toLowerCase();
                var desc = ($(this).data('desc') || '').toString().toLowerCase();
                var match = !query || titre.indexOf(query) !== -1 || lieu.indexOf(query) !== -1 || desc.indexOf(query) !== -1;

                $(this).toggle(match);
                if (match) visibleCount++;
            });

            $('#eventCount').text(visibleCount + ' événement(s)');

            $('#noEventResult').remove();
            if (visibleCount === 0 && query) {
                $('.event-grid').append(
                    '<div id="noEventResult" class="col-12 text-center py-4 text-muted">' +
                    '<i class="bi bi-search display-5"></i>' +
                    '<p class="mt-2">Aucun événement ne correspond à votre recherche.</p></div>'
                );
            }
        });
    }

    // ========================================================
    // 6. TOOLTIP BOOTSTRAP
    // ========================================================
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(el) {
        return new bootstrap.Tooltip(el);
    });

    // ========================================================
    // 7. NAV ACTIVE HIGHLIGHTING
    // ========================================================
    var currentPath = window.location.pathname;
    $('.navbar-nav .nav-link').each(function() {
        var href = $(this).attr('href');
        if (href && currentPath.indexOf(href.replace(/^.*\/\/[^\/]+/, '')) === 0) {
            $(this).addClass('active');
        }
    });

    // ========================================================
    // 8. CARROUSELS ÉVÉNEMENTS — Pause au survol + Swipe tactile
    // ========================================================
    $('[id^="carousel-"]').each(function() {
        var $carousel = $(this);
        $carousel.closest('.card').on('mouseenter', function() {
            $carousel.carousel('pause');
        }).on('mouseleave', function() {
            $carousel.carousel('cycle');
        });
    });

    $('[id^="carousel-"]').on('touchstart', function(e) {
        var startX = e.originalEvent.touches[0].clientX;
        $(this).one('touchend', function(e2) {
            var endX = e2.originalEvent.changedTouches[0].clientX;
            var diff = startX - endX;
            if (Math.abs(diff) > 50) {
                $(this).carousel(diff > 0 ? 'next' : 'prev');
            }
        });
    });

    // ========================================================
    // 9. BOUTON SCROLL-TO-TOP
    // ========================================================
    var $scrollBtn = $(
        '<button id="scrollTopBtn" class="btn btn-primary rounded-circle shadow" ' +
        'style="position:fixed;bottom:20px;right:20px;width:44px;height:44px;display:none;z-index:999;opacity:0.8;" ' +
        'title="Retour en haut">' +
        '<i class="bi bi-arrow-up"></i></button>'
    );
    $('body').append($scrollBtn);

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $scrollBtn.fadeIn(300);
        } else {
            $scrollBtn.fadeOut(300);
        }
    });

    $scrollBtn.on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 400);
    });

    // ========================================================
    // 10. ANIMATION D'ENTRÉE — Cards apparaissent au scroll
    // ========================================================
    var $animCards = $('.card-hover');
    if ($animCards.length && 'IntersectionObserver' in window) {
        $animCards.css({
            opacity: 0,
            transform: 'translateY(20px)',
            transition: 'opacity 0.4s ease, transform 0.4s ease'
        });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    $(entry.target).css({ opacity: 1, transform: 'translateY(0)' });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        $animCards.each(function() { observer.observe(this); });
    }

});
