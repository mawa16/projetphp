<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "users");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifie si l'ID de l'utilisateur est passé dans l'URL
if (!isset($_GET['id'])) {
    die("Aucun utilisateur sélectionné.");
}

// Récupère l'ID de l'utilisateur et le rend sûr
$id = intval($_GET['id']);

// Requête pour obtenir les données de l'utilisateur
$result = $conn->query("SELECT * FROM utilisateurs WHERE id = $id");

// Vérifie si l'utilisateur existe
if ($result->num_rows == 0) {
    die("Utilisateur introuvable.");
}

// Récupère les données de l'utilisateur
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'utilisateur</title>
    <link rel="stylesheet" href="view.css"> <!-- Assurez-vous d'avoir ce fichier CSS -->
</head>
<body>

<div class="container">
    <div class="user-info">
        <h2><?= htmlspecialchars($user['titre'] . " " . $user['nom'] . " " . $user['prenom']) ?></h2>
        <p>Login : <?= htmlspecialchars($user['login']) ?></p>
    </div>

    <!-- Affichage de la photo de profil -->
    <div class="profile-picture">
        <?php
        // Vérifie si le chemin de l'image existe
        $image_path = $user['profile'];

        // Débogage : Afficher le chemin de l'image pour vérifier si c'est correct
        echo "Chemin de l'image : " . $image_path . "<br>";

        // Vérifie si l'image existe et est un fichier valide
        if (!empty($image_path) && file_exists($image_path)) {
            echo '<img src="' . htmlspecialchars($image_path) . '" alt="Photo de profil" class="user-image">';
        } else {
            // Si l'image n'existe pas ou est invalide, affiche une image par défaut
            echo '<img src="default-profile.jpg" alt="Photo de profil par défaut" class="user-image">';
        }
        ?>
    </div>

    <!-- Bouton pour revenir à la liste des utilisateurs -->
    <a href="affichage.php" class="back-button">← Retour</a>
</div>

</body>
</html>
