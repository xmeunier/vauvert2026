<?php
$page = 'bilan';
$title = 'Bilan - Pour Vauvert, continuons d\'agir ENSEMBLE';
$config = json_decode(file_get_contents('config.json'), true);

// Liste des vidéos du bilan depuis la config
$videosBilan = isset($config['bilan']) ? $config['bilan'] : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include 'meta_tags.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Citation section */
        .citation-section {
            max-width: 900px;
            margin: 0 auto 60px auto;
            text-align: center;
            padding: 40px 20px;
        }

        .citation-block {
            position: relative;
            padding: 40px 60px;
        }

        .citation-block::before,
        .citation-block::after {
            content: '"';
            font-size: 6em;
            color: #FFD500;
            font-family: Georgia, serif;
            position: absolute;
            line-height: 1;
        }

        .citation-block::before {
            top: 0;
            left: 0;
        }

        .citation-block::after {
            content: '"';
            bottom: 0;
            right: 0;
        }

        .citation-text {
            color: #5DD9C1;
            font-size: 1.8em;
            line-height: 1.4;
            font-weight: 600;
        }

        .citation-suite {
            color: #FF2E7E;
            font-size: 1.8em;
            font-weight: 600;
            margin-top: 10px;
        }

        /* PDF section */
        .pdf-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .pdf-viewer {
            width: 100%;
            height: 800px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .pdf-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .pdf-header h2 {
            color: #008AAD;
            margin-bottom: 10px;
        }

        .pdf-header p {
            color: #666;
            font-size: 1.1em;
        }

        .download-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #008AAD 0%, #00A0C6 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 138, 173, 0.4);
        }

        .download-btn i {
            margin-right: 8px;
        }

        /* Videos section */
        .videos-section {
            margin-top: 60px;
        }

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .video-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .video-card:hover {
            transform: translateY(-10px);
        }

        .video-thumbnail {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-play-btn {
            position: absolute;
            width: 70px;
            height: 70px;
            background: rgba(255, 46, 126, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8em;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .video-card:hover .video-play-btn {
            transform: scale(1.1);
            background: rgba(255, 46, 126, 1);
        }

        .video-play-btn i {
            margin-left: 5px;
        }

        .video-info {
            padding: 20px;
        }

        .video-info h4 {
            color: #008AAD;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .video-info p {
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .citation-block {
                padding: 30px 40px;
            }

            .citation-block::before,
            .citation-block::after {
                font-size: 4em;
            }

            .citation-text {
                font-size: 1.2em;
            }

            .citation-suite {
                font-size: 1.5em;
            }

            .pdf-viewer {
                height: 600px;
            }

            .videos-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="container">
        <!-- Citation -->
        <div class="citation-section">
            <div class="citation-block">
                <p class="citation-text">Ce mandat a été guidé par une exigence simple : être utile aux habitants, à la ville, à l'avenir.</p>
                <p class="citation-suite">La suite s'écrit avec vous.</p>
            </div>
        </div>

        <!-- PDF Bilan -->
        <div class="pdf-container">
            <div class="pdf-header">
                <h2><?php echo htmlspecialchars($config['bilan_document']['titre']); ?></h2>
                <p><?php echo htmlspecialchars($config['bilan_document']['descriptif']); ?></p>
            </div>

<?php if(!empty($config['bilan_document']['lien_pdf'])): ?>
            <iframe
                src="<?php echo htmlspecialchars($config['bilan_document']['lien_pdf']); ?>#toolbar=1&navpanes=1&scrollbar=1"
                class="pdf-viewer"
                type="application/pdf"
                title="Bilan Jean Denat">
            </iframe>

            <div style="text-align: center;">
                <a href="<?php echo htmlspecialchars($config['bilan_document']['lien_pdf']); ?>"
                   download
                   class="download-btn">
                    <i class="fas fa-download"></i>
                    Télécharger le bilan PDF
                </a>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-file-pdf" style="font-size: 4em; color: #008AAD; margin-bottom: 20px;"></i>
                <h3 style="color: #008AAD; font-size: 1.8em;">Document à venir</h3>
                <p style="color: #666; font-size: 1.1em; margin-top: 10px;">
                    <?php echo isset($config['bilan_document']['message_attente']) ? htmlspecialchars($config['bilan_document']['message_attente']) : 'Le bilan sera bientôt disponible.'; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Vidéos -->
        <div class="videos-section">
            <h2>La Minute Bilan !</h2>

            <div class="videos-grid">
                <?php foreach($videosBilan as $index => $video): ?>
                <div class="video-card" onclick="openVideoModal(<?php echo $index; ?>)">
                    <div class="video-thumbnail">
                        <?php if(!empty($video['photo']) && file_exists($video['photo'])): ?>
                            <img src="<?php echo htmlspecialchars($video['photo']); ?>" alt="<?php echo htmlspecialchars($video['titre']); ?>">
                        <?php endif; ?>
                        <div class="video-play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-info">
                        <h4><?php echo htmlspecialchars($video['titre']); ?></h4>
                        <p><?php echo htmlspecialchars($video['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">Construisons ensemble l'avenir de Vauvert</h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">N'hésitez pas à nous contacter pour échanger</p>
            <a href="contact.php" class="btn">Nous contacter</a>
        </div>
    </section>

    <!-- Modal Vidéo -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-modal-close" onclick="closeVideoModal()">&times;</span>
            <div class="video-container">
                <iframe id="videoFrame" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
        const videosData = <?php echo json_encode(array_column($videosBilan, 'video')); ?>;

        function openVideoModal(index) {
            const modal = document.getElementById('videoModal');
            const videoFrame = document.getElementById('videoFrame');
            const videoUrl = videosData[index];

            let embedUrl = videoUrl;

            // Conversion des URLs YouTube
            if (videoUrl.includes('youtube.com/watch')) {
                const videoId = new URL(videoUrl).searchParams.get('v');
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            } else if (videoUrl.includes('youtu.be/')) {
                const videoId = videoUrl.split('youtu.be/')[1].split('?')[0];
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
            // Conversion des URLs Vimeo
            else if (videoUrl.includes('vimeo.com/')) {
                const videoId = videoUrl.split('vimeo.com/')[1].split('?')[0];
                embedUrl = `https://player.vimeo.com/video/${videoId}`;
            }

            videoFrame.src = embedUrl;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const videoFrame = document.getElementById('videoFrame');

            videoFrame.src = '';
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('videoModal');
            if (event.target === modal) {
                closeVideoModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
