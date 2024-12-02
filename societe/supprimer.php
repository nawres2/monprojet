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

// Récupérer l'ID du produit à supprimer
$stock_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Vérifier si l'ID est valide
if ($stock_id > 0) {
    // Préparer la requête de suppression
    $sql = "DELETE FROM stocks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);
    $societe_id = intval($_SESSION['societe_id']); // Récupérer l'ID de la société depuis la session


    // Exécuter la requête
    if ($stmt->execute()) {
        // Rediriger après la suppression avec un message de confirmation
        $stmt->close();
        $conn->close();
        header("Location: stocks.php?idsoc=" . $societe_id);
        
        exit(); // Arrêter l'exécution après la redirection
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }

    $stmt->close();
} else {
    // Si l'ID est invalide, afficher un message approprié
    echo "<p>ID de produit invalide.</p>";
}

// Fermer la connexion
$conn->close();
?>
