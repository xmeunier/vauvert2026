<?php
$page = 'bilan';
$title = 'Bilan - Pour Vauvert, continuons d\'agir ENSEMBLE';
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
    <style>
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

        @media (max-width: 768px) {
            .pdf-viewer {
                height: 600px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="container">
        <div class="pdf-container">
            <div class="pdf-header">
                <h2>Bilan du Mandat</h2>
                <p>Découvrez le bilan de notre action pour Vauvert</p>
            </div>

            <?php if(isset($config['bilan']['lien_pdf']) && !empty($config['bilan']['lien_pdf'])): ?>
                <!-- Lecteur PDF intégré -->
                <iframe
                    src="<?php echo htmlspecialchars($config['bilan']['lien_pdf']); ?>#toolbar=1&navpanes=1&scrollbar=1"
                    class="pdf-viewer"
                    type="application/pdf"
                    title="Document Bilan">
                </iframe>

                <!-- Bouton de téléchargement -->
                <div style="text-align: center;">
                    <a href="<?php echo htmlspecialchars($config['bilan']['lien_pdf']); ?>"
                       download
                       class="download-btn">
                        <i class="fas fa-download"></i>
                        Télécharger le document PDF
                    </a>
                </div>
            <?php else: ?>
                <!-- Message si aucun document n'est configuré -->
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-file-pdf" style="font-size: 4em; color: #008AAD; margin-bottom: 20px;"></i>
                    <h3 style="color: #008AAD; font-size: 1.8em;">Document à venir</h3>
                    <p style="color: #666; font-size: 1.1em; margin-top: 10px;">Le document du bilan sera bientôt disponible.</p>
                </div>
            <?php endif; ?>
        </div>

        <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">Une question sur notre bilan ?</h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">N'hésitez pas à nous contacter</p>
            <a href="contact.php" class="btn">Nous contacter</a>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
