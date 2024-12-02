<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: #fff;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease-in-out;
            padding: 20px;
            z-index: 999;
        }

        #sidebar.open {
            right: 0;
        }

        #sidebar h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .close-sidebar {
            font-size: 24px;
            cursor: pointer;
            position: absolute;
            top: 20px;
            left: 20px;
            color: #333;
        }

        .product {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .product-name {
            font-size: 16px;
        }

        .product-quantity {
            font-size: 14px;
            color: #888;
        }

        .add-to-cart-btn {
            background-color: #1ab188;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .add-to-cart-btn:hover {
            background-color: #148a6f;
        }
    </style>
</head>
<body>

<!-- Bouton pour ouvrir le panier -->
<button class="add-to-cart-btn" id="openSidebarBtn">Afficher le Panier</button>

<!-- Sidebar pour le panier -->
<div id="sidebar">
    <span class="close-sidebar" id="closeSidebarBtn">&times;</span>
    <h3>Votre Panier</h3>
    <div id="panierItems">
        <?php
        // Afficher les produits dans le panier
        if (!empty($panier)) {
            foreach ($panier as $produit_id => $item) {
                echo "<div class='product'>
                        <span class='product-name'>Produit $produit_id</span>
                        <span class='product-quantity'>Quantité: " . $item['quantite'] . "</span>
                      </div>";
            }
        } else {
            echo "<p>Le panier est vide.</p>";
        }
        ?>
    </div>
</div>

<!-- Liste des produits -->
<div class="container">
    <?php
    // Affichage des produits
    // Exemple de données pour les produits (ceci viendrait de la base de données)
    $produits = [
        1 => ['nom_produit' => 'Produit 1', 'prix' => 10],
        2 => ['nom_produit' => 'Produit 2', 'prix' => 20],
        3 => ['nom_produit' => 'Produit 3', 'prix' => 30],
    ];

    foreach ($produits as $id => $produit) {
        echo "
        <div class='product-card'>
            <img src='images/produit$id.jpg' alt='" . $produit['nom_produit'] . "' />
            <h3 class='product-name'>" . $produit['nom_produit'] . "</h3>
            <p class='product-price'>" . $produit['prix'] . "€</p>
            <form action='' method='POST'>
                <input type='hidden' name='produit_id' value='$id'>
                <button type='submit' class='add-to-cart-btn'>Ajouter au panier</button>
            </form>
        </div>";
    }
    ?>
</div>

<!-- JavaScript pour gérer l'ouverture et la fermeture du sidebar -->
<script>
    // Ouvrir le sidebar
    document.getElementById('openSidebarBtn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.add('open');
    });

    // Fermer le sidebar
    document.getElementById('closeSidebarBtn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('open');
    });
</script>

</body>
</html>
