<?php
session_start();

$dsn = '****';
$user = '****';
$password = '****';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if ($product_id > 0 && $quantity > 0) {
            // ★ここから追加・修正する部分★
            // productsテーブルから商品の名前と価格を取得
            $stmt_product = $pdo->prepare("SELECT name, price FROM products WHERE id = :product_id");
            $stmt_product->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt_product->execute();
            $product_data = $stmt_product->fetch(PDO::FETCH_ASSOC);

            if ($product_data) {
                $product_name = $product_data['name'];
                $product_price = $product_data['price'];
            } else {
                // 商品IDが無効な場合のエラーハンドリング
                error_log("存在しない商品IDがカートに追加されようとしました: " . $product_id);
                // エラーメッセージをセッションに保存してリダイレクトするなどの処理も検討
                header('Location: index.php'); // トップページに戻すなどの対応
                exit;
            }
            // ★ここまで追加・修正する部分★

            // セッションのカートを更新
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product_name, // DBから取得した名前を使用
                    'price' => $product_price, // DBから取得した価格を使用
                    'quantity' => $quantity
                ];
            }

            // ログインしているユーザーであればデータベースのカートも更新
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // ユーザーのカートが存在するか確認
                $cart_check_sql = "SELECT id FROM carts WHERE user_id = :user_id";
                $cart_check_stmt = $pdo->prepare($cart_check_sql);
                $cart_check_stmt->bindParam(':user_id', $user_id);
                $cart_check_stmt->execute();
                $cart_data = $cart_check_stmt->fetch(PDO::FETCH_ASSOC);

                $cart_id = null;
                if ($cart_data) {
                    $cart_id = $cart_data['id'];
                } else {
                    // カートが存在しない場合、新しく作成
                    $insert_cart_sql = "INSERT INTO carts (user_id) VALUES (:user_id)";
                    $insert_cart_stmt = $pdo->prepare($insert_cart_sql);
                    $insert_cart_stmt->bindParam(':user_id', $user_id);
                    $insert_cart_stmt->execute();
                    $cart_id = $pdo->lastInsertId();
                }

                // cart_items テーブルを更新
                $item_check_sql = "SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
                $item_check_stmt = $pdo->prepare($item_check_sql);
                $item_check_stmt->bindParam(':cart_id', $cart_id);
                $item_check_stmt->bindParam(':product_id', $product_id);
                $item_check_stmt->execute();
                $item_data = $item_check_stmt->fetch(PDO::FETCH_ASSOC);

                if ($item_data) {
                    // 既存の商品があれば数量を更新
                    $new_quantity = $item_data['quantity'] + $quantity;
                    $update_item_sql = "UPDATE cart_items SET quantity = :quantity WHERE id = :item_id";
                    $update_item_stmt = $pdo->prepare($update_item_sql);
                    $update_item_stmt->bindParam(':quantity', $new_quantity);
                    $update_item_stmt->bindParam(':item_id', $item_data['id']);
                    $update_item_stmt->execute();
                } else {
                    // 新規の商品であれば追加
                    $insert_item_sql = "INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (:cart_id, :product_id, :quantity, :price)";
                    $insert_item_stmt = $pdo->prepare($insert_item_sql);
                    $insert_item_stmt->bindParam(':cart_id', $cart_id);
                    $insert_item_stmt->bindParam(':product_id', $product_id);
                    $insert_item_stmt->bindParam(':quantity', $quantity);
                    $insert_item_stmt->bindParam(':price', $product_price); // DBから取得した価格を使用
                    $insert_item_stmt->execute();
                }
            }
        }
    }
} catch (PDOException $e) {
    // エラーハンドリング
    error_log("カート追加エラー: " . $e->getMessage());
    // ユーザーには表示しないか、一般的なエラーメッセージを表示
}

header('Location: cart.php');
exit;

?>
