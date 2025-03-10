<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "users");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM utilisateurs");

// Vérifier si la table contient des données
if ($result->num_rows == 0) {
    die("Aucune donnée trouvée dans la table utilisateurs.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="affichage.css">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des utilisateurs</h2>
    
    <div>
        <a href="affichage.php" class="btn btn-primary">List</a>
        <a href="add.php" class="btn btn-success">Add</a>
    </div>

    <br>

    <table border="1" width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Titre</th></th>
            <th>Profil</th>
            <th>Actions</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nom'] ?></td>
                <td><?= $row['prenom'] ?></td>
                <td><?= $row['titre'] ?></td>
                <td><?= $row['profile'] ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>">👁</a>
                    <a href="edit.php?id=<?= $row['id'] ?>">✏</a> 
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')">🗑</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
