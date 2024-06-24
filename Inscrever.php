<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background1.css" rel="stylesheet">
    <title>Inscrever</title>
</head>
<body class="bgI">
    <header class="bg-dark py-5">
        <div class="container">
            <div class="text-center text-white">
                <h1>Inscrever</h1>
            </div>
        </div>
    </header>

    <div class="container px-4 px-lg-5 my-5">
    <div class="col-md-3"></div>
    <div class="col-md-4">
    <div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="new-username" class="form-label">Nome de Usuário:</label>
                <input type="text" class="form-control" id="new-username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="new-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="new-email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="new-password" class="form-label">Palavra-passe:</label>
                <input type="password" class="form-control" id="new-password" name="password" required>
            </div>
      
            <button type="submit" class="btn btn-primary" name="submit">Sign Up</button>
            <button id="loginBtn" type="button" class="btn btn-secondary">Log in</button>

        </form>
        <p id="signup-error" class="text-danger">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include "ligacao.php"; 

                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $role = 'normal';

                $stmt = $ligax->prepare("INSERT INTO usuarios (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $password, $role);

                if ($stmt->execute()) {
                    header("Location: Index.php"); 
                    exit;
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
                $ligax->close();
            }
            ?>
        </p>
    </div>    </div>    </div>   </div>

    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">
            </p>
        </div>
    </footer>
    <script>
    document.getElementById('loginBtn').addEventListener('click', redirectToAnotherPage);
    function redirectToAnotherPage() {
        window.location.href = 'Index.php';
    }
</script>
</body>
</html>
