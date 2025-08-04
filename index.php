<?php
session_start();
require 'includes/header.php';

// DB接続
$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';
$ranking_products = [];

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 人気ランキング取得（上位6件）
    $sql_ranking = "
        SELECT
            p.id,
            p.name,
            p.price,
            SUM(oi.quantity) AS total_quantity_sold
        FROM
            order_items oi
        JOIN
            products p ON oi.product_id = p.id
        JOIN (
            SELECT user_id
            FROM order_items
            ORDER BY purchased_at DESC
            LIMIT 100
        ) AS recent_users ON oi.user_id = recent_users.user_id
        GROUP BY
            p.id, p.name, p.price
        ORDER BY
            total_quantity_sold DESC
        LIMIT 6
    ";
    $stmt_ranking = $pdo->prepare($sql_ranking);
    $stmt_ranking->execute();
    $ranking_products = $stmt_ranking->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log($e->getMessage());
}

// 6枠分のデータを埋める（足りなければnull）
$display_ranking = array_pad($ranking_products, 6, null);
?>

<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<img src="images/mainImage.png" alt="CCドーナツ Main Image" class="mainDonut">

<div class="duble">
    <a href="product_detail.php?id=5">
        <img src="images/new.png" alt="New Products" class="new">
    </a>
    <img src="images/donut.png" alt="Donut Image" class="donut">
</div>

<div class="product1">
    <a href="product.php"><img src="images/product.png" alt="Product Image"></a>
</div>

<div class="shareContent">
    <h1>philosophy</h1>
    <h2>私たちの信念</h2>
    <h3>"Creating Connections"</h3>
    <h4>「ドーナツでつながる」</h4>
</div>

<!-- 人気ランキング -->
<div class="ranking-section">
    <h1>人気ランキング</h1>
    <div class="product-list">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="product" id="rank-<?= $i + 1 ?>">
                <div class="rank-number"><?= $i + 1 ?></div>
                <?php if ($display_ranking[$i]): ?>
                    <?php $product = $display_ranking[$i]; ?>
                    
                    <!-- 商品画像 -->
                        <img src="images/<?= htmlspecialchars($product['id']) ?>.png" alt="<?= htmlspecialchars($product['name']) ?>">

                    <!-- 商品名 -->
                    <a href="product_detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                    <?= htmlspecialchars($product['name']) ?>
                    </a>
                    <!-- 価格 -->
                    <p class="price">税込　¥<?= number_format($product['price']) ?></p>

                    <!-- カート追加 -->
                    <form action="cart_add.php" method="post">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <button type="submit" class="add-to-cart">カートに入れる</button>
                    </form>
                    <?php else: ?>
                    <!-- データなしの場合 -->
                    <img src="images/no_image.png" alt="No Image">
                    <p>準備中</p>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
