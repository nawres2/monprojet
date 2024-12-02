
<?php

// Inclure la navbar si ce n'est pas la page login.php
if (basename($_SERVER['PHP_SELF']) !== 'signin.php') {
    include('../navbar/navbar.php');
}
?>

<?php
session_start(); // Démarrer la session pour l'utilisateur

// Vérifier si un produit est ajouté au panier
if (isset($_POST['produit_id'])) {
    $produit_id = $_POST['produit_id'];
    
    // Initialiser le panier si ce n'est pas déjà fait
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    
    // Ajouter le produit au panier ou augmenter la quantité si le produit est déjà dans le panier
    if (isset($_SESSION['panier'][$produit_id])) {
        $_SESSION['panier'][$produit_id]['quantite'] += 1;
    } else {
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "user_database";
        
        try {
            // Création de l'objet PDO
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Récupérer les informations du produit depuis la base de données
            $query = "SELECT * FROM produits WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $produit_id]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Ajouter le produit au panier
            $_SESSION['panier'][$produit_id] = [
                'quantite' => 1,
                'nom' => $produit['nom_produit'],
                'prix' => $produit['prix'],
                'image' => $produit['image']
            ];
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données: " . $e->getMessage();
        }
    }
}

// Rediriger vers la page principale
header('Location: index.php');
exit();
?>
4