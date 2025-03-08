<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion directe à la base de données
    $conn = mysqli_connect("localhost", "root", "161876", "users");
    
    // Vérification de la connexion
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = md5($_POST['password']);
    
    // Gestion de l'upload de l'image
    $target_dir = "profile/";
    $target_file = $target_dir . basename($_FILES["profile"]["name"]);
    move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file);
    
    // Insertion dans la base de données
    $sql = "INSERT INTO utilisateurs (prenom, nom, titre, login, password, profile) 
            VALUES ('$prenom', '$nom', '$titre', '$login', '$password', '$target_file')";
    
    if (mysqli_query($conn, $sql)) {
        $success = true; // Si l'insertion est réussie
    } else {
        $success = false; // Si l'insertion échoue
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: affichage.php");
        exit();
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }

    // Fermeture de la connexion
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
     <link rel="stylesheet" href="add.css">
    <script>
        // Vérification si l'insertion a réussi ou échoué
        <?php if ($success): ?>
            alert("Utilisateur ajouté avec succès !");
        <?php else: ?>
            alert("Erreur lors de l'ajout de l'utilisateur.");
        <?php endif; ?>
    </script>
</head>
<body>
    <nav>
        <a href="affichage.php">Home</a>
        <a href="#">Administration</a>
        <a href="login.php">Login</a>
        <a href="affichage.php">List</a>
        <a href="add.php">Add</a>
    </nav>
    <div class="container">
        <h2>Adding user</h2>
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" required>
            
            <label for="nom">Nom</label>
            <input type="text" name="nom" required>
            
            <label for="titre">Titre</label>
            <select name="titre" required>
                <option value="">Choisis un titre</option>
                <option value="Mr">Mr</option>
                <option value="Ms">Ms</option>
                <option value="Dr">Dr</option>
                <option value="Prof">Prof</option>
            </select>
            
            <label for="login">Login</label>
            <input type="text" name="login" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" required>
            
            <label for="profile">Profile Picture</label>
            <input type="file" name="profile" accept="image/*" required>
            
            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
