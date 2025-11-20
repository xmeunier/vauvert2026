<?php
$page = 'votez';
$title = 'Votez pour gagner - Pour Vauvert, continuons d\'agir ENSEMBLE';
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
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="container">
        <h2><i class="fas fa-vote-yea"></i> Votez pour gagner</h2>
        
        <div class="vote-section">
            <div style="font-size: 1.3em; margin-bottom: 30px;">
                <p style="margin-bottom: 20px;">
                    <i class="fas fa-check-circle" style="color: #008AAD;"></i> 
                    Vous êtes citoyen français ou européen ?
                </p>
                <p style="margin-bottom: 20px;">
                    <i class="fas fa-check-circle" style="color: #008AAD;"></i> 
                    Vous avez plus de 18 ans ?
                </p>
            </div>
            
            <h3 style="color: #008AAD; font-size: 2em; margin-bottom: 30px;">
                Votre voix compte !
            </h3>
            
            <p class="important" style="font-size: 1.4em; margin-bottom: 30px;">
                Pensez à vous inscrire (ou à vérifier votre bonne inscription) sur les listes électorales avant le 6 février 2026.
            </p>
            
            <a href="https://www.service-public.fr/particuliers/vosdroits/R16396" class="btn" target="_blank" style="font-size: 1.3em; padding: 20px 50px;">
                <i class="fas fa-edit"></i> Lien pour s'inscrire
            </a>
        </div>

        <!-- Informations complémentaires -->
        <div style="margin-top: 60px;">
            <h3 style="color: #008AAD; text-align: center; margin-bottom: 40px;">Informations importantes</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fas fa-calendar-check" style="font-size: 3em; color: #5DD9C1;"></i>
                    </div>
                    <h4 style="color: #008AAD; text-align: center; margin-bottom: 15px;">Dates à retenir</h4>
                    <p style="text-align: center;">
                        <strong style="color: #FF2E7E;">1er tour :</strong> Dimanche 15 mars 2026<br>
                        <strong style="color: #FF2E7E;">2nd tour :</strong> Dimanche 22 mars 2026
                    </p>
                </div>

                <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fas fa-clock" style="font-size: 3em; color: #5DD9C1;"></i>
                    </div>
                    <h4 style="color: #008AAD; text-align: center; margin-bottom: 15px;">Date limite d'inscription sur les listes électorales</h4>
                    <p style="text-align: center;">
                        <strong style="color: #FF2E7E; font-size: 1.2em;">6 février 2026</strong><br>
                        Ne manquez pas cette échéance !
                    </p>
                </div>

                <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 3px 15px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fas fa-info-circle" style="font-size: 3em; color: #5DD9C1;"></i>
                    </div>
                    <h4 style="color: #008AAD; text-align: center; margin-bottom: 15px;">Vérifier mon inscription</h4>
                    <p style="text-align: center;">
                        Consultez le site<br>
                        <a href="https://www.service-public.fr/particuliers/vosdroits/services-en-ligne-et-formulaires/ISE" target="_blank" style="color: #FF2E7E; font-weight: bold;">service-public.fr</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Pourquoi voter -->
        <div style="background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%); padding: 50px 40px; border-radius: 20px; color: white; margin-top: 60px;">
            <h3 style="color: white; text-align: center; margin-bottom: 40px; font-size: 2em;">
                <i class="fas fa-heart"></i> Pourquoi voter ?
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 30px;">
                <div style="text-align: center;">
                    <i class="fas fa-users" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="margin-bottom: 10px;">Participer à la démocratie</h4>
                    <p>Votre vote est l'expression de votre citoyenneté</p>
                </div>
                
                <div style="text-align: center;">
                    <i class="fas fa-city" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="margin-bottom: 10px;">Choisir l'avenir de Vauvert</h4>
                    <p>Décidez de la direction que prendra notre ville</p>
                </div>
                
                <div style="text-align: center;">
                    <i class="fas fa-handshake" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="margin-bottom: 10px;">S'engager ensemble</h4>
                    <p>Ensemble, construisons le Vauvert de demain</p>
                </div>
            </div>
        </div>

        <!-- Procuration -->
        <div style="background: #F0F0F0; padding: 40px; border-radius: 15px; margin-top: 60px;">
            <h3 style="color: #008AAD; text-align: center; margin-bottom: 20px;">
                <i class="fas fa-file-signature"></i> Vous ne pouvez pas vous déplacer ?
            </h3>
            <p style="text-align: center; font-size: 1.2em; margin-bottom: 30px;">
                Pensez à faire une procuration ! C'est simple et rapide.
            </p>
            <div style="text-align: center;">
                <a href="https://www.maprocuration.gouv.fr/" class="btn" style="background-color: #008AAD;" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Faire une procuration en ligne
                </a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
