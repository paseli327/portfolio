<?php require 'includes/header.php'; ?>
    <p class="page"><a href="index.php">トップ</a>><a href="login.php">ログイン</a>><span>会員登録</span></p>
 <div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

    <h1 class="title20">会員登録</h1>
<form action="register_confirm.php" method="post" class="member">
    <div class="memberInfo">
        <p>名前 <span class="red">（必須）</span></p>
        <input type="text" name="name" required>
    </div>
    <div class="memberInfo" id="myForm">
        <p>お名前（フリガナ）<span class="red">（必須）</span></p>
        <input type="text" name="furigana" id="katakanaInput" required>
    </div>
    <div class="memberInfo">
        <p>郵便番号 <span class="red">（必須）</span></p>
        <div class="postcode">
            <input type="text" name="postcode_a" maxlength="3" pattern="\d{3}" style="width:60px;" required>
            <input type="text" name="postcode_b" maxlength="4" pattern="\d{4}" style="width:100px;" required>
        </div>
    </div>
    <div class="memberInfo">
        <p>住所<span class="red">（必須）</span></p>
        <input type="text" name="address" required>
    </div>
    <div class="memberInfo">
        <p>メールアドレス<span class="red">（必須）</span></p>
        <input type="email" name="mail" required>
    </div>
    <div class="memberInfo">
        <p>メールアドレス確認用<span class="red">（必須）</span></p>
        <input type="email" name="mail_confirm" required>
    </div>
    <div class="memberInfo">
        <p>パスワード<span class="red">（必須）</span></p>
        <span class="red2">半角英数字8文字以上20文字以内で入力してください。※記号の使用はできません</span> 
        <input type="text" name="password" minlength="8" id="passwordInput" required>
    </div>
    <div class="memberInfo">
        <p>パスワード確認用<span class="red">（必須）</span></p>
        <input type="text" name="password_confirm" minlength="8" id="passwordConfirmInput" required>
    </div>
    <button type="submit" class="roselia">入力確認する</button>
</form>
<?php require 'includes/footer.php'; ?>
