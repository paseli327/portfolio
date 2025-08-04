<?php
session_start();
require 'includes/header.php';
if ($_SESSION['payment_status'] !== 'success') {
    header('Location: confirm.php');
    exit;
}

$order_id = $_SESSION['order_id'] ?? '未設定';

// 注文情報を表示
echo "<h2>注文完了</h2>";
echo "<p>注文番号: " . htmlspecialchars($order_id) . "</p>";
echo "<p>ご購入ありがとうございました。</p>";

// カートと状態をクリア
unset($_SESSION['cart'], $_SESSION['payment_status'], $_SESSION['order_id']);?>
<?php require 'includes/footer.php'; ?>