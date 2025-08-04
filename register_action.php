<?php require 'includes/header.php'; ?>
<?php
session_start();

$dsn = '****';
$user = '****';
$password = '****';

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 入力値取得
    $name = trim($_POST['name']);
    $furigana = trim($_POST['furigana']);
    $postcode_a = trim($_POST['postcode_a']);
    $postcode_b = trim($_POST['postcode_b']);
    $address = trim($_POST['address']);
    $mail = trim($_POST['mail']);
    $mail_confirm = trim($_POST['mail_confirm']);
    $pass = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    // 必須チェック
    if (empty($name) || empty($furigana) || empty($postcode_a) || empty($postcode_b) ||
        empty($address) || empty($mail) || empty($pass)) {
        echo "未入力の項目があります。<br><a href='register.php'>戻る</a>";
        exit;
    }

    // メール重複チェック
    $check_sql = "SELECT id FROM customers WHERE mail = :mail";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':mail', $mail);
    $check_stmt->execute();

    if ($check_stmt->fetch()) {
        echo "このメールアドレスはすでに登録されています。<br><a href='register.php'>戻る</a>";
        require 'includes/footer.php';
        exit;
    }

    // メール一致確認
    if ($mail !== $mail_confirm) {
        echo "メールアドレスが一致しません。<br><a href='register.php'>戻る</a>";
        require 'includes/footer.php';
        exit;
    }

    // パスワード一致確認
    if ($pass !== $pass_confirm) {
        echo "パスワードが一致しません。<br><a href='register.php'>戻る</a>";
        require 'includes/footer.php';
        exit;
    }

    // 新規登録
    $sql = "INSERT INTO customers (name, furigana, postcode_a, postcode_b, address, mail, password)
            VALUES (:name, :furigana, :postcode_a, :postcode_b, :address, :mail, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':furigana', $furigana);
    $stmt->bindParam(':postcode_a', $postcode_a);
    $stmt->bindParam(':postcode_b', $postcode_b);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':password', $pass);
    $stmt->execute();

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit;
}
?>
    <p class="page"><a href="index.php">トップ</a>><a href="login.php">ログイン</a>><a href="register.php">会員登録</a>><a href="register.php">入力確認</a>><span>会員登録完了</span></p>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<h1 class="title18">会員登録完了</h1>
<div class="confirmation">
<div class="message">
    <p>会員登録が完了しました。</p>
    <p>ログインページへお進みください。</p>
</div>
<div class="links">
    <a href="credit_register.php">クレジットカード登録へすすむ</a>
    <a href="checkout.php">購入確認ページへすすむ</a>
</div>
</div>

<?php require 'includes/footer.php'; ?>

