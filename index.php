<?php
session_start();
require 'db.php';

// Убираем проверку авторизации
$products = $pdo->query("SELECT * FROM products WHERE count > 0")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Магазин одежды</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php require 'header.php'; ?>

<div class="content">
    <?php if (isset($_GET['from_cart'])): ?>
        <a href="cart.php" class="back-btn">← Вернуться в корзину</a>
    <?php endif; ?>
        <!-- Блок уведомления -->
    <?php if (isset($_SESSION['message'])): ?>
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeModal()">&times;</span>
                <p><?= $_SESSION['message'] ?></p>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

         <!-- Карточки товаров -->
    <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <?php if (!empty($product['image'])): ?>
                        <img 
                            src="uploads/<?= htmlspecialchars($product['image']) ?>" 
                            alt="<?= htmlspecialchars($product['name']) ?>" 
                            class="product-image"
                        >
                    <?php else: ?>
                        <div class="placeholder-image">Изображение отсутствует</div>
                    <?php endif; ?>
                    <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="product-price"><?= htmlspecialchars($product['price']) ?> руб.</div>
                    <div class="product-count">В наличии: <?= htmlspecialchars($product['count']) ?> шт.</div>
                   <form action="purchase.php" method="POST">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
</form>
<form action="add_to_cart.php" method="POST" style="display: inline;">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <button type="submit" class="buy-btn">В корзину</button>
</form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require 'footer.php'; ?>

    <script>
        // Закрытие модального окна
        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }

        // Закрытие при клике вне окна
        document.getElementById('modalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Закрытие на ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Автоматическое закрытие через 3 секунды
        setTimeout(() => {
            const modal = document.getElementById('modalOverlay');
            if (modal) modal.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>