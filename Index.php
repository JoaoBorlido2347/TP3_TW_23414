<?php
session_start();
include "./ligacao.php";


function login($username, $password) {
    global $ligax; 

    
    $username = mysqli_real_escape_string($ligax, $username);
    $password = mysqli_real_escape_string($ligax, $password);


    $query = "SELECT * FROM usuarios WHERE username='$username' AND password='$password'";
    $consulta = mysqli_query($ligax, $query);
    $registos_devolvidos = mysqli_num_rows($consulta);

    if ($registos_devolvidos == 1) {
        $row = mysqli_fetch_assoc($consulta);
 
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        return true;
    }

    return false;
}


function logout() {
    session_unset();
    session_destroy(); 
}


function isUserLoggedIn() {
    return isset($_SESSION['username']);
}


function hasPermission($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

  
    if (login($username, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        
        $error_message = 'Utilizador ou Password incorreta';
    }
}


if (isset($_POST['logout'])) {
    logout();
    $logout_message = 'Logout bem-sucedido!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoptar Animais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
</head>
<body >
<header class="bg-dark py-3">
    <div class="container px-4 px-lg-5 my-5"></div>
</header>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6"></div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <?php if (isUserLoggedIn()): ?>
                        <?php if (hasPermission('admin')): ?>
                            <h2>Bem-vindo, Administrador!</h2>
                        <?php else: ?>
                            <h2>Bem-vindo, Usuário Normal!</h2>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="submit" name="logout" value="Logout" class="btn btn-primary">
                        </form>
                    <?php else: ?>
                        <h2>Login</h2>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form id="login-form" method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nome de Utilizador:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="login" value="Login" class="btn btn-primary">Login</button>
                                <button id="inscreverBtn" type="button" class="btn btn-secondary">Inscrever</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white"></p></div>
</footer>

<script>
    document.getElementById('inscreverBtn').addEventListener('click', redirectToAnotherPage);
    function redirectToAnotherPage() {
        window.location.href = 'Inscrever.php';
    }
</script>

</body>
</html>
