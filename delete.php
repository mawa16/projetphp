<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "users");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécuriser l'ID

    // Vérification de l'existence de l'utilisateur avant la suppression
    $checkSql = "SELECT * FROM utilisateurs WHERE id = ?";
    if ($stmtCheck = $conn->prepare($checkSql)) {
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows == 0) {
            die("Utilisateur introuvable.");
        }
        $stmtCheck->close();
    }

    // Suppression de l'utilisateur
    $sql = "DELETE FROM utilisateurs WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Redirection avec un paramètre de succès
            header("Location: affichage.php?success=1");
            exit();
        } else {
            echo "Erreur lors de la suppression de l'utilisateur : " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête : " . $conn->error;
    }
}

$conn->close();
?>
