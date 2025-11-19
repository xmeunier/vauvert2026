<?php
$page = 'presentation';
$title = 'Présentation - Pour Vauvert, continuons d\'agir ENSEMBLE';
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
        <h2>Le Candidat</h2>
        
        <div class="team-grid" style="max-width: 800px; margin: 40px auto;">
            <div class="team-member">
                <img src="<?php echo $config['candidat']['photo']; ?>" alt="Jean DENAT">
                <div class="team-member-info">
                    <h4><?php echo $config['candidat']['nom']; ?></h4>
                    <p style="color: #FF2E7E; font-weight: bold; margin-bottom: 10px;"><?php echo $config['candidat']['fonction']; ?></p>
                    <p><?php echo $config['candidat']['mot_du_candidat']; ?></p>
                    <p style="margin-top: 15px; font-style: italic;">"Pour Vauvert, continuons d'agir ENSEMBLE vers un avenir meilleur."</p>
                </div>
            </div>
        </div>

        <h2 style="margin-top: 80px;">L'Équipe</h2>
        <p style="text-align: center; font-size: 1.2em; color: #008AAD; margin-bottom: 40px;">
            Une équipe engagée et proche des citoyens
        </p>

        <div class="team-grid">
          <?php foreach($config['equipe'] as $membre): ?>
          <div class="team-member">
            <img src="<?php echo $membre['photo']; ?>" 
               alt="<?php echo $membre['prenom'] . ' ' . $membre['nom']; ?>">
              <div class="team-member-info">
                <h4><?php echo $membre['prenom'] . ' ' . $membre['nom']; ?></h4>
                <p style="color: #FF2E7E; font-weight: bold;">
                  <?php echo $membre['delegation']; ?>
                </p>
                <p><?php echo $membre['description']; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div style="background: linear-gradient(135deg, #FFD500 0%, #FFE44D 100%); padding: 40px; border-radius: 15px; text-align: center; margin-top: 60px;">
            <h3 style="color: #008AAD; margin-bottom: 20px;">Rejoignez-nous !</h3>
            <p style="font-size: 1.2em; margin-bottom: 30px;">Ensemble, construisons l'avenir de Vauvert</p>
            <a href="contact.php" class="btn">Nous contacter</a>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
