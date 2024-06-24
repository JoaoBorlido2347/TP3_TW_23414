<?php
session_start();
include 'ligacao.php'; 


if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}


if (isset($_GET['id'])) {
    $animal_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id']; 

    
    $stmt = $ligax->prepare("DELETE FROM animal WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $animal_id, $user_id);
    $stmt->execute();
    $stmt->close();
}


header("Location: Animal_display.php");
exit;
?>