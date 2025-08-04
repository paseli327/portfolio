<?php
session_start();
require 'includes/header.php';

// データベース接続情報
$host = 'localhost';
$db_name = 'ccdonuts';
$user = 'ccStaff';
$password = 'ccDonuts';

$searchResults = [];
$searchQuery = '';

// 検索キーワード取得（GETパラメータ名を確認）
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $searchQuery = trim($_GET['search']);
}

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    if ($searchQuery !== '') {
        $stmt = $dbh->prepare("SELECT id, name, price, introduction FROM products WHERE name LIKE :search");
        $stmt->bindValue(':search', '%' . $searchQuery . '%', PDO::PARAM_STR);
        $stmt->execute();
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo 'データベース接続エラー: ' . $e->getMessage();
    exit();
}
?>

<p class="page"><a href="index.php">トップ</a> > <a href="product.php">商品一覧</a> > <?= htmlspecialchars($searchQuery) ?>の検索結果</p>
<?php if (isset($_SESSION['user_name'])): ?>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<?php endif; ?>

<main>
    <div class="container">
        <div class="search-form">
        <?php if ($searchQuery !== ''): ?>
            <p>「<?= htmlspecialchars($searchQuery) ?>」の検索結果</p>
        <?php else: ?>
            <p>検索キーワードを入力してください。</p>
        <?php endif; ?>
        </div>

        <?php if (!empty($searchResults)): ?>
            <div class="product-list">
                <?php foreach ($searchResults as $product): ?>
                    <div class="product">
                        <img src="images/<?= htmlspecialchars($product['id']) ?>.png" alt="<?= htmlspecialchars($product['name']) ?>">
                        <a href="product_detail.php?id=<?= urlencode($product['id']) ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                        <p class="price">税込 <?= htmlspecialchars($product['price']) ?>円</p>
                    <form action="cart_add.php" method="post">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <button type="submit" class="add-to-cart">カートに入れる</button>
                    </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php if ($searchQuery !== ''): ?>
                <p>「<?= htmlspecialchars($searchQuery) ?>」に一致する商品はありませんでした。</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php require 'includes/footer.php'; ?>