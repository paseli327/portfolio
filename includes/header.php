<?php
$page_name = basename($_SERVER['PHP_SELF']);
switch ($page_name) {
    case 'index.php':
        $page_title = 'トップページ';
        break;
    case 'product.php':
        $page_title = '商品一覧';
        break;
    case 'login.php':
        $page_title = 'ログイン';
        break;
    case 'register.php':
        $page_title = '会員登録';
        break;
    case 'mypage.php':
        $page_title = 'マイページ';
        break;
    case 'cart.php':
        $page_title = 'カート';
        break;
    case 'checkout.php':
        $page_title = '購入ページ';
        break;
    case 'credit_register.php':
        $page_title = 'クレジットカード登録';
        break;
    case 'credit_confirm.php':
        $page_title = 'クレジットカード登録確認';
        break;
    case 'search.php':
        $page_title = '商品検索';
        break;
    default:
        $page_title = 'CCドーナツ';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/reset.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php
    $noindex_pages = ['credit_register.php', 'credit_confirm.php'];

    if (in_array($page_name, $noindex_pages)): ?>
        <meta name="robots" content="noindex">
    <?php endif; ?>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header>
    <nav class="gMenu">
        <input class="menu-btn" type="checkbox" id="menu-btn">
        <label class="menu-icon menu-icon-open" for="menu-btn">
            <span class="navicon"></span>
        </label>
        <label class="menu-icon menu-icon-close" for="menu-btn">
            <span class="close-icon">✕</span>
        </label>
        <ul class="menu">
            <img src="images/pcMainLogo.png" alt="CCドーナツ" class="logo1">
            <div class="menuSize">
                <li><a href="index.php">TOP</a></li>
                <li><a href="product.php">商品一覧</a></li>
                <li><a href="#">よくある質問</a></li>
                <li><a href="#">お問い合わせ</a></li>
                <li><a href="#">当サイトのポリシー</a></li>
            </div>
        </ul>
    </nav>
    <div class="header">
        <div class="headerLogo">
            <a href="index.php"><img src="images/pcMainLogo.png" alt="CCドーナツ" class="logo"></a>
        </div>
        <div class="headrLogin">
            <a href="login.php"><img src="images/pcLogin.png" alt="ログイン" class="loginImg"></a>
            <a href="cart.php"><img src="images/pcCart.png" alt="カート" class="cart"></a>
        </div>
    </div>
    <div class="search">
        <form action="search.php" method="get">
            <div class="search-container">
                <button type="submit"><img src="images/Vector.png"></button>
                <input type="text" id="search" name="search" placeholder="商品を検索...">
                <div class="suggestions" id="suggestions"></div>
            </div>
        </form>
    </div>
</header>
