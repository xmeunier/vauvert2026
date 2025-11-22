<?php
/**
 * Balises Meta pour le SEO
 * À inclure dans le <head> de chaque page
 *
 * Variables à définir AVANT d'inclure ce fichier :
 * - $page_title
 * - $page_description
 * - $page_keywords
 * - $page_image (optionnel)
 * - $page_url (optionnel)
 * - $page_type (optionnel)
 */

// Valeurs par défaut si non définies
$page_title = $page_title ?? 'Pour Vauvert, continuons d\'agir ENSEMBLE';
$page_description = $page_description ?? 'Jean DENAT et son équipe présentent leur projet pour les élections municipales 2026. Découvrez nos engagements pour Vauvert.';
$page_keywords = $page_keywords ?? $config["technique"]["mots_cles"];
$page_image = $page_image ?? 'https://vauvert2026.fr/vauvertEnsemble.png';
$page_url = $page_url ?? 'https://vauvert2026.fr' . $_SERVER['REQUEST_URI'];
$page_type = $page_type ?? 'website';
?>
<!-- Balises Meta de Base -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- SEO Essentiels -->
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
<meta name="author" content="Jean DENAT - Vauvert Ensemble">
<meta name="robots" content="index, follow">
<meta name="language" content="fr">
<meta name="revisit-after" content="7 days">
<meta name="rating" content="general">

<!-- Canonical URL (évite le duplicate content) -->
<link rel="canonical" href="<?php echo htmlspecialchars($page_url); ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?php echo htmlspecialchars($page_type); ?>">
<meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($page_image); ?>">
<meta property="og:url" content="<?php echo htmlspecialchars($page_url); ?>">
<meta property="og:site_name" content="Vauvert Ensemble">
<meta property="og:locale" content="fr_FR">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta name="twitter:image" content="<?php echo htmlspecialchars($page_image); ?>">

<!-- Geolocalisation -->
<meta name="geo.region" content="FR-30">
<meta name="geo.placename" content="Vauvert">
<meta name="geo.position" content="43.6917;4.2778">
<meta name="ICBM" content="43.6917, 4.2778">

<!-- Thème et Couleurs -->
<meta name="theme-color" content="#5DD9C1">
<meta name="msapplication-TileColor" content="#5DD9C1">
<meta name="apple-mobile-web-app-status-bar-style" content="#5DD9C1">

<!-- Manifest PWA -->
<link rel="manifest" href="/manifest.json">

<!-- Favicons -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/favicon-512.png">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
