<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $product_id = (int)$_GET['id'];

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);

            // ログインしているユーザーであればデータベースからも削除
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                
                // ユーザーのカートIDを取得
                $cart_id_sql = "SELECT id FROM carts WHERE user_id = :user_id";
                $cart_id_stmt = $pdo->prepare($cart_id_sql);
                $cart_id_stmt->bindParam(':user_id', $user_id);
                $cart_id_stmt->execute();
                $cart_data = $cart_id_stmt->fetch(PDO::FETCH_ASSOC);
                $cart_id = $cart_data ? $cart_data['id'] : null;

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
} catch (PDOException $e) {
    error_log("カートアイテム削除エラー: " . $e->getMessage());
}

// カートページにリダイレクト
header('Location: cart.php');
exit;
?>