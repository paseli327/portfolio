<?php
session_start();
require 'includes/header.php';

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

// ログイン中のユーザーIDを取得
// ゲスト購入を許可しない場合は、user_idがない場合にエラーとする
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    echo '<div class="error-message">';
    echo "<p>カートに商品がありません。<br><a href='index.php'>トップページに戻る</a></p>";
    echo '</div>';
    require 'includes/footer.php';
    exit;
}

// ユーザーがログインしていない場合（ゲスト購入を許可しない場合）
// ゲストユーザーの購入履歴も記録したい場合は、order_itemsのuser_idをNULL許容にするか、
// ゲスト用のダミーuser_idを作成するなどの対応が必要です。
if (!$user_id) {
    echo '<div class="error-message">';
    echo "<p>購入にはログインが必要です。<br><a href='login.php'>ログインページへ</a></p>";
    echo '</div>';
    require 'includes/footer.php';
    exit;
}
try {
    $pdo->beginTransaction(); // トランザクション開始

    foreach ($cart_items as $product_id => $item) {
        $sql = "INSERT INTO order_items (user_id, product_id, quantity, purchase_price)
                VALUES (:user_id, :product_id, :quantity, :purchase_price)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':purchase_price', $item['price'], PDO::PARAM_INT); // ここは単価を保存
        $stmt->execute();
    }

    // カートをクリア
    unset($_SESSION['cart']);

    $pdo->commit(); // トランザクションコミット

    echo '<p class="page"><a href="index.php">トップ</a> > <span>購入完了</span></p>';
    echo '<div class="welcome">';
    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
        echo '<span>ようこそ　' . htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') . ' 様</span>';
        echo '<a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>';
    } else {
        echo '<span>ようこそ　ゲスト 様</span>';
    }
    echo '</div>';
    echo '<h1 class="title18">購入完了</h1>';
    echo '<div class="confirmation">';
    echo '<div class="message">';
    echo '<p>ご購入が完了しました。ありがとうございます！</p>';
    echo '<p><a href="index.php">トップページに戻る</a></p>';
    echo '</div>';
    echo '</div>';

} catch (PDOException $e) {
    $pdo->rollBack(); // エラー時はロールバック
    error_log($e->getMessage()); // エラーログに出力
    echo "<p>購入処理中にエラーが発生しました。再度お試しください。</p><p><a href='checkout.php'>購入確認ページに戻る</a></p>";
}

require 'includes/footer.php';
?>