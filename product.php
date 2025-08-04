<?php
// DB接続設定
$dsn = '****';
$user = '****';
$password = '****';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// 商品データ取得
$sql = "SELECT * FROM products ORDER BY id";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 1～6と7～12に分割（array_sliceを利用）
$group1 = array_slice($products, 0, 6);
$group2 = array_slice($products, 6, 6);
?>

<?php
session_start();
require 'includes/header.php';
?>
  <p class="page"><a href="index.php">トップ</a>><span>商品一覧</span></p>

<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>
<div class="title">
<h1 class="syouhin">商品一覧</h1>
</div>
<h2 class="group">メインメニュー</h2>
<div class="product-list">
  <?php foreach ($group1 as $product): ?>
<div class="product">
  <img src="images/<?= htmlspecialchars($product['id']) ?>.png" alt="<?= htmlspecialchars($product['name']) ?>">
  <a href="product_detail.php?id=<?= urlencode($product['id']) ?>">
      <?= htmlspecialchars($product['name']) ?>
  </a>
  <p class="price">税込 <?= htmlspecialchars($product['price']) ?>円</p>
  <form action="cart_add.php" method="post">
      <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
      <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
      <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']) ?>">
      <input type="hidden" name="quantity" value="1">
      <button type="submit" class="add-to-cart">カートに入れる</button>
  </form>
</div>

  <?php endforeach; ?>
</div>

<h2 class="group">バラエティメニュー</h2>
<div class="product-list">
  <?php foreach ($group2 as $product): ?>
<div class="product">
  <img src="images/<?= htmlspecialchars($product['id']) ?>.png" alt="<?= htmlspecialchars($product['name']) ?>">
  <a href="product_detail.php?id=<?= urlencode($product['id']) ?>">
      <?= htmlspecialchars($product['name']) ?>
  </a>
  <p class="price">税込 <?= htmlspecialchars($product['price']) ?>円</p>
  <form action="cart_add.php" method="post">
      <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
      <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
      <input type="hidden" name="product_price" value="<?= htmlspecialchars($product['price']) ?>">
      <input type="hidden" name="quantity" value="1">
      <button type="submit" class="add-to-cart">カートに入れる</button>
  </form>
</div>

  <?php endforeach; ?>
</div>


<?php require 'includes/footer.php'; ?>
