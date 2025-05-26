<?php
session_start();
require 'db.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин одежды</title>
   <link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
    <h2>shop</h2>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Привет, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="profile.php">Профиль</a>
            <a href="<?= ($current_page === 'cart.php') ? 'index.php' : 'cart.php' ?>">
                Корзина
            </a>
            <a href="logout.php" style="color: #ff4444;">Выйти</a>
        <?php else: ?>
            <?php if ($current_page !== 'login.php' && $current_page !== 'register.php'): ?>
                <a href="login.php">Войти</a>
                <a href="register.php">Регистрация</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</div>
</body>
    </div>
	</html>