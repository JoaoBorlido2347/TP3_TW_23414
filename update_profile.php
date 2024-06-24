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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $ligax->real_escape_string($_POST['username']);
    $email = $ligax->real_escape_string($_POST['email']);
    $first_name = $ligax->real_escape_string($_POST['first_name']);
    $last_name = $ligax->real_escape_string($_POST['last_name']);
    $birthday = $ligax->real_escape_string($_POST['birthday']);
    $bio = $ligax->real_escape_string($_POST['bio']);
    $address = $ligax->real_escape_string($_POST['address']);
    $extra_field = $ligax->real_escape_string($_POST['extra_field']);
    $password = $ligax->real_escape_string($_POST['password']);

    $sql = "UPDATE usuarios SET username = ?, email = ?, first_name = ?, last_name = ?, birthday = ?, bio = ?, address = ?, extra_field = ?";


    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
    }

    $sql .= " WHERE id = ?";
    $stmt = $ligax->prepare($sql);

    if (!empty($password)) {
        $stmt->bind_param("sssssssssi", $username, $email, $first_name, $last_name, $birthday, $bio, $address, $extra_field, $hashed_password, $user_id);
    } else {
        $stmt->bind_param("ssssssssi", $username, $email, $first_name, $last_name, $birthday, $bio, $address, $extra_field, $user_id);
    }

    $stmt->execute();
    $stmt->close();

   
    header("Location: profile.php");
    exit;
}


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
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
</head>
<body>
<header class="bg-dark py-3">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 text-left text-white">
                <h1 class="display-5">Atulizar Prefil</h1>
        
            </div>
            <div class="col-2"></div>
            <div class="col-6 text-end">
            <a href="Dashboard.php" class="btn btn-outline-light mt-3">Pagina Incial</a>
                <a id="profile-btn" href="profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <a href="Animal_display.php" class="btn btn-outline-light mt-3">Meus Animais</a>
                <?php if ($role === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3">Painel do Administrador</a>
                <?php endif; ?>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
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
                        <form action="update_profile.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Apelido</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Aniversario</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $birthday; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" required><?php echo $bio; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Morada</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="extra_field" class="form-label">Ocupaçao</label>
                                <input type="text" class="form-control" id="extra_field" name="extra_field" value="<?php echo $extra_field; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nova Password (deixe em branco para manter a atual)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary">Gravar mudanças</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">2014</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
