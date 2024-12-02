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

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation et récupération des données du formulaire
    $societe_id = isset($_POST['societe_id']) ? intval($_POST['societe_id']) : 0;
    $nom_produit = isset($_POST['nom_produit']) ? trim($_POST['nom_produit']) : '';
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 0;
    $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0.0;

    // Vérifier que tous les champs nécessaires sont remplis
    if ($societe_id > 0 && !empty($nom_produit) && $quantite > 0 && $prix > 0) {
        // Préparer la requête pour insérer un nouveau produit
        $sql = "INSERT INTO stocks (societe_id, nom_produit, quantite, prix) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erreur dans la préparation de la requête : " . $conn->error);
        }

        // Lier les paramètres
        $stmt->bind_param("isid", $societe_id, $nom_produit, $quantite, $prix);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Rediriger vers la page de gestion des stocks de la société après ajout
            header("Location: stocks.php?idsoc=" . $societe_id);
            exit();
        } else {
            echo "Erreur lors de l'ajout du produit : " . $stmt->error;
        }

        // Fermer la requête préparée
        $stmt->close();
    } else {
        echo "<p>Veuillez remplir tous les champs correctement.</p>";
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
