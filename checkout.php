<?php
session_start();
require 'includes/header.php'; // ヘッダーファイルの読み込み

// DB接続設定
$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// ユーザー情報の取得
$customer_name = 'ゲスト'; // デフォルトはゲスト
$customer_postcode_a = '';
$customer_postcode_b = '';
$customer_address = '';
$payment_method = '未選択'; // デフォルトの支払い方法
$card_brand = '未登録'; // デフォルトのカードブランド
$card_data = null; // クレカ情報判定用

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // 顧客情報の取得
    $sql_customer = "SELECT name, postcode_a, postcode_b, address FROM customers WHERE id = :user_id";
    $stmt_customer = $pdo->prepare($sql_customer);
    $stmt_customer->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_customer->execute();
    $customer_data = $stmt_customer->fetch(PDO::FETCH_ASSOC);

    if ($customer_data) {
        $customer_name = htmlspecialchars($customer_data['name'], ENT_QUOTES, 'UTF-8');
        $customer_postcode_a = htmlspecialchars($customer_data['postcode_a'], ENT_QUOTES, 'UTF-8');
        $customer_postcode_b = htmlspecialchars($customer_data['postcode_b'], ENT_QUOTES, 'UTF-8');
        $customer_address = htmlspecialchars($customer_data['address'], ENT_QUOTES, 'UTF-8');
    }

    // クレジットカード情報の取得
    $sql_card = "SELECT card_company FROM credit_cards WHERE user_id = :user_id LIMIT 1";
    $stmt_card = $pdo->prepare($sql_card);
    $stmt_card->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_card->execute();
    $card_data = $stmt_card->fetch(PDO::FETCH_ASSOC);

    if ($card_data) {
        $payment_method = 'クレジットカード';
        $card_brand = htmlspecialchars($card_data['card_company'], ENT_QUOTES, 'UTF-8');
    } else {
        $payment_method = '<span class="cardMessage">クレジットカード情報が登録されていません<br><a href="credit_register.php">クレジットカードを登録する</a></span>';
        $card_brand = 'N/A';
    }

} else {
    // ゲストユーザー
    $payment_method = 'クレジットカード'; // ゲストは仮でクレカ支払い
    $card_brand = 'JCB'; // ゲストは仮でJCB
    // 実際にはゲスト購入を許可するかどうかで仕様を変える
}

// カート情報の取得
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_quantity = 0;
$total_price = 0;

foreach ($cart_items as $item) {
    $total_quantity += $item['quantity'];
    $total_price += $item['price'] * $item['quantity'];
}
?>

<p class="page"><a href="index.php">トップ</a> > <a href="cart.php">カート</a> > <span>ご購入確認</span></p>

<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<div class="title15">
    <h1>ご購入確認</h1>
</div>

<div class="checkout-container">
    <h2 class="section-title">ご購入商品</h2>
    <table class="order-summary-table">
        <tbody>
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $product_id => $item): ?>
                    <tr>
                        <td><span>商品名</span><?= htmlspecialchars($item['name']) ?></td>
                        <td><span>数量　</span><?= htmlspecialchars($item['quantity']) ?>個</td>
                        <td><span>金額　</span>税込 &yen;<?= htmlspecialchars($item['price'] * $item['quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">カートに商品がありません。</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="td">合計数量</td>
                <td class="td2"><?= htmlspecialchars($total_quantity) ?>個</td>
            </tr>
            <tr>
                <td class="td">合計金額</td>
                <td class="td2">税込 &yen;<?= htmlspecialchars($total_price) ?></td>
            </tr>
        </tfoot>
    </table>

    <h2 class="section-title">お届け先</h2>
    <table class="delivery-info-table">
        <tr>
            <td class="td3">お名前　</td>
            <td class="td4"><?= $customer_name ?></td>
        </tr>
        <tr>
            <td class="td3">郵便番号</td>
            <td class="td4"><?= $customer_postcode_a . '-' . $customer_postcode_b ?></td>
        </tr>
        <tr>
            <td class="td3">住所　　</td>
            <td class="td4"><?= $customer_address ?></td>
        </tr>
    </table>

    <h2 class="section-title">お支払い方法</h2>
    <table class="payment-info-table">
        <tr>
            <td class="td3">お支払い</td>
            <td class="td4"><?= $payment_method ?></td>
        </tr>
        <tr>
            <td class="td3">ブランド</td>
            <td class="td4"><?= $card_brand ?></td>
        </tr>
    </table>

    <div class="confirm-button-area">
        <form action="payment.php" method="post">
            <?php if (isset($_SESSION['user_id']) && !$card_data): ?>
                <!-- ログイン済みだがカード未登録 -->
                <button type="submit" class="confirm-purchase-button" disabled style="background:#ccc;cursor:not-allowed;">
                    購入を確定する
                </button>
            <?php else: ?>
                <!-- ゲスト or カード登録済み -->
                <button type="submit" class="confirm-purchase-button">購入を確定する</button>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php require 'includes/footer.php'; // フッターファイルの読み込み ?>
