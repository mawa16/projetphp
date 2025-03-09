<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
    <?php
session_start();
$conn = mysqli_connect("localhost", "root", "161876", "users");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe
    $sql = "SELECT * FROM utilisateurs WHERE login = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Vérification du mot de passe hashé en MD5
    if ($user && md5($password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        
        header("Location: affichage.php"); // Rediriger vers le tableau de bord
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<div class="login-container">
    <h2>Connexion</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="login" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>

