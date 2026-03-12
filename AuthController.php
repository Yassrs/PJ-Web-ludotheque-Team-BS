<?php
class AuthController {
    
    public function login() {
        if (isLoggedIn()) redirect('/mon-compte');
        $pageTitle = 'Connexion';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/auth/login.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doLogin() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de sécurité invalide.');
            redirect('/connexion');
        }

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['mot_de_passe'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Veuillez remplir tous les champs.');
            redirect('/connexion');
        }

        $userModel = new Utilisateur();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            loginUser($user);
            setFlash('success', 'Bienvenue, ' . $user['prenom'] . ' !');
            
            // Redirection selon le rôle
            if (isAdmin() || isPresident()) {
                redirect('/admin');
            } else {
                redirect('/mon-compte');
            }
        } else {
            setFlash('error', 'Email ou mot de passe incorrect.');
            redirect('/connexion');
        }
    }

    public function register() {
        if (isLoggedIn()) redirect('/mon-compte');
        $pageTitle = 'Inscription';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/auth/register.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doRegister() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de sécurité invalide.');
            redirect('/inscription');
        }

        $data = [
            'nom' => sanitize($_POST['nom'] ?? ''),
            'prenom' => sanitize($_POST['prenom'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'mot_de_passe' => $_POST['mot_de_passe'] ?? '',
        ];
        $confirm = $_POST['confirm_mot_de_passe'] ?? '';

        // Validation
        $errors = validateRequired(
            ['nom' => 'Nom', 'prenom' => 'Prénom', 'email' => 'Email', 'mot_de_passe' => 'Mot de passe'],
            $data
        );

        if (!validateEmail($data['email'])) {
            $errors[] = "L'adresse email n'est pas valide.";
        }
        if (!validatePassword($data['mot_de_passe'])) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        if ($data['mot_de_passe'] !== $confirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        $userModel = new Utilisateur();
        if ($userModel->emailExists($data['email'])) {
            $errors[] = "Cette adresse email est déjà utilisée.";
        }

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect('/inscription');
        }

        $data['role'] = ROLE_NON_MEMBRE;
        $userId = $userModel->create($data);

        if ($userId) {
            $user = $userModel->findById($userId);
            loginUser($user);
            setFlash('success', 'Votre compte a été créé avec succès ! Bienvenue, ' . $data['prenom'] . ' !');
            redirect('/mon-compte');
        } else {
            setFlash('error', "Une erreur est survenue lors de l'inscription.");
            redirect('/inscription');
        }
    }

    public function logout() {
        logoutUser();
        setFlash('success', 'Vous avez été déconnecté.');
        redirect('/');
    }
}
