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

// Lister tous les types de fichiers
$allFiles = ['photos' => [], 'videos' => [], 'documents' => []];

foreach ($config['upload_types'] as $type => $typeConfig) {
    $dir = __DIR__ . '/' . $typeConfig['upload_directory'];

    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && !is_dir($dir . '/' . $file)) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $typeConfig['allowed_extensions'])) {
                    $allFiles[$type][] = [
                        'name' => $file,
                        'size' => filesize($dir . '/' . $file),
                        'date' => filemtime($dir . '/' . $file),
                        'path' => $typeConfig['upload_directory'] . '/' . $file,
                        'extension' => $extension
                    ];
                }
            }
        }

        // Trier par date (plus récent en premier)
        usort($allFiles[$type], function ($a, $b) {
            return $b['date'] - $a['date'];
        });
    }
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
    <title>Backoffice - Gestion des médias</title>
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

        .tabs-container {
            max-width: 1200px;
            margin: 20px auto 0;
            padding: 0 20px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            background: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .tab-btn {
            flex: 1;
            padding: 15px 20px;
            background: #f5f5f5;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            color: #666;
        }

        .tab-btn:hover {
            background: #e8f4fd;
            color: #333;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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

        .files-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .files-section h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .file-card {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s;
            position: relative;
        }

        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .file-card img,
        .file-card video {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .file-card.video::before {
            content: '▶';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: rgba(255,255,255,0.8);
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .file-card.document {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        }

        .file-card.document .file-icon {
            font-size: 80px;
            margin-bottom: 10px;
        }

        .file-info {
            padding: 15px;
        }

        .file-name {
            font-weight: 600;
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .file-meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .file-meta a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .file-meta a:hover {
            text-decoration: underline;
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
            <h1>🗂️ Gestion des Médias</h1>
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="?logout=1" class="logout-btn">Déconnexion</a>
            </div>
        </div>
    </div>

    <div class="tabs-container">
        <div class="tabs">
            <button class="tab-btn active" data-type="photos">📸 Photos</button>
            <button class="tab-btn" data-type="videos">🎬 Vidéos</button>
            <button class="tab-btn" data-type="documents">📄 Documents</button>
        </div>
    </div>

    <div class="container">
        <div class="upload-section">
            <h2 id="uploadTitle">Envoyer une nouvelle photo</h2>

            <div id="message"></div>

            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" id="uploadType" name="upload_type" value="photos">
                <div class="upload-zone" id="uploadZone">
                    <div class="upload-icon" id="uploadIcon">📁</div>
                    <div class="upload-text" id="uploadText">Cliquez ou glissez une photo ici</div>
                    <small id="uploadFormats">Formats acceptés: <?php echo implode(', ', $config['upload_types']['photos']['allowed_extensions']); ?></small><br>
                    <small id="uploadMaxSize">Taille max: <?php echo round($config['upload_types']['photos']['max_file_size'] / 1024 / 1024, 1); ?> Mo</small>
                    <input type="file" id="fileInput" name="file" accept="image/*">
                </div>

                <div class="file-info" id="fileInfo"></div>

                <div class="progress-bar" id="progressBar">
                    <div class="progress-fill" id="progressFill">0%</div>
                </div>

                <button type="submit" class="upload-btn" id="uploadBtn">Envoyer la photo</button>
            </form>
        </div>

        <div class="files-section">
            <h2 id="filesTitle">Photos existantes (<span id="filesCount">0</span>)</h2>
            <div id="filesGrid" class="files-grid">
                <!-- Dynamically populated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Configuration des types d'upload
        const uploadConfig = {
            photos: {
                title: 'Envoyer une nouvelle photo',
                icon: '📁',
                text: 'Cliquez ou glissez une photo ici',
                formats: '<?php echo implode(", ", $config["upload_types"]["photos"]["allowed_extensions"]); ?>',
                maxSize: '<?php echo round($config["upload_types"]["photos"]["max_file_size"] / 1024 / 1024, 1); ?> Mo',
                accept: 'image/*',
                filesTitle: 'Photos existantes'
            },
            videos: {
                title: 'Envoyer une nouvelle vidéo',
                icon: '🎬',
                text: 'Cliquez ou glissez une vidéo ici',
                formats: '<?php echo implode(", ", $config["upload_types"]["videos"]["allowed_extensions"]); ?>',
                maxSize: '<?php echo round($config["upload_types"]["videos"]["max_file_size"] / 1024 / 1024, 1); ?> Mo',
                accept: 'video/*',
                filesTitle: 'Vidéos existantes'
            },
            documents: {
                title: 'Envoyer un nouveau document',
                icon: '📄',
                text: 'Cliquez ou glissez un PDF ici',
                formats: '<?php echo implode(", ", $config["upload_types"]["documents"]["allowed_extensions"]); ?>',
                maxSize: '<?php echo round($config["upload_types"]["documents"]["max_file_size"] / 1024 / 1024, 1); ?> Mo',
                accept: 'application/pdf',
                filesTitle: 'Documents existants'
            }
        };

        // Données des fichiers
        const allFiles = <?php echo json_encode($allFiles); ?>;
        let currentType = 'photos';

        // Éléments DOM
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const uploadBtn = document.getElementById('uploadBtn');
        const uploadForm = document.getElementById('uploadForm');
        const messageDiv = document.getElementById('message');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');

        let selectedFile = null;

        // Gestion des onglets
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => switchTab(btn.dataset.type));
        });

        function switchTab(type) {
            currentType = type;
            const config = uploadConfig[type];

            // Mise à jour styling onglets
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.type === type);
            });

            // Mise à jour zone d'upload
            document.getElementById('uploadTitle').textContent = config.title;
            document.getElementById('uploadIcon').textContent = config.icon;
            document.getElementById('uploadText').textContent = config.text;
            document.getElementById('uploadFormats').textContent = 'Formats acceptés: ' + config.formats;
            document.getElementById('uploadMaxSize').textContent = 'Taille max: ' + config.maxSize;
            document.getElementById('fileInput').setAttribute('accept', config.accept);
            document.getElementById('uploadType').value = type;
            document.getElementById('uploadBtn').textContent = 'Envoyer ' + (type === 'photos' ? 'la photo' : type === 'videos' ? 'la vidéo' : 'le document');

            // Mise à jour liste de fichiers
            document.getElementById('filesTitle').innerHTML = config.filesTitle + ' (<span id="filesCount">' + allFiles[type].length + '</span>)';
            renderFiles(type);

            // Reset upload form
            resetUploadForm();
        }

        function resetUploadForm() {
            selectedFile = null;
            fileInput.value = '';
            fileInfo.style.display = 'none';
            uploadBtn.style.display = 'none';
            messageDiv.innerHTML = '';
            progressBar.style.display = 'none';
            progressFill.style.width = '0';
            uploadBtn.disabled = false;
        }

        function renderFiles(type) {
            const filesGrid = document.getElementById('filesGrid');
            const files = allFiles[type];

            if (files.length === 0) {
                filesGrid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">${uploadConfig[type].icon}</div>
                        <p>Aucun fichier n'a encore été envoyé.</p>
                    </div>
                `;
                return;
            }

            filesGrid.innerHTML = files.map(file => {
                if (type === 'photos') {
                    return createPhotoCard(file);
                } else if (type === 'videos') {
                    return createVideoCard(file);
                } else {
                    return createDocumentCard(file);
                }
            }).join('');
        }

        function createPhotoCard(file) {
            const date = new Date(file.date * 1000);
            const dateStr = date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            return `
                <div class="file-card photo">
                    <img src="${file.path}" alt="${file.name}">
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-meta">📦 ${(file.size / 1024).toFixed(1)} Ko</div>
                        <div class="file-meta">📅 ${dateStr}</div>
                    </div>
                </div>
            `;
        }

        function createVideoCard(file) {
            const date = new Date(file.date * 1000);
            const dateStr = date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            return `
                <div class="file-card video">
                    <video width="100%" height="200">
                        <source src="${file.path}" type="video/${file.extension}">
                    </video>
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-meta">📦 ${(file.size / 1024 / 1024).toFixed(1)} Mo</div>
                        <div class="file-meta">📅 ${dateStr}</div>
                    </div>
                </div>
            `;
        }

        function createDocumentCard(file) {
            const date = new Date(file.date * 1000);
            const dateStr = date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            return `
                <div class="file-card document">
                    <div class="file-icon">📄</div>
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-meta">📦 ${(file.size / 1024).toFixed(1)} Ko</div>
                        <div class="file-meta">📅 ${dateStr}</div>
                        <div class="file-meta">
                            <a href="${file.path}" target="_blank">Ouvrir le PDF</a>
                        </div>
                    </div>
                </div>
            `;
        }

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
                showMessage('Veuillez sélectionner un fichier', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('file', selectedFile);
            formData.append('upload_type', currentType);

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

                xhr.open('POST', 'admin_upload.php');
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

        // Initialiser l'onglet photos au chargement
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('photos');
        });
    </script>
</body>
</html>
