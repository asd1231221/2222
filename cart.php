<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Получаем товары в корзине пользователя
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, p.id, p.name, p.price, c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <div class="content">
    <h2>Ваша корзина</h2>
        <?php if (empty($cartItems)): ?>
            <p>Корзина пуста.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="item-price"><?= htmlspecialchars($item['price']) ?> руб.</div>
                        <div class="item-quantity">
                            Количество: <?= htmlspecialchars($item['quantity']) ?>
                            <form action="remove_from_cart.php" method="POST" style="display: inline;">
                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                <button type="submit" class="remove-btn">-</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div> <!-- Закрываем div.cart-items -->
        <?php endif; ?> <!-- Добавляем закрытие условия if -->
    </div> <!-- Закрываем div.content -->

    <?php require 'footer.php'; ?>
</body>
</html>