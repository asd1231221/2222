<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Получение информации о пользователе
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Получение истории покупок
$purchases = $pdo->prepare("
    SELECT p.name, p.price, pu.purchase_date 
    FROM purchases pu
    JOIN products p ON pu.product_id = p.id
    WHERE pu.user_id = ?
    ORDER BY pu.purchase_date DESC
");
$purchases->execute([$_SESSION['user_id']]);
$purchases = $purchases->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logout { color: red; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Личный кабинет</h2>
        <a href="index.php">Вернуться в магазин</a>
    </div>
    
    <h3>Ваши данные:</h3>
    <p><strong>Имя пользователя:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    
    <h3>История покупок:</h3>
    <?php if (count($purchases) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Дата покупки</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase): ?>
                <tr>
                    <td><?= htmlspecialchars($purchase['name']) ?></td>
                    <td><?= htmlspecialchars($purchase['price']) ?> руб.</td>
                    <td><?= htmlspecialchars($purchase['purchase_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Вы еще не совершали покупок.</p>
    <?php endif; ?>
</body>
</html>