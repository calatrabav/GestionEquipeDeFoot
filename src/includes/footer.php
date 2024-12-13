<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>À propos</h4>
            <p>Notre site propose une gestion simplifiée des matchs et joueurs de votre équipe de football. Pour toute question, contactez-nous.</p>
        </div>

        <div class="footer-section">
            <h4>Navigation</h4>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php?controller=joueurs&action=liste">Joueurs</a></li>
                <li><a href="index.php?controller=matchs&action=liste">Matchs</a></li>
                <li><a href="index.php?controller=contact&action=formulaire">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <p>&copy; <?= date('Y') ?> MonSite. Tous droits réservés.</p>
        </div>
    </div>

</footer>
