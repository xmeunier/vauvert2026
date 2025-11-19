<header>
    <div class="header-container">
        <div class="logo-container">
            <div class="bubbles">
                <div class="chat-bubble"></div>
                <div class="chat-bubble"></div>
            </div>
            <div class="logo-image">
                <img src="vauvertEnsemble.png" alt="Vauvert Ensemble">
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?php echo ($page == 'accueil') ? 'active' : ''; ?>">Accueil</a></li>
                <li><a href="presentation.php" class="<?php echo ($page == 'presentation') ? 'active' : ''; ?>">Présentation</a></li>
                <li><a href="evenements.php" class="<?php echo ($page == 'evenements') ? 'active' : ''; ?>">Événements</a></li>
                <li><a href="contact.php" class="<?php echo ($page == 'contact') ? 'active' : ''; ?>">Contact</a></li>
                <li><a href="votez.php" class="<?php echo ($page == 'votez') ? 'active' : ''; ?>">Votez pour gagner</a></li>
            </ul>
        </nav>
    </div>
</header>
