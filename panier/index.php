<?php
session_start(); // Démarrer la session pour l'utilisateur

// Inclure la configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

try {
    // Création de l'objet PDO pour la connexion à la base de données
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les produits depuis la table 'produits' (ajustez le nom de la table et les colonnes selon votre base de données)
    $query = "SELECT * FROM stocks";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        button {
            padding: 10px 20px;
            background-color: #1ab188;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #148a6f;
        }

        .produits {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .product-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            margin: 20px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        #sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            transition: right 0.3s ease;
        }

        #sidebar.open {
            right: 0;
        }

        #closeSidebarBtn {
            font-size: 30px;
            cursor: pointer;
            color: #333;
        }

        #panierItems {
            margin-top: 20px;
        }

    </style>
</head>
<body>

<!-- Bouton pour afficher le panier -->
<button id="openSidebarBtn">Afficher le Panier</button>

<!-- Affichage des produits depuis la base de données -->
<div class="produits">
    <?php foreach ($produits as $produit): ?>
        <div class="product-card">
            <img src="images/<?= $produit['image'] ?>" alt="<?= $produit['nom_produit'] ?>" />
            <h3><?= $produit['nom_produit'] ?></h3>
            <p>Prix : <?= $produit['prix'] ?>€</p>
            <form action="panier.php" method="POST">
                <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                <button type="submit" class="add-to-cart-btn">Ajouter au panier</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<!-- Sidebar du panier -->
<div id="sidebar">
    <span id="closeSidebarBtn">&times;</span>
    <h3>Votre Panier</h3>
    <div id="panierItems">
        <!-- Les éléments du panier seront ajoutés ici via PHP -->
        <?php
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $id => $item) {
                echo "<p>Produit {$id} : Quantité: {$item['quantite']}</p>";
            }
        } else {
            echo "<p>Le panier est vide.</p>";
        }
        ?>
    </div>
</div>

<script src="script.js"></script> <!-- Lien vers le fichier JavaScript -->
</body>
</html>
