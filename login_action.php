<?php
session_start();

$dsn = '****';
$user = '****';
$password = '****';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mail = $_POST['mail'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE LOWER(mail) = LOWER(:mail) AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':password', $pass);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // ログイン成功後、ユーザーのカート情報をデータベースから読み込む
        $_SESSION['cart'] = []; // セッションのカートを一度クリア

        $cart_sql = "SELECT c.id AS cart_id, ci.product_id, ci.quantity, ci.price, p.name AS product_name 
                     FROM carts c 
                     JOIN cart_items ci ON c.id = ci.cart_id 
                     JOIN products p ON ci.product_id = p.id
                     WHERE c.user_id = :user_id";
        $cart_stmt = $pdo->prepare($cart_sql);
        $cart_stmt->bindParam(':user_id', $_SESSION['user_id']);
        $cart_stmt->execute();
        $cart_items_from_db = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($cart_items_from_db) {
            foreach ($cart_items_from_db as $item) {
                $_SESSION['cart'][$item['product_id']] = [
                    'name' => $item['product_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            }
        }
        // ここで、データベースにカートが存在しない場合は新規作成、存在する場合はそのカートIDをセッションに保存することも検討
        // 今回は、データベースから読み込んだカート内容をセッションに反映するのみとする

        header("Location: mypage.php");
        exit;
    } else {
        echo "メールアドレスまたはパスワードが間違っています。";
    }

} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}

?>
