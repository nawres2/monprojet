<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Sociétés</title>
    <link rel="stylesheet" href="societe.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

<?php

// Inclure la navbar si ce n'est pas la page login.php
if (basename($_SERVER['PHP_SELF']) !== 'signin.php') {
    include('../navbar/navbar.php');
}
?>
    <div class="project-container">
   

        <div class="grid-container">
            <!-- PHP: Affichage des sociétés -->
            <?php







// Connexion à la base de données
$servername = "localhost";
$username = "root";  // Votre nom d'utilisateur MySQL
$password = "";      // Votre mot de passe MySQL
$dbname = "user_database";  // Nom de la base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Requête pour récupérer les informations de la société
$sql = "SELECT * FROM societe";
$result = $conn->query($sql);

// Vérifier si des sociétés sont retournées
session_start();


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['userid'])) {
    echo "Veuillez vous connecter pour accéder à vos sociétés.";
    exit;
}

$userId = $_SESSION['userid']; // Récupérer l'ID utilisateur connecté

// Préparer et exécuter la requête pour récupérer les sociétés associées à l'utilisateur
$sql = "SELECT * FROM societe WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si des sociétés sont retournées
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='grid-item'>";
        echo "<a href='stocks.php?idsoc=" . htmlspecialchars($row['idsoc']) . "' class='grid-link'>";
        echo "<div class='grid-content'>";
        echo "<h2>" . htmlspecialchars($row['nom']) . "</h2>";
        echo "<p>Secteur: " . htmlspecialchars($row['secteur']) . "</p>";
        echo "<p>Année de création: " . htmlspecialchars($row['annee_creation']) . "</p>";
        echo "<p>Services: " . htmlspecialchars($row['services']) . "</p>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
} else {
    echo "<p>Aucune société trouvée pour cet utilisateur.</p>";
}

// Fermer la connexion
$conn->close();
?>

        </div> <!-- Fermeture du grid-container -->

    </div>  <!-- Fermeture du project-container -->

</body>
</html>  