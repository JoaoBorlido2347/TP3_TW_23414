<?php
session_start();
include "ligacao.php";

// Function to check if the user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect if not admin
if (!isset($_SESSION['username']) || !isAdmin()) {
    header("Location: index.php");
    exit;
}

// Function to delete an animal
function deleteAnimal($animalId) {
    global $ligax;

    $stmt = $ligax->prepare("DELETE FROM animal WHERE id = ?");
    $stmt->bind_param("i", $animalId);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    return false;
}

// Check if form was submitted for deleting an animal
if (isset($_POST['deleteAnimal'])) {
    if (isset($_POST['animalId'])) {
        $animalId = $_POST['animalId'];

        if (deleteAnimal($animalId)) {
            echo "<script>alert('Animal Removido');</script>";
            // Redirect back to the same page to refresh the list
            header("Location: Admin_User_animal.php");
            exit;
        } else {
            echo "<script>alert('Falhou a Remover Animal');</script>";
        }
    } else {
        echo "<script>alert('Pedido Inválido');</script>";
    }
}

// Fetch animals associated with the selected user
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $query = "SELECT id, nome, especie, vacinado, tem_chip, data_adocao, imagem_url FROM animal WHERE id_usuario = ?";
    $stmt = $ligax->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User's Animals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
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
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Espécie</th>
                <th>Vacinado</th>
                <th>Tem Chip</th>
                <th>Data de Adoção</th>
                <th>Imagem</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['especie']; ?></td>
                    <td><?php echo $row['vacinado'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo $row['tem_chip'] ? 'Sim' : 'Não'; ?></td>
                    <td><?php echo $row['data_adocao']; ?></td>
                    <td><img src="<?php echo $row['imagem_url']; ?>" alt="Imagem do animal" style="max-width: 100px;"></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="animalId" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="deleteAnimal" value="Apagar" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">
        
        </p>
    </div>
</footer>
</body>
</html>
