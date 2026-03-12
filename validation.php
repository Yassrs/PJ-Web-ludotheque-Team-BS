<?php
/**
 * Fonctions de validation des formulaires
 */

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    return strlen($password) >= 6;
}

function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field => $label) {
        if (empty($data[$field]) || trim($data[$field]) === '') {
            $errors[] = "Le champ « {$label} » est obligatoire.";
        }
    }
    return $errors;
}

function validateImage($file) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        if ($file['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = "Erreur lors de l'upload du fichier.";
        }
        return $errors;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = "L'image ne doit pas dépasser 5 Mo.";
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS)) {
        $errors[] = "Format d'image non autorisé. Formats acceptés : " . implode(', ', ALLOWED_EXTENSIONS);
    }
    
    return $errors;
}

function uploadImage($file, $directory) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('img_', true) . '.' . $ext;
    $destination = $directory . $filename;
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    
    return null;
}
