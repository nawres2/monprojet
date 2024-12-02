<?php



// Connexion à la base de données
$host = 'localhost';
$dbname = 'user_database';
$username = 'root';  // Remplacez par votre utilisateur MySQL
$password = '';      // Remplacez par votre mot de passe MySQL

// Déclaration des variables pour les messages d'erreur ou de succès
$error_message = '';
$success_message = '';

// Processus de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Connexion (login)
        if (isset($_POST['action']) && $_POST['action'] == 'signin') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Requête pour vérifier l'existence de l'utilisateur dans la base de données
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            session_start(); // Toujours démarrer la session avant d'accéder ou de définir des données de session

            if ($user && $user['password'] === $password) {
                // Authentification réussie
                $_SESSION['userid'] = $user['id']; // Remplacez 'id' par le nom de la colonne correspondant à l'ID de l'utilisateur
                $success_message = 'Connexion réussie. Redirection vers la page des sociétés...';
                header('Location: ../societe/societe.php'); // Redirection vers la page des sociétés
                exit();
            } else {
                $error_message = 'Email ou mot de passe incorrect.';
            }
        }

        if (isset($_POST['action']) && $_POST['action'] == 'signup') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (empty($name) || empty($email) || empty($password)) {
                $error_message = "Tous les champs sont obligatoires.";
            } else {
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $error_message = "Email déjà utilisé.";
                } else {
                    // Insérer l'utilisateur dans la base de données
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    if ($stmt->execute([$name, $email, $password])) {
                        $success_message = "Inscription réussie !";
                    } else {
                        $error_message = "Erreur lors de l'inscription.";
                    }
                }
            }
        }
    } catch (PDOException $e) {
        $error_message = 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link rel="stylesheet" href="auth.css"> <!-- Lien vers le fichier CSS -->
    <style>
        body {
	font-family: Arial, sans-serif;
	background-color: #f4f4f4;
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100vh;
	margin: 0;
}
.auth-container {
	width: 100%;
	max-width: 400px;
	background-color: white;
	padding: 20px;
	border-radius: 8px;
	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.auth-container h2 {
	text-align: center;
}
.auth-container form {
	display: flex;
	flex-direction: column;
}
.auth-container label {
	margin-bottom: 5px;
}
.auth-container input {
	padding: 10px;
	margin-bottom: 15px;
	border: 1px solid #ddd;
	border-radius: 4px;
}
.auth-container button {
	padding: 10px;
	background-color: #28a745;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;
}
.auth-container button:hover {
	background-color: #218838;
}
.auth-container .toggle-links {
	text-align: center;
	margin-top: 15px;
}
.auth-container .toggle-links a {
	color: #007bff;
	text-decoration: none;
}
.auth-container .toggle-links a:hover {
	text-decoration: underline;
}
</style>

</head>
<body>

<div class="auth-container">
    <h2>Authentification</h2>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php elseif ($success_message): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <!-- Formulaire de connexion -->
    <div id="signin-form" style="display: <?php echo !isset($_POST['action']) || $_POST['action'] == 'signin' ? 'block' : 'none'; ?>;">
        <form method="POST">
            <input type="hidden" name="action" value="signin">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <div class="toggle-links">
            <p>Pas encore de compte? <a href="#" onclick="toggleForm('signup')">S'inscrire</a></p>
        </div>
    </div>

    <!-- Formulaire d'inscription -->
    <div id="signup-form" style="display: <?php echo isset($_POST['action']) && $_POST['action'] == 'signup' ? 'block' : 'none'; ?>;">
        <form method="POST">
            <input type="hidden" name="action" value="signup">
            <label for="name">Nom:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <div class="toggle-links">
            <p>Vous avez déjà un compte? <a href="#" onclick="toggleForm('signin')">Se connecter</a></p>
        </div>
    </div>
</div>
<script src="auth.js"></script> <!-- Lien vers le fichier JS -->


</body>
</html>
