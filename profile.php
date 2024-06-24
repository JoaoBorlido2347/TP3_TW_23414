<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}


$user_id = $_SESSION['user_id']; 

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "canil";

$ligax = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($ligax->connect_error) {
    die("Falha na conexão com o banco de dados: " . $ligax->connect_error);
}

$ligax->set_charset("utf8");

$sql = "SELECT username, email, role, first_name, last_name, birthday, bio, address, extra_field FROM usuarios WHERE id = ?";
$stmt = $ligax->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $email = htmlspecialchars($user['email']);
    $role = htmlspecialchars($user['role']);
    $first_name = htmlspecialchars($user['first_name']);
    $last_name = htmlspecialchars($user['last_name']);
    $birthday = htmlspecialchars($user['birthday']);
    $bio = htmlspecialchars($user['bio']);
    $address = htmlspecialchars($user['address']);
    $extra_field = htmlspecialchars($user['extra_field']);
} else {
    echo "No user found";
    exit;
}

$stmt->close();
$ligax->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
</head>
<body>
<header class="bg-dark py-3">
    <div class="container ">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">  
                <h1 class="display-5">Perfil do Utilizador</h1>
                </div>
                <div class="col-1"></div>
            <div class="col-7 text-end">
            <a href="Dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a href="Animal_display.php" class="btn btn-outline-light mt-3">Meus Animais</a>
                <?php if ($role === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3">Painel do Administrador</a>
                <?php endif; ?>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
            </div>
        </div>
    </div>
</header>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Username: <?php echo $username; ?></h5>
                        <p class="card-text">Email: <?php echo $email; ?></p>
                        <p class="card-text">Tipo de User: <?php echo $role; ?></p>
                        <p class="card-text">Nome: <?php echo $first_name; ?></p>
                        <p class="card-text">Apelido: <?php echo $last_name; ?></p>
                        <p class="card-text">Aniversario: <?php echo $birthday; ?></p>
                        <p class="card-text">Bio: <?php echo $bio; ?></p>
                        <p class="card-text">Morada: <?php echo $address; ?></p>
                        <p class="card-text">Profissao: <?php echo $extra_field; ?></p>
                        <a href="update_profile.php" class="btn btn-primary">Atulizar Prefil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">
        
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
