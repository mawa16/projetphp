<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "161876", "users");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Aucun utilisateur sélectionné.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM utilisateurs WHERE id = $id");

if ($result->num_rows == 0) {
    die("Utilisateur introuvable.");
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'utilisateur</title>
    <link rel="stylesheet" href="view.css">
</head>
<body>

<div class="container">
    <div class="user-info">
        <h2><?= htmlspecialchars($user['titre'] . " " . $user['nom'] . " " . $user['prenom']) ?></h2>
        <p>Login : <?= htmlspecialchars($user['login']) ?></p>
    </div>

    <img src="<?= htmlspecialchars($user['profile']) ?>" alt="Photo de l'utilisateur" class="user-image">

    <a href="affichage.php" class="back-button">← Retour</a>
</div>

</body>
</html>
