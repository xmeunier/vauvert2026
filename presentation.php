<?php
$page = 'presentation';
$title = 'Présentation - Pour Vauvert, continuons d\'agir ENSEMBLE';
$config = json_decode(file_get_contents('config.json'), true);
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
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="container">
        <h2>Le Candidat</h2>
        
        <div class="team-grid" style="max-width: 800px; margin: 40px auto;">
            <div class="team-member">
                <img src="<?php echo $config['candidat']['photo']; ?>" alt="Jean DENAT">
                <div class="team-member-info">
                    <h4><?php echo $config['candidat']['nom']; ?></h4>
                    <p style="color: #FF2E7E; font-weight: bold; margin-bottom: 10px;"><?php echo $config['candidat']['fonction']; ?></p>
                    <p><?php echo $config['candidat']['mot_du_candidat']; ?></p>
                    <p style="margin-top: 15px; font-style: italic;"><?php echo $config['site']['nom']; ?></p>
                </div>
            </div>
        </div>

        <div style="max-width: 900px; margin: 60px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h3 style="color: #008AAD; margin-bottom: 30px; text-align: center;">Lettre du candidat</h3>

            <div style="line-height: 1.8; color: #333;">
                <p style="margin-bottom: 15px;">Chères Vauverdoises,<br>
                Chers Vauverdois,</p>

                <p style="margin-bottom: 20px;"><em>En quelques années, Vauvert a beaucoup changé. Ce changement, c'est le fruit de l'engagement quotidien d'une équipe municipale qui a œuvré pour faire de notre ville un lieu où il fait bon vivre.</em></p>

                <h4 style="color: #008AAD; margin: 30px 0 25px 0; text-align: center; font-size: 1.5em;">Nous avons agi pour faire de Vauvert :</h4>

                <div class="encarts-grid">
                    <!-- Une ville à visage humain -->
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #FF2E7E;">
                        <h5 style="color: #FF2E7E; font-size: 1.1em; margin-bottom: 10px;">Une ville à visage humain,</h5>
                        <p style="font-size: 0.95em; line-height: 1.6;">qui se développe tout en préservant son identité, engagée dans la rénovation de l'habitat, en centre ville, au sein du quartier d'habitat social, sur l'ensemble de la commune et dans la mise en valeur de son patrimoine.</p>
                    </div>

                    <!-- Une ville qui bouge -->
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #FF2E7E;">
                        <h5 style="color: #FF2E7E; font-size: 1.1em; margin-bottom: 10px;">Une ville qui bouge,</h5>
                        <p style="font-size: 0.95em; line-height: 1.6;">avec l'ouverture de commerces en centre ville, l'accueil de nouvelles entreprises sur la ZAC Côté Soleil, le pôle des Costières et l'implantation de nouveaux services et équipements de proximité.</p>
                    </div>

                    <!-- Une ville qui respire -->
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #FF2E7E;">
                        <h5 style="color: #FF2E7E; font-size: 1.1em; margin-bottom: 10px;">Une ville qui respire,</h5>
                        <p style="font-size: 0.95em; line-height: 1.6;">plus paisible, grâce à la création de parcs, d'aires de jeux, de chemins piétons et à un vaste plan voirie dans tous les quartiers.</p>
                    </div>

                    <!-- Une ville conviviale -->
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #FF2E7E;">
                        <h5 style="color: #FF2E7E; font-size: 1.1em; margin-bottom: 10px;">Une ville conviviale</h5>
                        <p style="font-size: 0.95em; line-height: 1.6;">solidaire qui soutient l'éducation, la vie associative et sportive, multiplie les occasions de rencontres et propose de nombreux rendez-vous culturels, taurins et festifs.</p>
                    </div>
                </div>

                <p style="margin-bottom: 20px;">Tout cela a été mené en transparence, en favorisant la participation citoyenne et le dialogue avec vous lors de nos rencontres et opérations de terrain en "porte à porte".</p>

                <p style="margin-bottom: 20px;">Alors que le mandat s'achève, nous souhaitons partager avec vous le bilan de notre action et échanger sur nos orientations pour l'avenir. Des ateliers seront organisés pour discuter ensemble de nos réussites, de vos attentes et de projets à venir.</p>

                <p style="margin-bottom: 20px;">Car si beaucoup a été accompli, beaucoup reste à faire. Des chantiers sont en cours, de nouveaux projets prêts à démarrer, et nous portons des idées neuves pour répondre à l'évolution des besoins des Vauverdois.</p>

                <p style="margin-bottom: 20px;">Voilà pourquoi, toujours animé par la passion de ma ville et la volonté de servir ses habitants, toujours prêt à être à la fois un maire du quotidien, de terrain, à l'écoute et porteur d'une vision d'avenir, j'ai décidé d'être candidat aux prochaines élections municipales.</p>

                <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 25px; border-radius: 10px; margin: 30px 0;">
                    <p style="color: #008AAD; font-weight: bold; font-size: 1.1em; margin-bottom: 15px; text-align: center;">Autour de moi, je rassemble une équipe de femmes et d'hommes engagés et connus de vous, acteurs de la vie sociale et quotidien, réunis sous la bannière :</p>
                    <div style="text-align: center;">
                        <img src="vauvertEnsemble.png" alt="Pour Vauvert, continuons d'agir ENSEMBLE" style="max-width: 400px; width: 100%; height: auto;">
                    </div>
                </div>

                <p style="margin-bottom: 15px;">Chères Vauverdoises, chers Vauverdois, les élections municipales sont un moment important.<br>
                En mars 2026, il s'agira de Vauvert, et de Vauvert seulement.</p>

                <p>Dans un contexte où les communes sont fragilisées, notre ville a besoin de stabilité, d'une équipe solide, solidaire et mobilisée, à votre écoute, capable de protéger et de faire grandir l'espoir d'une ère nouvelle, qu'elle a tracée, pour Vauvert.</p>
            </div>
        </div>

        <h2 style="margin-top: 80px;">L'Équipe</h2>
        <p style="text-align: center; font-size: 1.2em; color: #008AAD; margin-bottom: 40px;">
            Une équipe engagée et proche des citoyens
        </p>

        <div class="team-grid">
          <?php if(empty($config['equipe'])): ?>
            <div style="text-align: center; padding: 60px 20px; width: 100%;">
              <i class="fas fa-users" style="font-size: 4em; color: #008AAD; margin-bottom: 20px;"></i>
              <h3 style="color: #008AAD; font-size: 1.8em;">À venir</h3>
              <p style="color: #666; font-size: 1.1em; margin-top: 10px;"><?php echo $config['messages']['0_membres_equipe']; ?></p>
            </div>
          <?php else: ?>
            <?php foreach($config['equipe'] as $index => $membre): ?>
            <div class="team-member">
              <div class="team-member-photo">
                <img src="<?php echo $membre['photo']; ?>"
                   alt="<?php echo $membre['prenom'] . ' ' . $membre['nom']; ?>">
                <?php if(!empty($membre['video'])): ?>
                <button class="video-overlay-btn" onclick="openVideoModal('<?php echo $index; ?>')">
                  <i class="fas fa-play-circle"></i>
                  Voir vidéo de présentation
                </button>
                <?php endif; ?>
              </div>
              <div class="team-member-info">
                <h4><?php echo $membre['prenom'] . ' ' . $membre['nom']; ?></h4>
                <p style="color: #FF2E7E; font-weight: bold;">
                  <?php echo $membre['delegation']; ?>
                </p>
                <p><?php echo $membre['description']; ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">Rejoignez-nous !</h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">Ensemble, construisons l'avenir de Vauvert</p>
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
        const membersVideos = <?php echo json_encode(array_column($config['equipe'], 'video')); ?>;

        function openVideoModal(index) {
            const modal = document.getElementById('videoModal');
            const videoFrame = document.getElementById('videoFrame');
            const videoUrl = membersVideos[index];

            // Support pour YouTube, Vimeo, et vidéos directes
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

        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('videoModal');
            if (event.target === modal) {
                closeVideoModal();
            }
        }

        // Fermer avec la touche Echap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
