<?php
/**
 * Formate le texte en convertissant les balises personnalisées en HTML
 *
 * Balises supportées :
 * - **texte** : Met le texte en gras
 * - [texte](URL) : Crée un lien hypertexte
 *
 * @param string $text Le texte à formater
 * @return string Le texte formaté en HTML
 */
function format_text($text) {
    if (empty($text)) {
        return '';
    }

    // Échapper le HTML pour la sécurité
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    // Convertir les liens [texte](URL) en balises <a>
    // Pattern : [texte](URL)
    $text = preg_replace_callback(
        '/\[([^\]]+)\]\(([^\)]+)\)/',
        function($matches) {
            $link_text = $matches[1];
            $url = $matches[2];
            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $link_text . '</a>';
        },
        $text
    );

    // Convertir le gras **texte** en balises <strong>
    // Pattern : **texte**
    $text = preg_replace(
        '/\*\*([^\*]+)\*\*/',
        '<strong>$1</strong>',
        $text
    );

    // Convertir les retours à la ligne en <br>
    $text = nl2br($text);

    return $text;
}

/**
 * Formate récursivement tous les champs texte d'un tableau
 * Utile pour formater automatiquement tout le contenu du config.json
 *
 * @param array $data Le tableau à formater
 * @param array $excluded_keys Les clés à exclure du formatage (par défaut: photo, lien, video, etc.)
 * @return array Le tableau avec les valeurs formatées
 */
function format_config_recursive($data, $excluded_keys = ['photo', 'lien', 'video', 'email', 'qrcode', 'facebook', 'youtube', 'tiktok', 'questionnaire']) {
    if (is_array($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            // Ne pas formater les URLs et chemins de fichiers
            if (in_array($key, $excluded_keys)) {
                $result[$key] = $value;
            } else {
                $result[$key] = format_config_recursive($value, $excluded_keys);
            }
        }
        return $result;
    } elseif (is_string($data)) {
        return format_text($data);
    } else {
        return $data;
    }
}
?>
