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

// Fonction de validation des images
function validateImage($tmpFile)
{
    $imageInfo = @getimagesize($tmpFile);
    if ($imageInfo === false) {
        logUpload("Fichier corrompu ou non-image", 'WARNING');
        sendResponse(false, 'Le fichier n\'est pas une image valide', null, 400);
    }
    return $imageInfo;
}

// Fonction de validation des vidéos
function validateVideo($tmpFile, $extension)
{
    // Lecture des 12 premiers bytes pour vérifier les magic numbers
    $handle = fopen($tmpFile, 'rb');
    if ($handle === false) {
        logUpload("Impossible d'ouvrir le fichier vidéo pour validation", 'ERROR');
        sendResponse(false, 'Erreur lors de la validation du fichier', null, 500);
    }

    $header = fread($handle, 12);
    fclose($handle);

    $valid = false;
    switch ($extension) {
        case 'mp4':
        case 'mov':
            // Check for 'ftyp' signature at bytes 4-7
            $valid = (substr($header, 4, 4) === 'ftyp');
            break;
        case 'webm':
            // Check for EBML header (1A 45 DF A3)
            $valid = (bin2hex(substr($header, 0, 4)) === '1a45dfa3');
            break;
        case 'avi':
            // Check for 'RIFF' header and 'AVI ' signature
            $valid = (substr($header, 0, 4) === 'RIFF' && substr($header, 8, 4) === 'AVI ');
            break;
    }

    if (!$valid) {
        logUpload("Fichier vidéo corrompu ou invalide: format $extension non reconnu", 'WARNING');
        sendResponse(false, 'Le fichier vidéo n\'est pas valide', null, 400);
    }

    return true;
}

// Fonction de validation des PDF
function validatePDF($tmpFile)
{
    // Vérifier header %PDF-
    $handle = fopen($tmpFile, 'rb');
    if ($handle === false) {
        logUpload("Impossible d'ouvrir le fichier PDF pour validation", 'ERROR');
        sendResponse(false, 'Erreur lors de la validation du fichier', null, 500);
    }

    $header = fread($handle, 5);

    // Lire les derniers 1024 bytes pour le footer
    fseek($handle, -1024, SEEK_END);
    $footer = fread($handle, 1024);
    fclose($handle);

    // Check for %PDF- header
    if (substr($header, 0, 5) !== '%PDF-') {
        logUpload("En-tête PDF manquant", 'WARNING');
        sendResponse(false, 'Le fichier n\'est pas un PDF valide', null, 400);
    }

    // Check for %%EOF footer
    if (strpos($footer, '%%EOF') === false) {
        logUpload("Pied de page PDF manquant (%%EOF)", 'WARNING');
        sendResponse(false, 'Le fichier PDF est corrompu', null, 400);
    }

    return true;
}

// Charger la configuration
$configPath = __DIR__ . '/admin_config.json';
if (!file_exists($configPath)) {
    logUpload('Configuration manquante', 'ERROR');
    sendResponse(false, 'Erreur de configuration', null, 500);
}

$config = json_decode(file_get_contents($configPath), true);
if (!$config || !isset($config['upload_types'])) {
    logUpload('Configuration invalide', 'ERROR');
    sendResponse(false, 'Erreur de configuration', null, 500);
}

// Vérifier la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logUpload('Méthode HTTP non autorisée: ' . $_SERVER['REQUEST_METHOD'], 'WARNING');
    sendResponse(false, 'Seule la méthode POST est autorisée', null, 405);
}

// Récupérer le type d'upload
$uploadType = $_POST['upload_type'] ?? 'photos';

// Valider que le type d'upload existe dans la configuration
if (!isset($config['upload_types'][$uploadType])) {
    logUpload("Type d'upload invalide: $uploadType", 'WARNING');
    sendResponse(false, 'Type d\'upload invalide', null, 400);
}

$uploadConfig = $config['upload_types'][$uploadType];

// Vérifier si un fichier a été envoyé
if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
    logUpload("[$uploadType] Aucun fichier envoyé", 'WARNING');
    sendResponse(false, 'Aucun fichier n\'a été envoyé', null, 400);
}

$file = $_FILES['file'];

