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
<div class="container">
    <h2>Liste des utilisateurs</h2>
    
    <div class="actions">
        <a href="affichage.php" class="btn btn-primary">List</a>
        <a href="add.php" class="btn btn-success">Add</a>
    </div>

    <br>

    <table class="user-table">
        <tr>
            <th>ID</th>
             <th>Titre</th>
            <th>Nom</th>
            <th>Prénom</th>
           
            <th>Actions</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                 <td><?= $row['titre'] ?></td>
                <td><?= $row['nom'] ?></td>
                <td><?= $row['prenom'] ?></td>
               
                <td class="action-icons">
                    <a href="view.php?id=<?= $row['id'] ?>" class="view-icon">👁</a>

                    <a href="modification.php?id=<?= $row['id'] ?>" class="edit-icon">✏</a> 
                    <a href="delete.php?id=<?= $row['id'] ?>" class="delete-icon" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')">🗑</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
