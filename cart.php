<?php
session_start();
require 'includes/header.php';

$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // カートの更新処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $cart_id_sql = "SELECT id FROM carts WHERE user_id = :user_id";
            $cart_id_stmt = $pdo->prepare($cart_id_sql);
            $cart_id_stmt->bindParam(':user_id', $user_id);
            $cart_id_stmt->execute();
            $cart_data = $cart_id_stmt->fetch(PDO::FETCH_ASSOC);
            $cart_id = $cart_data ? $cart_data['id'] : null;
        }

        foreach ($_POST['quantity'] as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity = (int)$quantity;

            if (isset($_SESSION['cart'][$product_id])) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;

                    if (isset($cart_id)) {
                        $update_item_sql = "UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id";
                        $update_item_stmt = $pdo->prepare($update_item_sql);
                        $update_item_stmt->bindParam(':quantity', $quantity);
                        $update_item_stmt->bindParam(':cart_id', $cart_id);
                        $update_item_stmt->bindParam(':product_id', $product_id);
                        $update_item_stmt->execute();
                    }
                } else {
                    unset($_SESSION['cart'][$product_id]);

                    if (isset($cart_id)) {
                        $delete_item_sql = "DELETE FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
                        $delete_item_stmt = $pdo->prepare($delete_item_sql);
                        $delete_item_stmt->bindParam(':cart_id', $cart_id);
                        $delete_item_stmt->bindParam(':product_id', $product_id);
                        $delete_item_stmt->execute();
                    }
                }
            }
        }
    }
} catch (PDOException $e) {
    error_log("カート更新エラー: " . $e->getMessage());
}
?>

<p class="page"><a href="index.php">トップ</a> > <a href="product.php">商品一覧</a> > <span>カート</span></p>

<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>

    <?php
    $total_price = 0;
    $total_quantity = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total_price += $subtotal;
        $total_quantity += $item['quantity'];
    }
    ?>

    <form action="cart.php" method="post" class="cart-form">
        <div class="cart-summary2">
            <div class="cart-summary">
                <p>現在　商品<?= htmlspecialchars($total_quantity) ?>点</p>
                <p>ご注文小計:税込<span class="red">¥<?= number_format($total_price) ?></span></p>
                <a href="checkout.php" class="checkout-btn">購入確認へ進む</a>
            </div>
        </div>

        <div class="cart-items">
            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($product_id) ?>.png" 
                     alt="<?= htmlspecialchars($item['name']) ?>" 
                     class="cart-item-img">
                
                <div class="cart-item-info">
                    <h2 class="cart-item-name"><?= htmlspecialchars($item['name']) ?></h2>
                    <div class="cartmoney">
                        <p class="cart-item-price">税込　<?= number_format($item['price']) ?>円</p>
                        
                        <div class="cart-item-quantity">
                            <label>数量</label>
                            <input type="number" name="quantity[<?= $product_id ?>]" 
                                   value="<?= htmlspecialchars($item['quantity']) ?>" 
                                   min="0">
                            <label>個</label>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <button type="submit" name="update_cart" class="update-btn">再計算</button>
                        <a href="cart_remove.php?id=<?= $product_id ?>" class="delete-btn">削除する</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <div class="cart-summary2">
    <div class="cart-summary">
        <p>現在　商品<?= htmlspecialchars($total_quantity) ?>点</p>
        <p>ご注文小計:税込<span class="red">¥<?= number_format($total_price) ?></span></p>
        <a href="checkout.php" class="checkout-btn">購入確認へ進む</a>
    </div>
    </div>
        <div class="cart-actions">
            <a href="product.php" class="continue-shopping-btn">買い物を続ける</a>
        </div>
    </form>
<?php else: ?>
    <p style="display: flex;
    justify-content: center;
    margin: 80px;">カートに商品はありません。</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
