<?php
session_start();
require 'db.php';

if (!isset($_POST['product_id'])) {
    header("Location: index.php");
    exit;
}

// Если пользователь не авторизован
if (!isset($_SESSION['user_id'])) {
    $_SESSION['pending_product'] = $_POST['product_id'];
    header("Location: login.php");
    exit;
}

// Логика добавления в корзину для авторизованных
$productId = $_POST['product_id'];
$userId = $_SESSION['user_id'];

try {
    // Проверяем наличие товара в корзине
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // Увеличиваем количество
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$existingItem['id']]);
    } else {
        // Добавляем новый товар
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$userId, $productId]);
    }

    // Уменьшаем количество товара на складе
    $stmt = $pdo->prepare("UPDATE products SET count = count - 1 WHERE id = ? AND count > 0");
    $stmt->execute([$productId]);

    // Уведомление
    $_SESSION['message'] = "Товар добавлен в корзину!";
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>