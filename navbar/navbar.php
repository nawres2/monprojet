<head>
    <style>
 /* Style général pour la navbar */
nav {
    background-color: #f8f9fa; /* Couleur claire pour le fond */
    padding: 15px 20px;       /* Espacement interne légèrement augmenté */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombre subtile pour un effet moderne */
    border-radius: 8px;       /* Coins légèrement arrondis */
    text-align: center;
    margin: 20px auto;        /* Centrage avec un peu d'espace autour */
    max-width: 80%;           /* Limiter la largeur de la navbar */
}

/* Style pour la liste des éléments dans la navbar */
nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: space-around; /* Répartir les éléments */
    align-items: center;          /* Aligner verticalement */
}

/* Style pour chaque élément de la liste */
nav ul li {
    margin: 0 10px; /* Espacement horizontal modéré */
}

/* Style des liens */
nav ul li a {
    color: #333;               /* Couleur de texte neutre */
    text-decoration: none;     /* Retirer la décoration initiale */
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 16px;
    padding: 5px 10px;         /* Ajouter un peu de remplissage */
    position: relative;        /* Nécessaire pour l'effet underline */
    transition: all 0.3s ease; /* Transition fluide pour les effets */
}

/* Effet de survol des liens */
nav ul li a:hover {
    color: #007bff; /* Couleur bleue élégante au survol */
}

nav ul li a::after {
    content: "";
    display: block;
    width: 0;
    height: 2px;
    background: #007bff; /* Ligne bleue au survol */
    transition: width 0.3s ease;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

/* Étendre la ligne au survol */
nav ul li a:hover::after {
    width: 100%; /* La ligne s'étend sur toute la largeur */
}

/* Style pour mobile (responsive design) */
@media (max-width: 768px) {
    nav ul {
        flex-direction: column;
        align-items: center;
    }

    nav ul li {
        margin-bottom: 15px;
    }

    nav ul li:last-child {
        margin-bottom: 0;
    }
}

</style >
</head>

<nav>
    <ul>
        <li><a href="../auth/signin.php">Accueil</a></li>
        <li><a href="about.php">À propos</a></li>
        <li><a href="societe.php">Sociéte</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="../panier/panier.php">panier</a></li>
        <li><a href="../user/interface.php">panier</a></li>

    </ul>
</nav>


