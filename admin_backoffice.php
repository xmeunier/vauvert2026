<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Timeout de session (30 minutes)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_destroy();
    header('Location: admin_login.php?timeout=1');
    exit();
}

// Charger la configuration
$configPath = __DIR__ . '/admin_config.json';
$config = json_decode(file_get_contents($configPath), true);

// Lister les photos existantes
$photoDir = __DIR__ . '/' . $config['upload']['upload_directory'];
$photos = [];

if (is_dir($photoDir)) {
    $files = scandir($photoDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && !is_dir($photoDir . '/' . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $config['upload']['allowed_extensions'])) {
                $photos[] = [
                    'name' => $file,
                    'size' => filesize($photoDir . '/' . $file),
                    'date' => filemtime($photoDir . '/' . $file),
                    'path' => $config['upload']['upload_directory'] . '/' . $file
                ];
            }
        }
    }

    // Trier par date (plus récent en premier)
    usort($photos, function ($a, $b) {
        return $b['date'] - $a['date'];
    });
}

// Gérer la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Gestion des photos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .upload-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .upload-section h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .upload-zone {
            border: 3px dashed #ccc;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #fafafa;
            transition: all 0.3s;
            cursor: pointer;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .upload-zone input[type="file"] {
            display: none;
        }

        .upload-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .upload-text {
            color: #666;
            margin-bottom: 10px;
        }

        .file-info {
            margin-top: 20px;
            padding: 15px;
            background: #e8f4fd;
            border-radius: 5px;
            display: none;
        }

        .upload-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            display: none;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .photos-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .photos-section h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .photo-card {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .photo-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .photo-info {
            padding: 15px;
        }

        .photo-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .photo-meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .progress-bar {
            width: 100%;
            height: 30px;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            display: none;
            margin-top: 15px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 0;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>📸 Gestion des Photos</h1>
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="?logout=1" class="logout-btn">Déconnexion</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="upload-section">
            <h2>Envoyer une nouvelle photo</h2>

            <div id="message"></div>

            <form id="uploadForm" enctype="multipart/form-data">
                <div class="upload-zone" id="uploadZone">
                    <div class="upload-icon">📁</div>
                    <div class="upload-text">Cliquez ou glissez une photo ici</div>
                    <small>Formats acceptés: <?php echo implode(', ', $config['upload']['allowed_extensions']); ?></small><br>
                    <small>Taille max: <?php echo round($config['upload']['max_file_size'] / 1024 / 1024, 1); ?> Mo</small>
                    <input type="file" id="fileInput" name="photo" accept="image/*">
                </div>

                <div class="file-info" id="fileInfo"></div>

                <div class="progress-bar" id="progressBar">
                    <div class="progress-fill" id="progressFill">0%</div>
                </div>

                <button type="submit" class="upload-btn" id="uploadBtn">Envoyer la photo</button>
            </form>
        </div>

        <div class="photos-section">
            <h2>Photos existantes (<?php echo count($photos); ?>)</h2>

            <?php if (empty($photos)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🖼️</div>
                    <p>Aucune photo n'a encore été envoyée.</p>
                </div>
            <?php else: ?>
                <div class="photos-grid">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-card">
                            <img src="<?php echo htmlspecialchars($photo['path']); ?>" alt="<?php echo htmlspecialchars($photo['name']); ?>">
                            <div class="photo-info">
                                <div class="photo-name"><?php echo htmlspecialchars($photo['name']); ?></div>
                                <div class="photo-meta">📦 <?php echo round($photo['size'] / 1024, 1); ?> Ko</div>
                                <div class="photo-meta">📅 <?php echo date('d/m/Y H:i', $photo['date']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadForm = document.getElementById('uploadForm');
        const messageDiv = document.getElementById('message');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');

        let selectedFile = null;

        // Click sur la zone d'upload
        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Sélection de fichier
        fileInput.addEventListener('change', (e) => {
            handleFile(e.target.files[0]);
        });

        // Drag and drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file) return;

            selectedFile = file;

            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.innerHTML = `
                <strong>Fichier sélectionné:</strong><br>
                📄 Nom: ${file.name}<br>
                📦 Taille: ${fileSize} Mo<br>
                📝 Type: ${file.type}
            `;
            fileInfo.style.display = 'block';
            uploadBtn.style.display = 'block';
        }

        // Soumission du formulaire
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!selectedFile) {
                showMessage('Veuillez sélectionner une photo', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('photo', selectedFile);

            uploadBtn.disabled = true;
            progressBar.style.display = 'block';
            messageDiv.innerHTML = '';

            try {
                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressFill.style.width = percent + '%';
                        progressFill.textContent = percent + '%';
                    }
                });

                xhr.addEventListener('load', () => {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showMessage(response.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showMessage(response.message, 'error');
                            uploadBtn.disabled = false;
                        }
                    } else {
                        showMessage('Erreur lors de l\'upload', 'error');
                        uploadBtn.disabled = false;
                    }
                    progressBar.style.display = 'none';
                });

                xhr.addEventListener('error', () => {
                    showMessage('Erreur réseau', 'error');
                    uploadBtn.disabled = false;
                    progressBar.style.display = 'none';
                });

                xhr.open('POST', 'admin_upload_photo.php');
                xhr.send(formData);

            } catch (error) {
                showMessage('Erreur: ' + error.message, 'error');
                uploadBtn.disabled = false;
                progressBar.style.display = 'none';
            }
        });

        function showMessage(text, type) {
            messageDiv.innerHTML = `<div class="message ${type}">${text}</div>`;
        }
    </script>
</body>
</html>
