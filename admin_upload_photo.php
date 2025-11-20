<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Non authentifié'
    ]);
    exit();
}

// Fonction de réponse JSON
function sendResponse($success, $message, $data = null, $httpCode = 200)
{
    http_response_code($httpCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('c')
    ]);
    exit();
}

// Fonction de logging sécurisé
function logUpload($message, $level = 'INFO')
{
    $logFile = __DIR__ . '/admin_uploads.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user = $_SESSION['admin_username'] ?? 'unknown';
    $logEntry = "[$timestamp] [$level] [User: $user] [IP: $ip] $message" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Charger la configuration
$configPath = __DIR__ . '/admin_config.json';
if (!file_exists($configPath)) {
    logUpload('Configuration manquante', 'ERROR');
    sendResponse(false, 'Erreur de configuration', null, 500);
}

$config = json_decode(file_get_contents($configPath), true);
if (!$config || !isset($config['upload'])) {
    logUpload('Configuration invalide', 'ERROR');
    sendResponse(false, 'Erreur de configuration', null, 500);
}

// Vérifier la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logUpload('Méthode HTTP non autorisée: ' . $_SERVER['REQUEST_METHOD'], 'WARNING');
    sendResponse(false, 'Seule la méthode POST est autorisée', null, 405);
}

// Vérifier si un fichier a été envoyé
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
    logUpload('Aucun fichier envoyé', 'WARNING');
    sendResponse(false, 'Aucun fichier n\'a été envoyé', null, 400);
}

$file = $_FILES['photo'];

// Vérifier les erreurs d'upload
switch ($file['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
        logUpload('Fichier trop volumineux: ' . $file['name'], 'WARNING');
        sendResponse(false, 'Le fichier est trop volumineux', null, 400);
    case UPLOAD_ERR_PARTIAL:
        logUpload('Upload partiel: ' . $file['name'], 'ERROR');
        sendResponse(false, 'Le fichier n\'a été que partiellement téléchargé', null, 400);
    default:
        logUpload('Erreur upload inconnue: ' . $file['error'], 'ERROR');
        sendResponse(false, 'Erreur lors de l\'upload du fichier', null, 500);
}

// Vérifier la taille du fichier
if ($file['size'] > $config['upload']['max_file_size']) {
    $maxSizeMB = round($config['upload']['max_file_size'] / 1024 / 1024, 1);
    $fileSizeMB = round($file['size'] / 1024 / 1024, 1);
    logUpload("Fichier trop volumineux: {$file['name']} ({$fileSizeMB} Mo > {$maxSizeMB} Mo)", 'WARNING');
    sendResponse(false, "Le fichier est trop volumineux ({$fileSizeMB} Mo). Maximum autorisé: {$maxSizeMB} Mo", null, 400);
}

// Vérifier l'extension du fichier
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $config['upload']['allowed_extensions'])) {
    logUpload("Extension non autorisée: {$file['name']} (.{$extension})", 'WARNING');
    sendResponse(false, "Extension de fichier non autorisée. Formats acceptés: " . implode(', ', $config['upload']['allowed_extensions']), null, 400);
}

// Vérifier le type MIME réel du fichier
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowedMimeTypes = [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp'
];

if (!in_array($mimeType, $allowedMimeTypes)) {
    logUpload("Type MIME non autorisé: {$file['name']} ({$mimeType})", 'WARNING');
    sendResponse(false, 'Le fichier n\'est pas une image valide', null, 400);
}

// Vérifier que c'est bien une image en essayant de la lire
$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    logUpload("Fichier corrompu ou non-image: {$file['name']}", 'WARNING');
    sendResponse(false, 'Le fichier n\'est pas une image valide', null, 400);
}

// Sécuriser le nom du fichier
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
$safeName = substr($safeName, 0, 100); // Limiter la longueur

// Ajouter un timestamp pour éviter les collisions
$finalName = $safeName . '.' . $extension;

// Créer le dossier de destination s'il n'existe pas
$uploadDir = __DIR__ . '/' . $config['upload']['upload_directory'];
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        logUpload('Impossible de créer le dossier photos', 'ERROR');
        sendResponse(false, 'Erreur de configuration serveur', null, 500);
    }
}

// Chemin complet du fichier de destination
$destinationPath = $uploadDir . '/' . $finalName;

// Déplacer le fichier uploadé
if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
    logUpload("Échec du déplacement du fichier: {$file['name']}", 'ERROR');
    sendResponse(false, 'Erreur lors de l\'enregistrement du fichier', null, 500);
}

// Définir les permissions du fichier
chmod($destinationPath, 0644);

// Log de succès
$fileSize = round(filesize($destinationPath) / 1024, 2);
logUpload("Photo uploadée avec succès: {$finalName} ({$fileSize} Ko)", 'SUCCESS');

// Réponse de succès
sendResponse(true, 'Photo envoyée avec succès', [
    'filename' => $finalName,
    'original_name' => $file['name'],
    'size' => filesize($destinationPath),
    'size_kb' => $fileSize,
    'path' => $config['upload']['upload_directory'] . '/' . $finalName,
    'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1]
], 200);
