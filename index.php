<?php
$page = 'accueil';
$title = 'Accueil - Pour Vauvert, continuons d\'agir ENSEMBLE';

// Charger la configuration
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
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon-192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="favicon-512.png">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Pour VAUVERT, continuons d'agir <span style="color: #FF2E7E;">ENSEMBLE</span></h1>
            <p class="tagline">Un candidat, une vision, une équipe, un projet</p>
            
            <!-- Carrousel d'actualités -->
            <div class="carousel-container">
                <div class="carousel-wrapper">
                    <?php foreach($config['actualites'] as $index => $actu): ?>
                    <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="carousel-content">
                            <div class="carousel-image">
                                <img src="<?php echo $actu['photo']; ?>" alt="<?php echo htmlspecialchars($actu['titre']); ?>">
                            </div>
                            <div class="carousel-info">
                                <span class="carousel-date">
                                    <i class="fas fa-calendar-alt"></i> <?php echo $actu['date']; ?>
                                </span>
                                <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($actu['description']); ?></p>
                                <a href="<?php echo $actu['lien']; ?>" class="carousel-btn">
                                    En savoir plus <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Contrôles du carrousel -->
                <button class="carousel-btn-nav carousel-prev" onclick="moveSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn-nav carousel-next" onclick="moveSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Indicateurs -->
                <div class="carousel-indicators">
                    <?php foreach($config['actualites'] as $index => $actu): ?>
                    <span class="carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>" 
                          onclick="goToSlide(<?php echo $index; ?>)"></span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Lien vers le questionnaire -->
            <div style="margin: 30px 0;">
                <a href="<?php echo $config['liens']['questionnaire']; ?>" class="btn" target="_blank" style="background-color: #FFD500; color: #008AAD; font-size: 1.2em;">
                    <i class="fas fa-clipboard-list"></i> Répondre au questionnaire citoyen
                </a>
            </div>
            
            <div class="social-links">
                <a href="<?php echo $config['liens']['facebook']; ?>" target="_blank" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="<?php echo $config['liens']['youtube']; ?>" target="_blank" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="<?php echo $config['liens']['tiktok']; ?>" target="_blank" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="container">
        <h2>Élections Municipales et Communautaires</h2>
        <p style="text-align: center; font-size: 1.3em; color: #008AAD; font-weight: bold; margin-bottom: 40px;">
            Les 15 et 22 mars 2026
        </p>

        <div style="background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%); padding: 40px; border-radius: 15px; color: white; text-align: center;">
            <h3 style="color: white; margin-bottom: 20px;">Nos Priorités</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-heart" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Santé</h4>
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-leaf" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Transition écologique</h4>
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-handshake" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Solidarité</h4>
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-graduation-cap" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Éducation</h4>
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-shield-alt" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Sécurité et tranquillité publique</h4>
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px;">
                    <i class="fas fa-landmark" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <h4>Patrimoine</h4>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script>
    // JavaScript pour le carrousel
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const totalSlides = slides.length;

    function showSlide(n) {
        // Boucler si on dépasse
        if (n >= totalSlides) {
            currentSlide = 0;
        } else if (n < 0) {
            currentSlide = totalSlides - 1;
        } else {
            currentSlide = n;
        }

        // Masquer toutes les slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });

        // Désactiver tous les indicateurs
        indicators.forEach(indicator => {
            indicator.classList.remove('active');
        });

        // Afficher la slide actuelle
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
    }

    function moveSlide(direction) {
        showSlide(currentSlide + direction);
    }

    function goToSlide(n) {
        showSlide(n);
    }

    // Auto-play (optionnel - défilement automatique toutes les 5 secondes)
    setInterval(() => {
        moveSlide(1);
    }, 10000);
    </script>
</body>
</html>
