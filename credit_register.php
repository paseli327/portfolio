<?php
session_start();
require 'includes/header.php'; // ヘッダーを読み込み

// ログインしていない場合はログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// エラーメッセージの初期化
$error_message = '';

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力値の取得とサニタイズ
    $card_holder_name = htmlspecialchars($_POST['card_holder_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $card_number = htmlspecialchars($_POST['card_number'] ?? '', ENT_QUOTES, 'UTF-8');
    $card_company = htmlspecialchars($_POST['card_company'] ?? '', ENT_QUOTES, 'UTF-8');
    $expiration_month = htmlspecialchars($_POST['expiration_month'] ?? '', ENT_QUOTES, 'UTF-8');
    $expiration_year = htmlspecialchars($_POST['expiration_year'] ?? '', ENT_QUOTES, 'UTF-8');
    $security_code = htmlspecialchars($_POST['security_code'] ?? '', ENT_QUOTES, 'UTF-8');

    // 必須項目のチェック
    if (empty($card_holder_name) || empty($card_number) || empty($card_company) || empty($expiration_month) || empty($expiration_year) || empty($security_code)) {
        $error_message = "すべての項目は必須です。";
    } else {
        // 入力値をセッションに保存して確認ページへリダイレクト
        $_SESSION['credit_card_input'] = [
            'card_holder_name' => $card_holder_name,
            'card_number' => $card_number,
            'card_company' => $card_company,
            'expiration_month' => $expiration_month,
            'expiration_year' => $expiration_year,
            'security_code' => $security_code
        ];
        header("Location: credit_confirm.php");
        exit;
    }
}
?>

<p class="page"><a href="index.php">トップ</a>><a href="mypage.php">マイページ</a>><span>カード情報登録</span></p>
<?php if (isset($_SESSION['user_name'])): // ログインユーザー名を表示?>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<?php endif; ?>
<div style="background-color:#fff3cd; color:#856404; padding:10px; border:1px solid #ffeeba; margin-bottom:15px; font-weight:bold; text-align:center; font-size:18px;">
⚠ テストサイトです。本物のクレジットカード情報を入力しないでください。
</div>

<h1 class="title17">カード情報登録</h1>

<?php if ($error_message): ?>
    <p style="color: red;"><?= $error_message ?></p>
<?php endif; ?>
<div class="creditForm">
    <form action="credit_register.php" method="post" class="creditForm2">
        <div class="formGroup2">
            <label for="card_holder_name">お名前 <span class="red"> (必須)</span></label>
            <input type="text" id="card_holder_name" name="card_holder_name" value="<?= htmlspecialchars($_SESSION['credit_card_input']['card_holder_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="formGroup2">
            <label for="card_number">カード番号 <span class="red"> (必須)</span></label>
            <input type="text" id="card_number" name="card_number" pattern="\d*" maxlength="16" minlength="14" value="<?= htmlspecialchars($_SESSION['credit_card_input']['card_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="formGroup3">
            <label>カード会社 <span class="red"> (必須)</span></label>
            <div class="cardCompany">
            <input type="radio" id="jcb" name="card_company" value="JCB" <?= (($_SESSION['credit_card_input']['card_company'] ?? '') === 'JCB') ? 'checked' : '' ?> required>
            <label for="jcb">JCB</label>
            <input type="radio" id="visa" name="card_company" value="Visa" <?= (($_SESSION['credit_card_input']['card_company'] ?? '') === 'Visa') ? 'checked' : '' ?>>
            <label for="visa">Visa</label>
            <input type="radio" id="mastercard" name="card_company" value="Mastercard" <?= (($_SESSION['credit_card_input']['card_company'] ?? '') === 'Mastercard') ? 'checked' : '' ?>>
            <label for="mastercard">Mastercard</label>
            </div>
        </div>
        <div class="formGroup2">
            <label>有効期限<span class="red"> (必須)</span></label>
            <p><input type="text" id="expiration_month" name="expiration_month" pattern="\d*" placeholder="MM" maxlength="2" style="width: 50px;" value="<?= htmlspecialchars($_SESSION['credit_card_input']['expiration_month'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required> 月</p>
            <p><input type="text" id="expiration_year" name="expiration_year" pattern="\d*" placeholder="YY" maxlength="2" style="width: 50px;" value="<?= htmlspecialchars($_SESSION['credit_card_input']['expiration_year'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required> 年</p>
        </div>
        <div class="formGroup2">
            <label for="security_code">セキュリティコード <span class="red"> (必須)</span></label>
            <input type="text" id="security_code" name="security_code" pattern="\d*" minlength="3" maxlength="6" value="<?= htmlspecialchars($_SESSION['credit_card_input']['security_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="roselia2">
        <button type="submit">入力確認する</button>
        </div>
    </form>
</div>
<div style="background-color:#fff3cd; color:#856404; padding:10px; border:1px solid #ffeeba; margin-bottom:15px; font-weight:bold; text-align:center; font-size:18px;">
⚠ テストサイトです。本物のクレジットカード情報を入力しないでください。
</div>

<script>
window.onload = function() {
    alert('⚠ このサイトはテスト用です。本物のクレジットカード情報は入力しないでください。');
};
</script>
<?php require 'includes/footer.php'; ?>