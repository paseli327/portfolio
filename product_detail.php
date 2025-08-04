<?php
session_start();
$dsn = '****';
$user = '****';
$password = '****';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("DB接続失敗: " . $e->getMessage());
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "商品が見つかりません";
    exit;
}
require 'includes/header.php';
?>

  <p class="page"><a href="index.php">トップ</a> > <a href="product.php">商品一覧</a>><span><?= htmlspecialchars($product['name']) ?></span></p>

<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<div class="productDetail">
<img src="images/<?= htmlspecialchars($product['id']) ?>.png" alt="<?= htmlspecialchars($product['name']) ?>">
<div class="productText">
<h1><?= htmlspecialchars($product['name']) ?></h1>
<div  class="productText2">
<p><?=htmlspecialchars($product['introduction']) ?></p>
</div>
<p class="price">税込 <?= htmlspecialchars($product['price']) ?>円</p>

<form action="cart_add.php" method="post">
    <label>
        <input type="number" name="quantity" value="1" min="1"><p>個</p>
    </label>
    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
    <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']) ?>">
    <button type="submit">カートに入れる</button>
</form>

</div>
</div>

<?php require 'includes/footer.php'; ?>
