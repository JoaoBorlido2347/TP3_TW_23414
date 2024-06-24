<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}


$username = $_SESSION['username'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Usuário';
$user_id = $_SESSION['user_id']; 


require_once 'ligacao.php'; 


$stmt = $ligax->prepare("SELECT first_name FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name);
$stmt->fetch();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $especie = $_POST['especie'];
    $imagem_url = $_POST['imagem_url'];
    $id_usuario = $_POST['id_usuario'];

    
    $stmt = $ligax->prepare("INSERT INTO animal (nome, especie, id_usuario, imagem_url) VALUES (?, ?, ?, ?)");
    $nome = "Nome do Animal"; 
    $stmt->bind_param("ssis", $nome, $especie, $id_usuario, $imagem_url);
    $stmt->execute();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopta Animais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="./Styles/Style.css" rel="stylesheet">
    <link href="./Styles/background2.css" rel="stylesheet">
  </head>
<body onload="processRequest()">
<header class="bg-dark py-3">
    <div class="container ">
        <div class="row justify-content-between align-items-center">
            <div class="col-6 text-left text-white">
                <h1 class="display-5">Adopta Animais</h1>
                <p class="lead fw-normal text-white-50 mb-0">Cães, Gatos e Raposas!</p>
                <p>Bem-vindo, <?php echo htmlspecialchars($first_name); ?>!</p> 
            </div>    
            <div class="col-6 text-end">
                <a id="dashboard-btn" href="Animal_display.php" class="btn btn-outline-light mt-3">Meus Animais</a>
                <a id="profile-btn" href="profile.php" class="btn btn-outline-light mt-3">Perfil</a>
                <?php if ($role === 'admin'): ?>
                    <a id="admin-panel-btn" href="admnistrador.php" class="btn btn-outline-light mt-3"> Painel do Administrador</a>
                <?php endif; ?>
                <a id="logout-btn" class="btn btn-outline-light mt-3" href="index.php">Sair</a>
            </div>
        </div>
    </div>
</header>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="animals">
        </div>
    </div>
</section>

<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">
            <a class="btn btn-outline-light" href="#" id="more">Mais</a>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function getDog() {
  const tbody = document.getElementById('animals');
  
  fetch('https://dog.ceo/api/breeds/image/random')
    .then(response => response.json())
    .then(data => {
      const newHtml = `
      <div class="col mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="${data.message}" alt="Random Dog Image" width="500" height="300"/>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
            <div></div>
              <form action="Dashboard.php" method="post">
                <input type="hidden" name="especie" value="dog">
                <input type="hidden" name="imagem_url" value="${data.message}">
                <input type="hidden" name="id_usuario" value="${getUserId()}"> <!-- Add user ID -->
                <button type="submit" class="btn btn-outline-dark margin">Adoptar</button>
              </form>
            </div>
          </div>
        </div>
      </div>`;
      tbody.innerHTML += newHtml;
    })
    .catch(error => console.error(error));
}

function getCat() {
  const tbody = document.getElementById('animals');
  
  fetch('https://cataas.com/cat')
    .then(response => response.blob())
    .then(data => {
      const imageUrl = URL.createObjectURL(data);
      const newHtml = `
      <div class="col mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="${imageUrl}" alt="Random Cat Image" width="500" height="300"/>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
                     <div></div>
              <form action="Dashboard.php" method="post">
                <input type="hidden" name="especie" value="cat">
                <input type="hidden" name="imagem_url" value="${imageUrl}">
                <input type="hidden" name="id_usuario" value="${getUserId()}"> <!-- Add user ID -->
                <button type="submit" class="btn btn-outline-dark margin">Adoptar</button>
              </form>
            </div>
          </div>
        </div>
      </div>`;
      tbody.innerHTML += newHtml;
    })
    .catch(error => console.error(error));
}

function getFox() {
  const tbody = document.getElementById('animals');
  
  fetch('https://randomfox.ca/floof/')
    .then(response => response.json())
    .then(data => {
      const newHtml = `
      <div class="col mb-5">
        <div class="card h-100">
          <img class="card-img-top" src="${data.image}" alt="Random Fox Image" width="500" height="300"/>
          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
              <form action="Dashboard.php" method="post">
                <input type="hidden" name="especie" value="fox">
                <input type="hidden" name="imagem_url" value="${data.image}">
                <input type="hidden" name="id_usuario" value="${getUserId()}"> <!-- Add user ID -->
                <button type="submit" class="btn btn-outline-dark margin">Adoptar</button>
              </form>
            </div>
          </div>
        </div>
      </div>`;
      tbody.innerHTML += newHtml;
    })
    .catch(error => console.error(error));
}

const generateKeyBtn = document.getElementById("more");
generateKeyBtn.addEventListener('click', () => processRequest());

function getRandomNumber() {
  return Math.floor(Math.random() * 3) + 1;
}

function processRequest() {
  for (let i = 0; i < 8; i++) {
    const n = getRandomNumber();
    if (n === 1) {
      getDog();
    } else if (n === 2) {
      getCat();
    } else {
      getFox();
    }
  }
}

function getUserId() {

  return <?php echo $user_id; ?>;
}
</script>
</body>

</html>
