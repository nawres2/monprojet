<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

session_start();

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les produits
    $stmt = $pdo->query("SELECT id, nom_produit, prix, quantite, image FROM stocks");
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gestion du panier
    if (isset($_POST['produit_id'])) {
        $produit_id = $_POST['produit_id'];

        // Vérifier la quantité
        $stmt = $pdo->prepare("SELECT * FROM stocks WHERE id = :id");
        $stmt->bindParam(':id', $produit_id);
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produit['quantite'] > 0) {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            $panier = $_SESSION['panier'];
            if (isset($panier[$produit_id])) {
                $panier[$produit_id]['quantite'] += 1;
            } else {
                $panier[$produit_id] = [
                    'nom_produit' => $produit['nom_produit'],
                    'prix' => $produit['prix'],
                    'quantite' => 1
                ];
            }

            $_SESSION['panier'] = $panier;

            // Mise à jour de la quantité
            $stmt = $pdo->prepare("UPDATE stocks SET quantite = quantite - 1 WHERE id = :id");
            $stmt->bindParam(':id', $produit_id);
            $stmt->execute();
        } else {
            $rupture_stock = true;
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks avec Panier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 250px;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
        .product-price {
            color: #e60000;
            font-size: 16px;
            margin: 10px 0;
        }
        .product-quantity {
            font-size: 14px;
            color: #666;
        }
        .low-stock {
            color: red;
        }
        .add-to-cart-btn {
            background: #1ab188;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .add-to-cart-btn:hover {
            background: #148a6f;
        }

        /* Sidebar panier */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: #ffffff;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            transform: translateX(100%);
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .sidebar-header h2 {
            font-size: 20px;
            margin: 0;
        }
        .sidebar-header button {
            background: #e60000;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .cart-item p {
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f9f9f9;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 250px;
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
        .product-price {
            color: #e60000;
            font-size: 16px;
            margin: 10px 0;
        }
        .add-to-cart-btn {
            background: #1ab188;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .add-to-cart-btn:hover {
            background: #148a6f;
        }

        /* Sidebar panier */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 350px;
            height: 100%;
            background: white;
            box-shadow: -3px 0 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .sidebar-header h2 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .cart-item .info {
            flex: 1;
            margin-left: 10px;
        }
        .cart-item .actions button {
            background: #1ab188;
            color: white;
            border: none;
            border-radius: 5px;
            width: 30px;
            height: 30px;
            font-size: 16px;
            cursor: pointer;
        }
        .cart-item .actions button:hover {
            background: #148a6f;
        }
    </style>
</head>
<body>
    <button id="toggleCartBtn" style="position: fixed; top: 20px; right: 20px; background: #1ab188; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Panier</button>
    <div class="container">
        <?php foreach ($stocks as $stock): ?>
        <div class="product-card">
            <img src="<?= htmlspecialchars($stock['image']) ?>" alt="Produit">
            <h3 class="product-name"><?= htmlspecialchars($stock['nom_produit']) ?></h3>
            <p class="product-price"><?= htmlspecialchars($stock['prix']) ?> €</p>
            <p class="product-quantity <?= $stock['quantite'] <= 5 ? 'low-stock' : '' ?>">
                Quantité : <?= htmlspecialchars($stock['quantite']) ?>
            </p>
            <form method="POST">
                <input type="hidden" name="produit_id" value="<?= $stock['id'] ?>">
                <button class="add-to-cart-btn" type="submit">Ajouter au panier</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Sidebar panier -->
    <div class="sidebar" id="cartSidebar">
        <div class="sidebar-header">
            <h2>Mon Panier</h2>
            <button id="closeCartBtn">X</button>
        </div>
        <div>
            <?php if (!empty($_SESSION['panier'])): ?>
                <?php foreach ($_SESSION['panier'] as $id => $produit): ?>
                <div class="cart-item">
                    <img src="<?= $produit['image'] ?>" alt="Produit">
                    <div class="info">
                        <p><?= htmlspecialchars($produit['nom_produit']) ?></p>
                        <p><?= $produit['prix'] ?> DT</p>
                        <p>Quantité : <?= $produit['quantite'] ?></p>
                    </div>
                    <div class="actions">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="produit_id" value="<?= $id ?>">
                            <button type="submit" name="action" value="augmenter">+</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="produit_id" value="<?= $id ?>">
                            <button type="submit" name="action" value="diminuer">-</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Votre panier est vide.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifiez si une action a été envoyée
    if (isset($_POST['action']) && isset($_POST['produit_id'])) {
        $produit_id = $_POST['produit_id'];
        $action = $_POST['action'];

        // Récupérez le produit depuis la base pour connaître la quantité en stock
        $stmt = $pdo->prepare("SELECT * FROM stocks WHERE id = :id");
        $stmt->bindParam(':id', $produit_id, PDO::PARAM_INT);
        $stmt->execute();
        $produit_stock = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit_stock) {
            // Si le produit n'existe pas, retournez une erreur
            echo "Produit non trouvé.";
            exit;
        }

        // Vérifiez si le panier existe
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        $panier = &$_SESSION['panier'];

        // Actions pour "+" et "-"
        if ($action === 'augmenter') {
            // Augmente la quantité dans le panier si possible
            if ($produit_stock['quantite'] > 0) {
                if (isset($panier[$produit_id])) {
                    $panier[$produit_id]['quantite'] += 1;
                } else {
                    $panier[$produit_id] = [
                        'nom_produit' => $produit_stock['nom_produit'],
                        'prix' => $produit_stock['prix'],
                        'quantite' => 1,
                        'image' => $produit_stock['image']
                    ];
                }

                // Réduisez la quantité en stock
                $stmt = $pdo->prepare("UPDATE stocks SET quantite = quantite - 1 WHERE id = :id");
                $stmt->bindParam(':id', $produit_id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                echo "Stock insuffisant.";
            }
        } elseif ($action === 'diminuer') {
            // Diminue la quantité dans le panier si possible
            if (isset($panier[$produit_id])) {
                $panier[$produit_id]['quantite'] -= 1;

                // Supprimez le produit du panier si la quantité est 0
                if ($panier[$produit_id]['quantite'] <= 0) {
                    unset($panier[$produit_id]);
                }

                // Augmentez la quantité en stock
                $stmt = $pdo->prepare("UPDATE stocks SET quantite = quantite + 1 WHERE id = :id");
                $stmt->bindParam(':id', $produit_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
}
?>


    <script>
        const cartSidebar = document.getElementById('cartSidebar');
        const toggleCartBtn = document.getElementById('toggleCartBtn');
        const closeCartBtn = document.getElementById('closeCartBtn');

        toggleCartBtn.addEventListener('click', () => {
            cartSidebar.classList.add('open');
        });

        closeCartBtn.addEventListener('click', () => {
            cartSidebar.classList.remove('open');
        });
    </script>
</body>
</html>
