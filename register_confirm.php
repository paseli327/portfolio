<?php
session_start();

// POSTデータ取得とエスケープ
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$furigana = htmlspecialchars($_POST['furigana'], ENT_QUOTES, 'UTF-8');
$postcode_a = htmlspecialchars($_POST['postcode_a'], ENT_QUOTES, 'UTF-8');
$postcode_b = htmlspecialchars($_POST['postcode_b'], ENT_QUOTES, 'UTF-8');
$address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
$mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
$mail_confirm = htmlspecialchars($_POST['mail_confirm'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
$password_confirm = htmlspecialchars($_POST['password_confirm'], ENT_QUOTES, 'UTF-8');

// エラーチェック
$errors = [];

// メール一致確認
if ($mail !== $mail_confirm) {
    $errors[] = "メールアドレスが一致しません。";
}

// パスワード一致確認
if ($password !== $password_confirm) {
    $errors[] = "パスワードが一致しません。";
}

// 必須チェック
if (!$name || !$furigana || !$postcode_a || !$postcode_b || !$address || !$mail || !$password) {
    $errors[] = "未入力の項目があります。";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<a href='register.php'>戻る</a>";
    exit;
}
?>
<?php require 'includes/header.php'; ?>
    <p class="page"><a href="index.php">トップ</a>><a href="login.php">ログイン</a>><a href="register.php">会員登録</a>><span>入力確認</span></p>
 <div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

    <h1 class="title19">入力確認</h1>
    <div class="louder">
        <div class="keepheart">
        <div class="overture">
            名前<p><?= $name ?></p>
        </div>
        <div class="overture">
            フリガナ<p><?= $furigana ?></p>
        </div>
        <div class="overture">
            郵便番号<p><?= $postcode_a ?><?= $postcode_b ?></p>
        </div>
        <div class="overture">
          住所<p><?= $address ?></p>
        </div>
        <div class="overture">
            メールアドレス<p><?= $mail ?></p>
        </div>
        <div class="overture">
            メールアドレス確認用<p><?= $mail_confirm ?></p>
        </div>
        <div class="overture">
            パスワード<p><?= $password ?></p>
        </div>
        <div class="overture">
           パスワード確認用<p><?= $password_confirm ?></p>
        </div>
        </div>
    </div>

<form action="register_action.php" method="post" class="rebirthday">
    <!-- 隠しフィールドで値を送信 -->
    <input type="hidden" name="name" value="<?= $name ?>">
    <input type="hidden" name="furigana" value="<?= $furigana ?>">
    <input type="hidden" name="postcode_a" value="<?= $postcode_a ?>">
    <input type="hidden" name="postcode_b" value="<?= $postcode_b ?>">
    <input type="hidden" name="address" value="<?= $address ?>">
    <input type="hidden" name="mail" value="<?= $mail ?>">
    <input type="hidden" name="mail_confirm" value="<?= $mail_confirm ?>">
    <input type="hidden" name="password" value="<?= $password ?>">
    <input type="hidden" name="password_confirm" value="<?= $password_confirm ?>">

    <button type="submit" class="roselia">登録する</button>
</form>
<?php require 'includes/footer.php'; ?>