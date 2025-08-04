-- データベースの作成（存在する場合は削除して再作成）
DROP DATABASE IF EXISTS `ccdonuts`;
CREATE DATABASE `ccdonuts` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ユーザー作成と権限付与
DROP USER IF EXISTS 'ccStaff'@'localhost';
CREATE USER 'ccStaff'@'localhost' IDENTIFIED BY 'ccDonuts';
GRANT ALL ON ccdonuts.* TO 'ccStaff'@'localhost';

-- データベース選択
USE `ccdonuts`;

-- 会員テーブル（customers）
CREATE TABLE `customers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `furigana` VARCHAR(100) NOT NULL,
  `postcode_a` INT(3) NOT NULL,
  `postcode_b` INT(4) NOT NULL,
  `address` VARCHAR(200) NOT NULL,
  `mail` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 商品テーブル（products）
CREATE TABLE `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `price` INT(11) NOT NULL,
  `introduction` VARCHAR(1000) NOT NULL,
  `is_new` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- クレジットカード情報テーブル（credit_cards）
CREATE TABLE credit_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- ユーザーテーブル（customers）への外部キー
    card_holder_name VARCHAR(255) NOT NULL,
    card_number VARCHAR(255) NOT NULL, -- 注意: 本番環境では暗号化またはトークン化された値を保存
    card_company VARCHAR(50) NOT NULL,
    expiration_month VARCHAR(2) NOT NULL,
    expiration_year VARCHAR(2) NOT NULL,
    security_code VARCHAR(255) NOT NULL, -- 注意: 本番環境では絶対に保存してはいけません
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES customers(id) ON DELETE CASCADE
);
-- カートテーブル（carts）
CREATE TABLE `carts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL, -- customersテーブルへの外部キー
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- カートアイテムテーブル（cart_items）
CREATE TABLE `cart_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cart_id` INT(11) NOT NULL, -- cartsテーブルへの外部キー
  `product_id` INT(11) NOT NULL, -- productsテーブルへの外部キー
  `quantity` INT(11) NOT NULL,
  `price` INT(11) NOT NULL, -- アイテム追加時の価格を記録（商品の価格変更に対応するため）
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT -- 商品が削除されてもカート履歴は残すためRESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 追加: 購入履歴アイテムテーブル（order_items）
CREATE TABLE `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL, -- 顧客ID
  `product_id` INT(11) NOT NULL, -- 商品ID
  `quantity` INT(11) NOT NULL, -- 購入数量
  `purchase_price` INT(11) NOT NULL, -- 購入時の価格
  `purchased_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 購入日時
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 商品初期データ
INSERT INTO `products` (`id`, `name`, `price`, `introduction`, `is_new`) VALUES
(1, 'CCドーナツ 当店オリジナル（5個入り）', 1500, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 0),
(2, 'チョコレートデライト（5個入り）', 1600, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 0),
(3, 'キャラメルクリーム（5個入り）', 1600, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 0),
(4, 'プレーンクラシック（5個入り）', 1500, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 0),
(5, '【新作】サマーシトラス（5個入り）', 1600, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 1),
(6, 'ストロベリークラッシュ（5個入り）', 1800, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 0),
(7, 'フルーツドーナツセット（12個入り）', 3500, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0),
(8, 'フルーツドーナツセット（14個入り）', 4000, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0),
(9, 'ベストセレクションボックス（4個入り）', 1200, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0),
(10, 'チョコクラッシュボックス（7個入り）', 2400, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0),
(11, 'クリームボックス（4個入り）', 1400, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0),
(12, 'クリームボックス（9個入り）', 2800, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 0);

-- テスト用ユーザー（パスワード：aaaaa）
INSERT INTO `customers` (`name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES
('平山 翔大', 'ひらやま しょうた', 000, 1111, 'aaaaaaa', 'aa@aa', 'aaaaa');
-- 複数のユーザーを作成してテストデータを投入しやすくするため、customersテーブルにいくつかユーザーを追加することを推奨します。
-- 例:
INSERT INTO `customers` (`name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES ('田中 太郎', 'たなか たろう', 111, 2222, '東京都', 'tanaka@example.com', 'testpass');
INSERT INTO `customers` (`name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES ('佐藤 花子', 'さとう はなこ', 333, 4444, '大阪府', 'sato@example.com', 'testpass');
INSERT INTO `customers` (`name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES ('鈴木 一郎', 'すずき いちろう', 555, 6666, '神奈川県', 'suzuki@example.com', 'testpass');
INSERT INTO `customers` (`name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES ('高橋 美咲', 'たかはし みさき', 777, 8888, '福岡県', 'takahashi@example.com', 'testpass');

-- テスト用購入履歴データ（実際のアプリケーションでは、これはpayment.phpで動的に挿入されますが、テスト用に手動で追加します）
-- user_idを複数に分散させて、100件以上のデータを作成してください。
INSERT INTO `order_items` (`user_id`, `product_id`, `quantity`, `purchase_price`, `purchased_at`) VALUES
(1, 1, 2, 1500, '2025-07-01 10:00:00'),
(2, 5, 1, 1600, '2025-07-01 10:05:00'),
(1, 2, 3, 1600, '2025-07-01 10:10:00'),
(3, 1, 1, 1500, '2025-07-01 10:15:00'),
(4, 7, 1, 3500, '2025-07-01 10:20:00'),
(1, 3, 2, 1600, '2025-07-01 10:25:00'),
(5, 5, 3, 1600, '2025-07-01 10:30:00'),
(2, 10, 1, 2400, '2025-07-01 10:35:00'),
(3, 1, 1, 1500, '2025-07-01 10:40:00'),
(4, 4, 2, 1500, '2025-07-01 10:45:00'),
(5, 6, 1, 1800, '2025-07-01 10:50:00'),
(1, 9, 2, 1200, '2025-07-01 10:55:00'),
(2, 2, 1, 1600, '2025-07-01 11:00:00'),
(3, 11, 3, 1400, '2025-07-01 11:05:00'),
(4, 8, 1, 4000, '2025-07-01 11:10:00'),
(5, 12, 2, 2800, '2025-07-01 11:15:00'),
(1, 1, 2, 1500, '2025-07-02 10:00:00'),
(2, 3, 1, 1600, '2025-07-02 10:05:00'),
(3, 5, 2, 1600, '2025-07-02 10:10:00'),
(4, 7, 3, 3500, '2025-07-02 10:15:00'),
(5, 9, 1, 1200, '2025-07-02 10:20:00'),
(1, 11, 2, 1400, '2025-07-02 10:25:00'),
(2, 2, 1, 1600, '2025-07-02 10:30:00'),
(3, 4, 2, 1500, '2025-07-02 10:35:00'),
(4, 6, 3, 1800, '2025-07-02 10:40:00'),
(5, 8, 1, 4000, '2025-07-02 10:45:00'),
(1, 10, 2, 2400, '2025-07-02 10:50:00'),
(2, 12, 3, 2800, '2025-07-02 10:55:00'),
(3, 1, 1, 1500, '2025-07-03 10:00:00'),
(4, 3, 2, 1600, '2025-07-03 10:05:00'),
(5, 5, 1, 1600, '2025-07-03 10:10:00'),
(1, 7, 2, 3500, '2025-07-03 10:15:00'),
(2, 9, 3, 1200, '2025-07-03 10:20:00'),
(3, 11, 1, 1400, '2025-07-03 10:25:00'),
(4, 2, 2, 1600, '2025-07-03 10:30:00'),
(5, 4, 1, 1500, '2025-07-03 10:35:00'),
(1, 6, 2, 1800, '2025-07-03 10:40:00'),
(2, 8, 3, 4000, '2025-07-03 10:45:00'),
(3, 10, 1, 2400, '2025-07-03 10:50:00'),
(4, 12, 2, 2800, '2025-07-03 10:55:00');
-- ここにさらにダミーデータを追加して、少なくとも100件のユニークなユーザーの購入履歴が含まれるようにしてください。
-- 例えば、user_idを6, 7, ... と増やし、それぞれのuser_idで複数回購入した履歴を追加します。


-- オートインクリメント値の設定
ALTER TABLE `products` AUTO_INCREMENT = 13;