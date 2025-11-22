<?php
$page = 'contact';
$title = 'Contact - Pour Vauvert, continuons d\'agir ENSEMBLE';
$config = json_decode(file_get_contents('config.json'), true);

// Traitement du formulaire
$message_sent = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $prenom = htmlspecialchars($_POST['prenom'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $telephone = htmlspecialchars($_POST['telephone'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Validation basique
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($message) && empty($phone)) {
        // Ici, vous pouvez ajouter l'envoi d'email ou l'enregistrement en base de données
        // Pour l'instant, on simule juste le succès

        // Exemple d'envoi d'email (à configurer selon votre serveur)

        $to = $config['site']['email'];
        $subject = "Nouveau message de contact - Vauvert Ensemble";
        $body = "Nom: $nom $prenom\nEmail: $email\nTéléphone: $telephone\n\nMessage:\n$message";
        $headers = "From: $email";

        mail($to, $subject, $body, $headers);

        $message_sent = true;

    } else {
        $error_message = 'Veuillez remplir tous les champs obligatoires.';
    }
}
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

            <?php if ($message_sent): ?>
    <!-- Message de succès -->
    <div class="notification-banner notification-success">
        <i class="fas fa-check-circle icon"></i>
        <h3>Merci pour votre message !</h3>
        <p>Nous avons bien reçu votre demande et nous vous répondrons dans les plus brefs délais.<br>
        À très bientôt !</p>
    </div>
    <br><br>
    <?php endif; ?>

    <?php if ($error): ?>
    <!-- Message d'erreur -->
    <div class="notification-banner notification-error">
        <i class="fas fa-exclamation-triangle icon"></i>
        <h3>Oups, une erreur s'est produite</h3>
        <p><?php echo $error_message; ?></p>
    </div>
    <br><br>
    <?php endif; ?>


        <h2><i class="fas fa-envelope"></i> Contact</h2>
        
        <p class="contact-intro">
            <i class="fas fa-users" style="color: #FF2E7E;"></i><br>
            Vous souhaitez nous rejoindre ?<br>
            Contactez-nous dès maintenant !
        </p>

        <div class="contact-form">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i> Nom *</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom"><i class="fas fa-user"></i> Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="telephone"><i class="fas fa-phone"></i> Téléphone</label>
                    <input type="tel" id="telephone" name="telephone">
                </div>

            		<input type="text" name="phone" class="hp-field">

                <div class="form-group">
                    <label for="message"><i class="fas fa-comment"></i> Votre message *</label>
                    <textarea id="message" name="message" required placeholder="Parlez-nous de votre motivation, de vos compétences, de vos idées pour Vauvert..."></textarea>
                </div>

                <div class="form-group" style="text-align: center;">
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations de contact supplémentaires -->
        <div style="margin-top: 60px;">
            <h3 style="color: #008AAD; text-align: center; margin-bottom: 40px;">
                Autres moyens de nous contacter
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <div style="background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%); padding: 30px; border-radius: 15px; text-align: center; color: white;">
                    <i class="fas fa-map-marker-alt" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="color: white; margin-bottom: 15px;">Adresse</h4>
                    <p><?php echo $config['permanence']['adresse']; ?></p>
                </div>

                <div style="background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%); padding: 30px; border-radius: 15px; text-align: center; color: white;">
                    <i class="fas fa-envelope" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="color: white; margin-bottom: 15px;">Email</h4>
                    <p>
                        <a href="mailto:<?php echo $config['site']['email']; ?>" style="color: white; text-decoration: underline;">
                            <?php echo $config['site']['email']; ?>
                        </a>
                    </p>
                </div>

                <div style="background: linear-gradient(135deg, #5DD9C1 0%, #7FE5D0 100%); padding: 30px; border-radius: 15px; text-align: center; color: white;">
                    <i class="fab fa-facebook-f" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <h4 style="color: white; margin-bottom: 15px;">Réseaux sociaux</h4>
                    <div class="social-links" style="margin-top: 15px;">
                        <a href="<?php echo $config['liens']['facebook']; ?>" target="_blank" style="background: white; color: #5DD9C1;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo $config['liens']['youtube']; ?>" target="_blank" style="background: white; color: #5DD9C1;">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="<?php echo $config['liens']['tiktok']; ?>" target="_blank" style="background: white; color: #5DD9C1;">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permanences -->
        <div style="background: #F0F0F0; padding: 40px; border-radius: 15px; margin-top: 60px; text-align: center;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">
                <i class="fas fa-calendar-alt"></i> Permanences
            </h3>
            <p style="font-size: 1.2em; color: #333; margin-bottom: 20px;">
                Rencontrez notre équipe lors de nos permanences
            </p>
            <p style="color: #FF2E7E; font-weight: bold; font-size: 1.1em;">
                <?php echo $config['permanence']['horaires']; ?><br>
                À la permanence de campagne - <?php echo $config['permanence']['adresse']; ?>
            </p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
