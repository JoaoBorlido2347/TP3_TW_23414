<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}


include 'ligacao.php';


if (isset($_GET['id'])) {
    $animal_id = $_GET['id'];

    
    $sql = "SELECT * FROM animal WHERE id = ?";
    $stmt = $ligax->prepare($sql);
    $stmt->bind_param("i", $animal_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $animal = $result->fetch_assoc();
    } else {
        echo "Animal não encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do animal não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background2.css" rel="stylesheet">

</head>
<body>
<header class="bg-dark py-3">
    <div class="container ">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">  
                <h1 class="display-5">Animal</h1>
            </div>
            <div class="col-1"></div>
            <div class="col-7 text-end">
                <a href="Dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a id="profile-btn" href="profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3">Painel do Administrador</a>
                <?php endif; ?>
                <a href="Animal_display.php" class="btn btn-outline-light mt-3">Meus Animais</a>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
    </div>
</header>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4 bkc">
            <div class="img-container">
                <img src="<?php echo $animal['imagem_url']; ?>" class="img-fluid" alt="Animal Image">
            </div>
        </div>
        <div class="col-md-8 bkc">
            <div class="card-body bordered">
                <h5 class="card-title"><?php echo $animal['especie']; ?></h5>
                <p class="card-text">ID: <?php echo $animal['id']; ?></p>
                <p class="card-text">Nome: <?php echo $animal['nome']; ?></p>
                <p class="card-text">Espécie: <?php echo $animal['especie']; ?></p>
                <p class="card-text">Vacinado: <?php echo $animal['vacinado'] ? 'Sim' : 'Não'; ?></p>
                <p class="card-text">Tem Chip: <?php echo $animal['tem_chip'] ? 'Sim' : 'Não'; ?></p>
                <p class="card-text">Data de Adoção: <?php echo $animal['data_adocao']; ?></p>
                <a href="Animal_edit.php?id=<?php echo $animal['id']; ?>" class="btn btn-primary">Editar</a>
            </div>
        </div>
    </div>
</div>

<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">
        
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+YktP15p3HTVkEuHkXh90I2Lkugz+" crossorigin="anonymous"></script>
</body>
</html>
