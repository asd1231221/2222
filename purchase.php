<?php
session_start(); // Должно быть в самом начале
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    try {
    // Уменьшаем количество товара
    $stmt = $pdo->prepare("UPDATE products SET count = count - 1 WHERE id = ? AND count > 0");
    $stmt->execute([$product_id]);

    if ($stmt->rowCount() > 0) {
        // Добавляем запись в историю покупок
        $pdo->prepare("INSERT INTO purchases (user_id, product_id, purchase_date) VALUES (?, ?, NOW())")
            ->execute([$user_id, $product_id]);
        $_SESSION['message'] = "Спасибо за покупку! Вы приобрели товар.";
    } else {
        $_SESSION['message'] = "Товар закончился.";
        // Возвращаем товар, если он закончился
        $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE product_id = ? AND user_id = ?")
            ->execute([$product_id, $user_id]);
    }
} catch (PDOException $e) {
    error_log("Ошибка: " . $e->getMessage());
    $_SESSION['message'] = "Ошибка: " . $e->getMessage();
}

    header("Location: index.php");
    exit;
}
?>