<?php
$page = 'questionnaire';
$title = 'Questionnaire Citoyen - Pour Vauvert, continuons d\'agir ENSEMBLE';
$config = json_decode(file_get_contents('config.json'), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .questionnaire-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .questionnaire-intro {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px;
        }

        .questionnaire-intro h2 {
            color: #008AAD;
            margin-bottom: 20px;
        }

        .questionnaire-intro p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #333;
            margin-bottom: 15px;
        }

        .iframe-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .iframe-wrapper {
            position: relative;
            width: 100%;
            height: 800px;
            min-height: 600px;
        }

        .iframe-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .loading-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #008AAD;
            font-size: 1.2em;
        }

        .loading-message i {
            font-size: 2em;
            margin-bottom: 15px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .intro-box {
            background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .intro-box h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        .intro-box ul {
            list-style: none;
            padding-left: 0;
        }

        .intro-box li {
            padding: 8px 0;
            padding-left: 30px;
            position: relative;
        }

        .intro-box li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #FFD500;
            font-weight: bold;
            font-size: 1.2em;
        }

        .alternative-link {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
        }

        .alternative-link a {
            color: #008AAD;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1em;
        }

        .alternative-link a:hover {
            color: #FF2E7E;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .iframe-wrapper {
                height: 900px;
            }

            .questionnaire-intro p {
                font-size: 1em;
            }

            .intro-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="container">
        <div class="questionnaire-container">
            <div class="questionnaire-intro">
                <h2><i class="fas fa-clipboard-list"></i> Votre Avis Compte !</h2>
                <p style="font-size: 1.3em; color: #008AAD; font-weight: bold;">
                    Participez à la construction de notre projet pour Vauvert
                </p>
            </div>

            <div class="intro-box">
                <h3><i class="fas fa-comments"></i> Pourquoi répondre à ce questionnaire ?</h3>
                <ul>
                    <li>Faites entendre votre voix sur les priorités pour Vauvert</li>
                    <li>Proposez vos idées et suggestions pour notre ville</li>
                    <li>Participez activement au projet démocratique</li>
                    <li>Contribuez à bâtir ensemble l'avenir de notre commune</li>
                </ul>
                <p style="margin-top: 20px; font-style: italic;">
                    Vos réponses sont anonymes et nous aident à construire un projet qui vous ressemble.
                </p>
            </div>

            <div class="iframe-container">
                <div class="iframe-wrapper">
                    <div class="loading-message" id="loadingMessage">
                        <i class="fas fa-spinner"></i>
                        <p>Chargement du questionnaire...</p>
            </div>
                    <iframe src="https://vauvert2026.frama.space/apps/forms/embed/sYNyoGsQJ4Xi9BML36H5SgNk" width="750" height="900"></iframe>
                </div>
            </div>

            <div class="alternative-link">
                <p><i class="fas fa-external-link-alt"></i> Le questionnaire ne s'affiche pas ?</p>
                <a href="https://vauvert2026.frama.space/apps/forms/s/YtrawnmHocAdx5HEEK89tMGX" target="_blank" rel="noopener">
                    Ouvrir le questionnaire dans une nouvelle fenêtre
                </a>
            </div>

            <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
                <h3 style="color: #008AAD; margin-bottom: 20px;">
                    <i class="fas fa-heart"></i> Merci de votre participation !
                </h3>
                <p style="font-size: 1.2em; color: #333; margin-bottom: 20px;">
                    Chaque contribution compte pour construire le Vauvert de demain
                </p>
                <p style="color: #666;">
                    Vous souhaitez aller plus loin ? <a href="contact.php" style="color: #FF2E7E; font-weight: bold; text-decoration: none;">Rejoignez notre équipe</a>
                </p>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script>
        // Gérer le message de chargement si l'iframe ne se charge pas
        setTimeout(function() {
            var loadingMsg = document.getElementById('loadingMessage');
            if (loadingMsg && loadingMsg.style.display !== 'none') {
                loadingMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i><p>Le questionnaire met du temps à charger...<br>Vous pouvez <a href="https://vauvert2026.frama.space/apps/forms/s/YtrawnmHocAdx5HEEK89tMGX" target="_blank" style="color: #FF2E7E;">ouvrir le lien direct</a></p>';
            }
        }, 5000);
    </script>
</body>
</html>
