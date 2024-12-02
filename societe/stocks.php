<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stocks</title>
    <link rel="stylesheet" href="stocks.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f4f4f4;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        form input, form label {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
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

<?php





// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("<p>La connexion à la base de données a échoué: " . $conn->connect_error . "</p>");
}

// Récupérer l'ID de la société depuis l'URL
$societe_id = isset($_GET['idsoc']) ? intval($_GET['idsoc']) : 0;

// Si l'ID de la société est valide, afficher les stocks
if ($societe_id > 0) {
    // Requête pour récupérer les stocks
    $sql = "SELECT * FROM stocks WHERE societe_id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $societe_id);
    $stmt->execute();
    $result = $stmt->get_result();



    echo "<h1>Gestion des stocks de la société #$societe_id</h1>";

    if ($result->num_rows > 0) {
        // Afficher le tableau des stocks
        echo "<table>";
        echo "<tr><th>Nom du produit</th><th>Quantité</th><th>Prix</th><th>Actions</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nom_produit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['quantite']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prix']) . " €</td>";
            echo "<td>
                    <a href='modifier.php?id=" . $row['id'] . "' class='action-link'>Modifier</a> | 
        <a href='supprimer.php?id=" . $row['id'] . "&societe_id=" . $societe_id . "' class='action-link'>Supprimer</a>
                    
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Aucun stock trouvé pour cette société.</p>";
    }

    // Formulaire pour ajouter un produit
    echo "
    <h2>Ajouter un produit</h2>
    <form action='ajouter.php' method='post'>
        <input type='hidden' name='societe_id' value='$societe_id'>
        <label for='nom_produit'>Nom du produit:</label>
        <input type='text' name='nom_produit' required>

        <label for='quantite'>Quantité:</label>
        <input type='number' name='quantite' required>

        <label for='prix'>Prix:</label>
        <input type='number' name='prix' step='0.01' required>

        <input type='submit' value='Ajouter'>
    </form>";
} else {
    echo "<h1>Gestion des stocks</h1>";
    echo "<p>ID de société invalide ou non spécifié.</p>";
}

// Fermer la connexion
$conn->close();
?>

</body>
</html>
