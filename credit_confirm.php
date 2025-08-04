<?php
session_start();
require 'includes/header.php'; // ヘッダーを読み込み

// ログインしていない、または入力情報がない場合は登録ページへリダイレクト
if (!isset($_SESSION['user_id']) || !isset($_SESSION['credit_card_input'])) {
    header("Location: credit_register.php");
    exit;
}

// セッションから入力値を取得
$input = $_SESSION['credit_card_input'];

// データベース接続情報
$dsn = 'mysql:host=localhost;dbname=ccdonuts;charset=utf8';
$user = 'ccStaff';
$password = 'ccDonuts';

$error_message = '';
$success_message = '';

// 「登録する」ボタンが押された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION['user_id'];
        $card_holder_name = $input['card_holder_name'];
        $card_number = $input['card_number']; 
        $card_company = $input['card_company'];
        $expiration_month = $input['expiration_month'];
        $expiration_year = $input['expiration_year'];
        $security_code = $input['security_code'];

        // データベースに挿入
        $sql = "INSERT INTO credit_cards (user_id, card_holder_name, card_number, card_company, expiration_month, expiration_year, security_code) VALUES (:user_id, :card_holder_name, :card_number, :card_company, :expiration_month, :expiration_year, :security_code)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':card_holder_name', $card_holder_name);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':card_company', $card_company);
        $stmt->bindParam(':expiration_month', $expiration_month);
        $stmt->bindParam(':expiration_year', $expiration_year);
        $stmt->bindParam(':security_code', $security_code);

        if ($stmt->execute()) {
            $success_message = "カード情報が正常に登録されました。";
            unset($_SESSION['credit_card_input']); // セッションの入力情報をクリア
            // 登録後、マイページなどにリダイレクトする
            header("Location: mypage.php?card_registered=true");
            exit;
        } else {
            $error_message = "カード情報の登録に失敗しました。";
        }

    } catch (PDOException $e) {
        $error_message = "データベースエラー: " . $e->getMessage();
        error_log("Credit card registration DB error: " . $e->getMessage()); // エラーログの記録
    }
}
?>

<p class="page"><a href="index.php">トップ</a>><a href="mypage.php">マイページ</a>><a href="credit_register.php">カード情報登録</a>><span>入力情報確認</span></p>
<?php if (isset($_SESSION['user_name'])): // ログインユーザー名を表示?>
<?php endif; ?>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>
<div style="background-color:#fff3cd; color:#856404; padding:10px; border:1px solid #ffeeba; margin-bottom:15px; font-weight:bold; text-align:center; font-size:18px;">
⚠ テストサイトです。本物のクレジットカード情報を入力しないでください。
</div>

<h1 class="title16">入力情報確認</h1>

<?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
<?php endif; ?>

<?php if ($success_message): ?>
    <p style="color: green;"><?= $success_message ?></p>
<?php endif; ?>

<div class="popipa">
    <div class="pipopa">
    <div class="popipapapipopa">
    お名前<p><?= htmlspecialchars($input['card_holder_name'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="popipapapipopa">
    カード番号<p><?= htmlspecialchars($input['card_number'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="popipapapipopa">
    カード会社<p><?= htmlspecialchars($input['card_company'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="popipapapipopa">
    有効期限
    <span class="span"><p><?= htmlspecialchars($input['expiration_month'], ENT_QUOTES, 'UTF-8') ?></p>　月</span>
    <span class="span"><p><?= htmlspecialchars($input['expiration_year'], ENT_QUOTES, 'UTF-8') ?></p>　年</span>
    </div>
    <div class="popipapapipopa">
    セキュリティコード<p><?= htmlspecialchars($input['security_code'], ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    </div>
</div>
<form action="credit_confirm.php" method="post" class="louder">
    <button type="submit" class="roselia">登録する</button>
</form>
<div style="background-color:#fff3cd; color:#856404; padding:10px; border:1px solid #ffeeba; margin-bottom:15px; font-weight:bold; text-align:center; font-size:18px;">
⚠ テストサイトです。本物のクレジットカード情報を入力しないでください。
</div>
<script>
window.onload = function() {
    alert('⚠ このサイトはテスト用です。本物のクレジットカード情報は入力しないでください。');
};
</script>
<?php require 'includes/footer.php'; ?>