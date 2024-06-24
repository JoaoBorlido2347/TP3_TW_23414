<?php
session_start();
include 'ligacao.php'; 


if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}


$username = $_SESSION['username'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Usuário';
$user_id = $_SESSION['user_id']; 


$stmt = $ligax->prepare("SELECT id, especie, imagem_url FROM animal WHERE id_usuario = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$animals = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Meus Animais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background2.css" rel="stylesheet">
</head>
<body>

<header class="bg-dark py-3">
    <div class="container ">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">
                <h1 class="display-5">Adopta Animais</h1>
                <p class="lead fw-normal text-white-50 mb-0">Cães, Gatos e Raposas!</p>
                <p>Bem-vindo, <?php echo htmlspecialchars($username); ?>!</p>
            </div>
            <div class="col-2"></div>
            <div class="col-6 text-end">
            <a href="Dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a id="profile-btn" href="profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <?php if ($role === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3">Painel do Administrador</a>
                <?php endif; ?>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
    </div>
</header>

<section class="py-5">
    <div class="container px-4 px-lg-5">
        <div class="row row-cols-xl-4">
        <?php foreach ($animals as $animal): ?>
                <div class="col mb-5">
                    <div class="card" style="min-height: 500px;">
                        <img src="<?php echo htmlspecialchars($animal['imagem_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($animal['especie']); ?>" style="object-fit: cover; height: 350px;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($animal['especie']); ?></h5>
                        </div>
                        <div class="card-footer">
                            <a href="animal.php?id=<?php echo htmlspecialchars($animal['id']); ?>" class="btn btn-primary">Ver Detalhes</a>
                            <a href="delete_animal.php?id=<?php echo htmlspecialchars($animal['id']); ?>" class="btn btn-danger">Excluir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

