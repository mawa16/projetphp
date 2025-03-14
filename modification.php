<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données (MySQLi)
$conn = mysqli_connect("localhost", "root", "", "users");

// Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupérer l'ID de l'utilisateur depuis l'URL
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Récupérer les informations de l'utilisateur à modifier
$query = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Si l'utilisateur n'existe pas, rediriger vers la liste des utilisateurs
if (!$user) {
    header("Location: liste_utilisateurs.php");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    
    // Si l'utilisateur n'a pas uploadé une nouvelle image, conserver l'image actuelle
    $profil = isset($user['profil']) ? $user['profil'] : ''; // Garder l'ancienne image si elle existe
    
    // Gestion de l'upload de l'image
    if (!empty($_FILES['profil']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["profil"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Vérifier les formats autorisés
        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowTypes)) {
            // Déplacer le fichier dans le dossier "uploads"
            if (move_uploaded_file($_FILES["profil"]["tmp_name"], $targetFilePath)) {
                // Mettre à jour le chemin de l'image dans la variable $profil
                $profil = $targetFilePath;
            } else {
                echo "Erreur lors du téléchargement du fichier.";
            }
        } else {
            echo "Seuls les fichiers JPG, JPEG, PNG ou GIF sont autorisés.";
        }
    }

    // Mettre à jour l'utilisateur dans la base de données
    $updateQuery = $conn->prepare("UPDATE utilisateurs SET titre = ?, nom = ?, prenom = ?, profile = ? WHERE id = ?");
    $updateQuery->bind_param("ssssi", $titre, $nom, $prenom, $profil, $id);
    
    // Exécuter la requête
    if ($updateQuery->execute()) {
        // Redirection après modification
        header("Location: affichage.php");
        exit();
    } else {
        echo "Erreur de mise à jour : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="modification.css">
</head>
<body>
    <h2>Modifier un utilisateur</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Titre :</label>
        <select name="titre">
            <option value="Mr" <?= ($user['titre'] == 'Mr') ? 'selected' : '' ?>>Mr</option>
            <option value="Ms" <?= ($user['titre'] == 'Ms') ? 'selected' : '' ?>>Ms</option>
            <option value="Dr" <?= ($user['titre'] == 'Dr') ? 'selected' : '' ?>>Dr</option>
            <option value="Prof" <?= ($user['titre'] == 'Prof') ? 'selected' : '' ?>>Prof</option>
        </select><br>

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required><br>

        <label>Photo de profil :</label>
        <input type="file" name="profil"><br>

        <?php if (!empty($user['profil'])): ?>
            <img src="<?= htmlspecialchars($user['profil']) ?>" width="100"><br>
        <?php endif; ?>

        <input type="submit" value="Modifier">
    </form>

</body>
</html>