// Vérifier les erreurs d'upload
switch ($file['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
        logUpload("[$uploadType] Fichier trop volumineux: " . $file['name'], 'WARNING');
        sendResponse(false, 'Le fichier est trop volumineux', null, 400);
    case UPLOAD_ERR_PARTIAL:
        logUpload("[$uploadType] Upload partiel: " . $file['name'], 'ERROR');
        sendResponse(false, 'Le fichier n\'a été que partiellement téléchargé', null, 400);
    default:
        logUpload("[$uploadType] Erreur upload inconnue: " . $file['error'], 'ERROR');
        sendResponse(false, 'Erreur lors de l\'upload du fichier', null, 500);
}

// Vérifier la taille du fichier
if ($file['size'] > $uploadConfig['max_file_size']) {
    $maxSizeMB = round($uploadConfig['max_file_size'] / 1024 / 1024, 1);
    $fileSizeMB = round($file['size'] / 1024 / 1024, 1);
    logUpload("[$uploadType] Fichier trop volumineux: {$file['name']} ({$fileSizeMB} Mo > {$maxSizeMB} Mo)", 'WARNING');
    sendResponse(false, "Le fichier est trop volumineux ({$fileSizeMB} Mo). Maximum autorisé: {$maxSizeMB} Mo", null, 400);
}

// Vérifier l'extension du fichier
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $uploadConfig['allowed_extensions'])) {
    logUpload("[$uploadType] Extension non autorisée: {$file['name']} (.{$extension})", 'WARNING');
    sendResponse(false, "Extension de fichier non autorisée. Formats acceptés: " . implode(', ', $uploadConfig['allowed_extensions']), null, 400);
}

// Vérifier le type MIME réel du fichier
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $uploadConfig['mime_types'])) {
    logUpload("[$uploadType] Type MIME non autorisé: {$file['name']} ({$mimeType})", 'WARNING');
    sendResponse(false, 'Le type de fichier n\'est pas valide', null, 400);
}

// Validation spécifique selon le type de fichier
$validationResult = null;
switch ($uploadConfig['validation_type']) {
    case 'image':
        $validationResult = validateImage($file['tmp_name']);
        break;
    case 'video':
        validateVideo($file['tmp_name'], $extension);
        break;
    case 'pdf':
        validatePDF($file['tmp_name']);
        break;
    default:
        logUpload("[$uploadType] Type de validation inconnu: " . $uploadConfig['validation_type'], 'ERROR');
        sendResponse(false, 'Erreur de configuration de validation', null, 500);
}

// Sécuriser le nom du fichier
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
$safeName = substr($safeName, 0, 100); // Limiter la longueur

// Nom final
$finalName = $safeName . '.' . $extension;

// Créer le dossier de destination s'il n'existe pas
$uploadDir = __DIR__ . '/' . $uploadConfig['upload_directory'];
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        logUpload("[$uploadType] Impossible de créer le dossier " . $uploadConfig['upload_directory'], 'ERROR');
        sendResponse(false, 'Erreur de configuration serveur', null, 500);
    }
}

// Chemin complet du fichier de destination
$destinationPath = $uploadDir . '/' . $finalName;

// Déplacer le fichier uploadé
if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
    logUpload("[$uploadType] Échec du déplacement du fichier: {$file['name']}", 'ERROR');
    sendResponse(false, 'Erreur lors de l\'enregistrement du fichier', null, 500);
}

// Définir les permissions du fichier
@chmod($destinationPath, 0644);

// Log de succès
$fileSize = round(filesize($destinationPath) / 1024, 2);
logUpload("[$uploadType] Fichier uploadé avec succès: {$finalName} ({$fileSize} Ko)", 'SUCCESS');

// Préparer les données de réponse
$responseData = [
    'filename' => $finalName,
    'original_name' => $file['name'],
    'size' => filesize($destinationPath),
    'size_kb' => $fileSize,
    'path' => $uploadConfig['upload_directory'] . '/' . $finalName,
    'type' => $uploadType
];

// Ajouter les dimensions pour les images
if ($uploadConfig['validation_type'] === 'image' && $validationResult !== null) {
    $responseData['dimensions'] = $validationResult[0] . 'x' . $validationResult[1];
}

// Message de succès personnalisé par type
$successMessage = '';
switch ($uploadType) {
    case 'photos':
        $successMessage = 'Photo envoyée avec succès';
        break;
    case 'videos':
        $successMessage = 'Vidéo envoyée avec succès';
        break;
    case 'documents':
        $successMessage = 'Document envoyé avec succès';
        break;
    default:
        $successMessage = 'Fichier envoyé avec succès';
}

// Réponse de succès
sendResponse(true, $successMessage, $responseData, 200);
