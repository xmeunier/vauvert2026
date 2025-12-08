<?php
$page = 'evenements';
$title = 'Événements - Pour Vauvert, continuons d\'agir ENSEMBLE';
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
        <h2>Événements</h2>
        <p style="text-align: center; font-size: 1.2em; color: #008AAD; margin-bottom: 40px;">
            Rencontrez-nous et participez à nos réunions
        </p>

        <div class="events-grid">
    <?php
    foreach($config['reunions'] as $reunion):
        // Parse la date française et vérifie si l'événement est passé
        $dateStr = $reunion['date'];
        $heureStr = $reunion['heure'] ?? '';
        $isPast = false;

        // Debug - à supprimer après test
        echo "<!-- Date: $dateStr | Heure: $heureStr -->";

        // Extrait les informations de date (ignore le jour de la semaine si présent)
        if (preg_match('/(?:lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche)?\s*(\d{1,2})\s+([a-zàâäéèêëïîôùûüÿæœç]+)/iu', $dateStr, $matches)) {
            $jour = $matches[1];
            $moisFr = strtolower($matches[2]);

            // Debug
            echo "<!-- Jour: $jour | Mois: $moisFr -->";

            // Conversion mois français en numéro
            $moisMap = [
                'janvier' => 1, 'février' => 2, 'mars' => 3, 'avril' => 4,
                'mai' => 5, 'juin' => 6, 'juillet' => 7, 'août' => 8,
                'septembre' => 9, 'octobre' => 10, 'novembre' => 11, 'décembre' => 12
            ];

            if (isset($moisMap[$moisFr])) {
                $mois = $moisMap[$moisFr];
                $annee = date('Y');

                // Extrait l'heure si disponible (format: 18h30, 18h, etc.)
                $heure = 23;
                $minute = 59;
                if (!empty($heureStr) && preg_match('/(\d{1,2})h(\d{2})?/i', $heureStr, $heureMatches)) {
                    $heure = (int)$heureMatches[1];
                    $minute = isset($heureMatches[2]) ? (int)$heureMatches[2] : 0;
                }

                // Crée la date de l'événement
                $dateEvent = strtotime("$annee-$mois-$jour $heure:$minute:00");
                $now = time();

                // Debug
                echo "<!-- DateEvent: " . date('Y-m-d H:i:s', $dateEvent) . " | Now: " . date('Y-m-d H:i:s', $now) . " | isPast: " . ($dateEvent < $now ? 'OUI' : 'NON') . " -->";

                // Si la date est dans le passé, c'est peut-être pour l'année prochaine
                if ($dateEvent < time()) {
                    $isPast = true;
                } else {
                    $isPast = false;
                }
            }
        }

        $cardClass = $isPast ? 'event-card event-past' : 'event-card';
    ?>
    <div class="<?php echo $cardClass; ?>">
        <h3><i class="fas fa-calendar-alt"></i> <?php echo $reunion['titre']; ?></h3>
        <p class="event-date">
            <i class="fas fa-clock"></i>
            <?php echo $reunion['date'] . ' à ' . $reunion['heure']; ?>
        </p>
        <p class="event-location">
            <i class="fas fa-map-marker-alt"></i>
            <?php echo $reunion['lieu']; ?>
        </p>
        <p><?php echo $reunion['description']; ?></p>
        <ul style="margin-top: 15px; margin-left: 20px;">
            <?php foreach($reunion['details'] as $detail): ?>
            <li><?php echo $detail; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php if ($isPast): ?>
        <div class="event-past-badge">
            <i class="fas fa-check-circle"></i> Événement terminé
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
      </div>

        <!-- Section Questionnaire -->
        <div class="questionnaire-section">
            <h3 style="color: white; margin-bottom: 20px;"><i class="fas fa-clipboard-list"></i> Votre avis compte !</h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">
                Participez à notre questionnaire citoyen et contribuez à construire le projet de demain pour Vauvert
            </p>
            
            <div style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 40px;">
                <div class="qr-code">
                    <img src="<?php echo $config['liens']['qrcode']; ?>" alt="QR Code questionnaire">
                    <p style="margin-top: 10px; font-weight: bold;">Scannez le QR code</p>
                </div>
                
                <div>
                    <a href="<?php echo $config['liens']['questionnaire']; ?>" class="btn" style="background-color: #FFD500; color: #008AAD;" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Accéder au questionnaire
                    </a>
                </div>
            </div>
            
            <p style="margin-top: 30px; font-size: 0.9em; opacity: 0.9;">
                Le questionnaire prend environ 5 minutes. Toutes les réponses sont anonymes et contribuent à améliorer notre projet commun.
            </p>
        </div>

        <!-- Calendrier à venir -->
        <div style="background: #F0F0F0; padding: 40px; border-radius: 15px; margin-top: 60px; text-align: center;">
            <h3 style="color: #008AAD; margin-bottom: 20px;"><i class="fas fa-calendar-plus"></i> Autres événements à venir</h3>
            <p style="font-size: 1.1em; color: #333;">
                D'autres rencontres et événements seront organisés dans les prochaines semaines. 
                Suivez-nous sur les réseaux sociaux pour ne rien manquer !
            </p>
            <div class="social-links" style="margin-top: 30px;">
                <a href="<?php echo $config['liens']['facebook']; ?>" target="_blank" style="background-color: #008AAD;">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="<?php echo $config['liens']['youtube']; ?>" target="_blank" style="background-color: #008AAD;">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="<?php echo $config['liens']['tiktok']; ?>" target="_blank" style="background-color: #008AAD;">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
