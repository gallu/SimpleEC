-- 1レコードが1商品を表す
CREATE TABLE items (
  item_id SERIAL COMMENT '内部的な商品id',
  item_name VARCHAR(128) NOT NULL COMMENT '商品名',
  item_detail TEXT COMMENT '商品の説明',
  item_price INT UNSIGNED NOT NULL COMMENT '商品の値段(単価)',
  item_tax_rate INT UNSIGNED DEFAULT NULL COMMENT '消費税率: NULLならプログラムにあるデフォルトの消費税率とする',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (item_id)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが1商品を表す';

-- 1レコードが「1管理者の認証情報」を表す
CREATE TABLE admin_accounts (
  admin_account_id VARBINARY(128) NOT NULL COMMENT 'ログイン用のID',
  admin_account_pass VARBINARY(255) NOT NULL COMMENT 'ログイン用のパスワード(hash)',
  admin_account_name VARCHAR(128) DEFAULT '' COMMENT '管理アカウント表示名',
  error_num INT UNSIGNED DEFAULT 0 COMMENT '連続したエラー回数',
  lock_time DATETIME DEFAULT NULL COMMENT 'ロック解除時間',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (admin_account_id)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「1管理者の認証情報」を表す';


-- 1レコードが「1人の1回の注文」を表す
CREATE TABLE orders (
  order_id SERIAL COMMENT '注文ID',
  -- 購入者情報
  orderer_name VARCHAR(128) NOT NULL COMMENT '注文者名',
  orderer_email VARBINARY(254) NOT NULL COMMENT '注文者email',
  orderer_tel VARBINARY(16) NOT NULL COMMENT '注文者電話番号',
  orderer_zip VARBINARY(8) NOT NULL COMMENT '注文者郵便番号: フォーマットは ddd-dddd',
  orderer_address VARCHAR(256) NOT NULL COMMENT '注文者住所',
  -- その他情報
  order_notices TEXT COMMENT '特記事項',
  order_price_total INT UNSIGNED NOT NULL COMMENT '合計金額(二次情報): 一次は「商品明細」の合計',
  -- status
  payment_at DATETIME DEFAULT NULL COMMENT '入金確認日時',
  shipment_at DATETIME DEFAULT NULL COMMENT '発送処理日時',
  -- 
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時/注文日時',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (order_id)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「1人の1回の注文」を表す';

-- 1レコードが「"1人の1回の注文"の1商品明細」を表す
CREATE TABLE order_details (
  order_detail_id SERIAL COMMENT '注文明細ID',
  order_id BIGINT UNSIGNED NOT NULL COMMENT '注文ID',
  --
  item_id BIGINT UNSIGNED NOT NULL COMMENT '内部的な商品id',
  order_num INT UNSIGNED NOT NULL COMMENT '注文個数',
  item_name VARCHAR(128) NOT NULL COMMENT '商品名',
  item_price INT UNSIGNED NOT NULL COMMENT '商品の値段(単価)',
  -- item_data LONGBLOB COMMENT '商品情報一式(json)',
  -- 
  CONSTRAINT fk_order_details_order_id FOREIGN KEY (order_id) REFERENCES orders (order_id),
  PRIMARY KEY (order_id)
)CHARACTER SET 'utf8mb4', ENGINE=InnoDB, COMMENT='1レコードが「"1人の1回の注文"の1商品明細」を表す';


