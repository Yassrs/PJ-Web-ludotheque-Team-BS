<?php
class ContactController {

    public function apropos() {
        $pageTitle = 'À Propos';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/pages/apropos.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function contact() {
        $pageTitle = 'Contact';
        require BASE_PATH . '/views/layout/header.php';
        require BASE_PATH . '/views/pages/contact.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    public function doContact() {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token invalide.');
            redirect('/contact');
        }

        $data = [
            'nom' => sanitize($_POST['nom'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'sujet' => sanitize($_POST['sujet'] ?? ''),
            'message' => sanitize($_POST['message'] ?? ''),
        ];

        $errors = validateRequired(['nom' => 'Nom', 'email' => 'Email', 'sujet' => 'Sujet', 'message' => 'Message'], $data);
        if (!validateEmail($data['email'])) {
            $errors[] = "L'email n'est pas valide.";
        }

        if (!empty($errors)) {
            setFlash('error', implode('<br>', $errors));
            redirect('/contact');
        }

        $msgModel = new MessageContact();
        $msgModel->create($data);
        setFlash('success', 'Votre message a été envoyé. Nous vous répondrons dès que possible.');
        redirect('/contact');
    }
}
