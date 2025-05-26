<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$name]);
    $user = $stmt->fetch();
    
   if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // Если есть отложенный товар
        if (isset($_SESSION['pending_product'])) {
            $productId = $_SESSION['pending_product'];
            unset($_SESSION['pending_product']);
            header("Location: add_to_cart.php?product_id=$productId");
            exit;
        }
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверное имя пользователя или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Подключение общего CSS -->
</head>
<body>
    <?php require 'header.php'; ?> <!-- Вставка header -->
	

    <div class="content" style="max-width: 400px; margin: 50px auto;">
        <h2>Вход</h2>
        <?php if (isset($_GET['registered'])): ?>
            <div class="success">Регистрация успешна! Теперь вы можете войти.</div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Имя пользователя:</label>
                <div style="position: relative;">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="name" name="name" required placeholder="Введите имя">
                </div>
            </div>
            <div class="form-group">
                <label for="pass">Пароль:</label>
                <div style="position: relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="pass" name="pass" required placeholder="Введите пароль">
                </div>
            </div>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </div>

    <?php require 'footer.php'; ?> <!-- Вставка footer -->
</body>
</html>