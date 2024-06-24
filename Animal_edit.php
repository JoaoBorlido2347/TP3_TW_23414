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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $vacinado = isset($_POST['vacinado']) ? 1 : 0;
    $tem_chip = isset($_POST['tem_chip']) ? 1 : 0;
    $data_adocao = $_POST['data_adocao'];
    $imagem_url = $_POST['imagem_url'];

    $sql = "UPDATE animal SET nome = ?, especie = ?, vacinado = ?, tem_chip = ?, data_adocao = ?, imagem_url = ? WHERE id = ?";
    $stmt = $ligax->prepare($sql);
    $stmt->bind_param("ssisssi", $nome, $especie, $vacinado, $tem_chip, $data_adocao, $imagem_url, $animal_id);

    if ($stmt->execute()) {
        header("Location: Animal_display.php?id=" . $animal_id);
        exit;
    } else {
        echo "Erro ao atualizar os detalhes do animal.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background2.css" rel="stylesheet">
</head>
<body calls="bg">
<header class="bg-dark py-3">
    <div class="container px-4 px-lg-5 my-3">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">
                <h1 class="display-5">Adopta Animais</h1>
                <p class="lead fw-normal text-white-50 mb-0">Cães, Gatos e Raposas!</p>
            </div>
            <div class="col-2"></div>
            <div class="col-6 text-end">
                <a href="Dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a id="profile-btn" href="profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3">Painel do Administrador</a>
                <?php endif; ?>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="row justify-content-center">
        <p></p>
        <div class="card col-md-8">
            <div class="form-container">
                <h1 class="header-title text-center">Editar Animal</h1>
                <form method="post">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $animal['nome']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="especie" class="form-label">Espécie</label>
                        <input type="text" class="form-control" id="especie" name="especie" value="<?php echo $animal['especie']; ?>" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="vacinado" name="vacinado" <?php echo $animal['vacinado'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="vacinado">Vacinado</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="tem_chip" name="tem_chip" <?php echo $animal['tem_chip'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="tem_chip">Tem Chip</label>
                    </div>
                    <div class="mb-3">
                        <label for="data_adocao" class="form-label">Data de Adoção</label>
                        <input type="date" class="form-control" id="data_adocao" name="data_adocao" value="<?php echo $animal['data_adocao']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="imagem_url" class="form-label">Imagem URL</label>
                        <input type="text" class="form-control" id="imagem_url" name="imagem_url" value="<?php echo $animal['imagem_url']; ?>">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="Animal_display.php?id=<?php echo $animal_id; ?>" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
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