<?php
session_start(); // Activer la session

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

// Initialisation des variables
$error_message = "";

// Vérifier si le formulaire a été soumis pour modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nom_produit = htmlspecialchars(trim($_POST['nom_produit']));
    $quantite = intval($_POST['quantite']);
    $prix = floatval($_POST['prix']);
    $societe_id = intval($_SESSION['societe_id']); // Récupérer l'ID de la société depuis la session

    // Vérifier les données avant la mise à jour
    if ($id > 0 && !empty($nom_produit) && $quantite >= 0 && $prix >= 0) {
        // Requête de mise à jour
        $sql = "UPDATE stocks SET nom_produit = ?, quantite = ?, prix = ? WHERE id = ? AND societe_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidii", $nom_produit, $quantite, $prix, $id, $societe_id);

        if ($stmt->execute()) {
            // Redirection vers stocks.php après modification réussie
            header("Location: stocks.php?idsoc=" . $societe_id);
            exit();
        } else {
            $error_message = "Erreur lors de la modification: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error_message = "Données invalides fournies.";
    }
}

// Récupérer l'ID du produit à modifier depuis l'URL
$stock_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les informations du produit si l'ID est valide
$product = null;
if ($stock_id > 0) {
    $sql = "SELECT * FROM stocks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Enregistrer l'ID de la société dans la session
    if ($product) {
        $_SESSION['societe_id'] = $product['societe_id'];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .error {
            color: red;
        }
        form {
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php

// Inclure la navbar si ce n'est pas la page login.php
if (basename($_SERVER['PHP_SELF']) !== 'signin.php') {
    include('../navbar/navbar.php');
}
?>
    <h2>Modifier un produit</h2>

    <?php if ($error_message): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($product): ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            
            <label for="nom_produit">Nom du produit:</label>
            <input type="text" name="nom_produit" value="<?php echo htmlspecialchars($product['nom_produit']); ?>" required><br>

            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" value="<?php echo $product['quantite']; ?>" required><br>

            <label for="prix">Prix:</label>
            <input type="number" name="prix" value="<?php echo $product['prix']; ?>" step="1" required><br>

            <input type="submit" value="Modifier">
        </form>
    <?php else: ?>
        <p>Produit introuvable ou ID invalide.</p>
    <?php endif; ?>
</body>
</html>
