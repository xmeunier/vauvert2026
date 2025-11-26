<?php
// Liste fermée des types de documents autorisés
$documentsAutorises = ['bilan', 'programme', 'statuts', 'projet'];

// Récupération du type de document depuis l'URL
$type = isset($_GET['type']) ? $_GET['type'] : 'bilan';

// Vérification que le type est autorisé
if (!in_array($type, $documentsAutorises)) {
    // Redirection vers la page d'accueil si le type n'est pas autorisé
    header('Location: index.php');
    exit;
}

// Chargement de la configuration
$config = json_decode(file_get_contents('config.json'), true);

// Construction de la clé de configuration (type_document)
$configKey = $type . '_document';

// Vérification que le document existe dans la config
if (!isset($config[$configKey])) {
    // Redirection vers la page d'accueil si le document n'existe pas dans la config
    header('Location: index.php');
    exit;
}

// Récupération des informations du document
$docConfig = $config[$configKey];
$page = $type;
$title = (isset($docConfig['titre']) ? $docConfig['titre'] : ucfirst($type)) . ' - Pour Vauvert, continuons d\'agir ENSEMBLE';
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
                <h2><?php echo isset($docConfig['titre']) ? htmlspecialchars($docConfig['titre']) : ucfirst($type); ?></h2>
                <p><?php echo isset($docConfig['descriptif']) ? htmlspecialchars($docConfig['descriptif']) : ''; ?></p>
            </div>

            <?php if(isset($docConfig['lien_pdf']) && !empty($docConfig['lien_pdf'])): ?>
                <!-- Lecteur PDF intégré -->
                <iframe
                    src="<?php echo htmlspecialchars($docConfig['lien_pdf']); ?>#toolbar=1&navpanes=1&scrollbar=1"
                    class="pdf-viewer"
                    type="application/pdf"
                    title="<?php echo isset($docConfig['titre']) ? htmlspecialchars($docConfig['titre']) : 'Document'; ?>">
                </iframe>

                <!-- Bouton de téléchargement -->
                <div style="text-align: center;">
                    <a href="<?php echo htmlspecialchars($docConfig['lien_pdf']); ?>"
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
                    <p style="color: #666; font-size: 1.1em; margin-top: 10px;">
                        <?php echo isset($docConfig['message_attente']) ? htmlspecialchars($docConfig['message_attente']) : 'Ce document sera bientôt disponible.'; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">
                <?php echo isset($docConfig['infos_complementaires']) ? htmlspecialchars($docConfig['infos_complementaires']) : 'Une question ?'; ?>
            </h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">N'hésitez pas à nous contacter</p>
            <a href="contact.php" class="btn">Nous contacter</a>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
