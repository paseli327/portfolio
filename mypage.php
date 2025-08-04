<?php
session_start();
require 'includes/header.php';
?>
<p class="page"><a href="index.php">トップ</a>><a href="login.php">ログイン</a>><span>ログイン完了</span></p>
<?php if (isset($_SESSION['user_name'])): ?>
<div class="welcome">
    <?php if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])): ?>
        <span>ようこそ　<?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?> 様</span>
        <a href="logout.php" style="text-decoration:none;color:#333;font-weight:bold;">ログアウト</a>
    <?php else: ?>
        <span>ようこそ　ゲスト 様</span>
    <?php endif; ?>
</div>

<?php endif; ?>
<h1 class="title21">ログイン完了</h1>
<div class="page2">
<div class="loginComplete">
<p>ログインが完了しました。</p>
<p>引き続きお楽しみください。</p>
</div>
<div class="loginLinks">
<p><a href="checkout.php">購入ページへすすむ</a></p>
<p><a href="index.php">TOPページにもどる</a></p>
</div>
</div>
<?php require 'includes/footer.php'; ?>
