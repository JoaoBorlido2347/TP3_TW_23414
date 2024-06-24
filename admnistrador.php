<?php
session_start();
include "ligacao.php";

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function updateRole($userId, $newRole) {
    global $ligax;

    $stmt = $ligax->prepare("UPDATE usuarios SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $newRole, $userId);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    return false;
}

function deleteUser($userId) {
    global $ligax;


    $stmt = $ligax->prepare("DELETE FROM animal WHERE id_usuario = ?");
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        $stmt->close();
        return false;
    }
    $stmt->close();


    $stmt = $ligax->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    return false;
}


if (!isset($_SESSION['username']) || !isAdmin()) {
    header("Location: index.php");
    exit;
}

$query = "SELECT id, username, role FROM usuarios";
$result = $ligax->query($query);
?>

<?php

if (isset($_POST['updateRole'])) {
    if (isset($_POST['userId'])) {
        $userId = $_POST['userId'];
        $newRole = $_POST['newRole'];

        if (updateRole($userId, $newRole)) {
            echo "<script>alert('Privelegio Atulizado!');</script>";
            header("Location: admnistrador.php");
            exit;
        } else {
            echo "<script>alert('Falhou a Atulizar Previlegio');</script>";
            header("Location: admnistrador.php");
            exit;
        }
    } else {
        echo "<script>alert('Pedido Invalido.');</script>";
        header("Location: admnistrador.php");
        exit;
    }
}

if (isset($_POST['deleteUser'])) {
    if (isset($_POST['userId'])) {
        $userId = $_POST['userId'];

        if (deleteUser($userId)) {
            echo "<script>alert('Utilizador Removido');</script>";
            header("Location: admnistrador.php");
            exit;
        } else {
            echo "<script>alert('Falhou a Remover Utilizador');</script>";
            header("Location: admnistrador.php");
            exit;
        }
    } else {
        echo "<script>alert('Pedido Invalido..');</script>";
        header("Location: admnistrador.php");
        exit;
    }
}

$ligax->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
    <title>Admin</title>
</head>
<body>
<header class="bg-dark py-3">
    <div class="container ">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">
                <h1 class="display-5">Adopta Animais</h1>
                <p class="lead fw-normal text-white-50 mb-0">Cães, Gatos e Raposas!</p>
            </div>
            <div class="col-3"></div>
            <div class="col-5">
                <a href="dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a id="dashboard-btn" href="Animal_display.php" class="btn btn-outline-light mt-3">Meus Animais</a>
                <a id="dashboard-btn" href="Profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <a id="dashboard-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
    </div>
</header>
<div class="container mt-4">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Numero de Identificação</th>
                <th>Nome do Utilizador</th>
                <th>Privilegio</th>
                <th>Mudar Previlegios</th>
                <th>Ver Animais</th>
                <th>Remover Utilizador</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <?php if (isAdmin()): ?>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
                                <select name="newRole" class="form-select d-inline w-auto">
                                    <option value="normal">Normal</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <input type="submit" name="updateRole" value="Alterar" class="btn btn-primary">
                            </form>
                        <?php endif; ?>
                    </td>
                    <td> <?php if (isAdmin()): ?>
                        <a href="Admin_User_animal.php?userId=<?php echo $row['id']; ?>" class="btn btn-success">Animals</a>
                    <?php endif; ?></td>
                    <td>
                        <?php if (isAdmin()): ?>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="deleteUser" value="Apagar" class="btn btn-danger">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div id="code2" style="display: none;">
    <div id="dog-images">
        <table>
            <?php include 'dog.php'; ?>
        </table>
    </div>
</div>

<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white"></p>
    </div>
</footer>

    <script src="Script.js"></script>
</body>
</html>
