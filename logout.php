<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ログインしているユーザーで、かつカートに商品がある場合のみデータベースに保存
    if (isset($_SESSION['user_id']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];

        // ユーザーのカートが存在するか確認し、存在しない場合は作成
        $cart_check_sql = "SELECT id FROM carts WHERE user_id = :user_id";
        $cart_check_stmt = $pdo->prepare($cart_check_sql);
        $cart_check_stmt->bindParam(':user_id', $user_id);
        $cart_check_stmt->execute();
        $cart_data = $cart_check_stmt->fetch(PDO::FETCH_ASSOC);

        $cart_id = null;
        if ($cart_data) {
            $cart_id = $cart_data['id'];
        } else {
            $insert_cart_sql = "INSERT INTO carts (user_id) VALUES (:user_id)";
            $insert_cart_stmt = $pdo->prepare($insert_cart_sql);
            $insert_cart_stmt->bindParam(':user_id', $user_id);
            $insert_cart_stmt->execute();
            $cart_id = $pdo->lastInsertId();
        }

        // 既存のカートアイテムを一度クリア（または同期ロジックを実装）
        // シンプルにするため、ここでは既存のアイテムを削除してセッションの内容を再挿入
        $delete_existing_items_sql = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $delete_existing_items_stmt = $pdo->prepare($delete_existing_items_sql);
        $delete_existing_items_stmt->bindParam(':cart_id', $cart_id);
        $delete_existing_items_stmt->execute();

        // セッションのカート内容をデータベースに挿入
        $insert_item_sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (:cart_id, :product_id, :quantity, :price)";
        $insert_item_stmt = $pdo->prepare($insert_item_sql);
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $insert_item_stmt->bindParam(':cart_id', $cart_id);
            $insert_item_stmt->bindParam(':product_id', $product_id);
            $insert_item_stmt->bindParam(':quantity', $item['quantity']);
            $insert_item_stmt->bindParam(':price', $item['price']);
            $insert_item_stmt->execute();
        }
    }

} catch (PDOException $e) {
    error_log("ログアウト時のカート保存エラー: " . $e->getMessage());
}

session_unset();  // セッション変数を削除
session_destroy(); // セッションを破棄
header("Location: index.php"); // トップページへリダイレクト
exit;
?>