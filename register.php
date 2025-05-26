<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
    $stmt->execute([$name, $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        $error = "Пользователь с таким именем или email уже существует";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, pass, email) VALUES (?, ?, ?)");
        $stmt->execute([$name, $pass, $email]);
        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Подключение общего CSS -->
</head>
<body>
    <?php require 'header.php'; ?> <!-- Вставка header -->

    <div class="content" style="max-width: 400px; margin: 50px auto;">
        <h2>Регистрация</h2>
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
                <label for="email">Email:</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" required placeholder="example@mail.com">
                </div>
            </div>
            <div class="form-group">
                <label for="pass">Пароль:</label>
                <div style="position: relative;">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="pass" name="pass" required placeholder="Не менее 6 символов">
                </div>
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>

    <?php require 'footer.php'; ?> <!-- Вставка footer -->
</body>
</html>