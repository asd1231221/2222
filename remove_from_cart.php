<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['cart_id'])) {
    header("Location: login.php");
    exit;
}

// Получаем текущее количество товара
$stmt = $pdo->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$_POST['cart_id'], $_SESSION['user_id']]);
$item = $stmt->fetch();

if ($item) {
    if ($item['quantity'] > 1) {
        // Уменьшаем количество на 1
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ?");
        $stmt->execute([$_POST['cart_id']]);
    } else {
        // Удаляем запись, если количество 1
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$_POST['cart_id']]);
    }
}

header("Location: cart.php");
exit;
?>