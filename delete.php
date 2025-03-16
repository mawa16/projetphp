
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "161876", "users");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécuriser l'ID
    $sql = "DELETE FROM utilisateurs WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: affichage.php?success=1");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
