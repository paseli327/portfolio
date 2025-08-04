<?php require 'includes/header.php'; ?>
<p class="page"><a href="index.php">トップ</a>><span>ログイン</span></p>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<h1 class="title22">ログイン</h1>
<div class="login">
<form action="login_action.php" method="post" class="loginForm">
    <div class="formGroup">
    メールアドレス<input type="email" name="mail" required>
    </div>
    <div class="formGroup">
    パスワード<input type="password" name="password" required>
    </div>
    <button type="submit">ログイン</button>
</form>
<div class="touroku"><a href="register.php">会員登録はこちら</a></div>
    </div>
<?php require 'includes/footer.php'; ?>
