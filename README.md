# お問い合わせフォーム

## 環境構築

### Dockerビルド

1. リポジトリをクローンする

```bash
git clone git@github.com:m-iseda-GH/contact-form-app.git
```

2. プロジェクトディレクトリへ移動する

```bash
cd contact-form
```

3. `.env.example` をコピーして `.env` を作成する

```bash
cp .env.example .env
```

4. Composerパッケージをインストールする

```bash
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php82-composer:latest \
  composer install --ignore-platform-reqs
```

5. Sailを起動する

```bash
./vendor/bin/sail up -d
```

6. アプリケーションキーを作成する

```bash
./vendor/bin/sail artisan key:generate
```

7. マイグレーションとシーディングを実行する

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

8. フロントエンド依存関係をインストールする

```bash
./vendor/bin/sail npm install
```

9. Viteを起動する

```bash
./vendor/bin/sail npm run dev
```

---

## 使用技術

* PHP 8.2
* Laravel 10
* Laravel Sail
* Laravel Fortify
* MySQL 8.0
* Vite
* Tailwind CSS
* phpMyAdmin

---

## URL

### アプリケーション

```text
http://localhost/
```

### 管理画面

```text
http://localhost/admin
```

### phpMyAdmin

```text
http://localhost:8080
```

---

## ログイン情報

Seeder実行後、以下の管理者ユーザーでログインできます。

```text
メールアドレス: test@example.com
パスワード: password
```

---

## 機能一覧

### 公開側

* お問い合わせ入力
* 入力内容のバリデーション
* 入力内容確認
* 修正ボタンによる入力画面への戻り
* お問い合わせ送信
* 送信完了画面表示
* お問い合わせ種類の選択
* タグの複数選択

### 管理側

* 管理者登録
* 管理者ログイン
* 管理者ログアウト
* お問い合わせ一覧表示
* お問い合わせ詳細表示
* お問い合わせ削除
* 削除確認モーダル
* 名前検索
* メールアドレス検索
* 性別検索
* お問い合わせ種類検索
* 日付検索
* 検索条件リセット
* ページネーション
* CSVエクスポート
* タグ追加
* タグ編集
* タグ削除
* タグ削除確認モーダル

### API

* お問い合わせ一覧取得
* お問い合わせ詳細取得
* お問い合わせ新規登録
* お問い合わせ更新
* お問い合わせ削除

---

## APIエンドポイント

| メソッド   | URL                       | 内容         |
| ------ | ------------------------- | ---------- |
| GET    | `/api/contacts`           | お問い合わせ一覧取得 |
| GET    | `/api/contacts/{contact}` | お問い合わせ詳細取得 |
| POST   | `/api/contacts`           | お問い合わせ新規登録 |
| PUT    | `/api/contacts/{contact}` | お問い合わせ更新   |
| DELETE | `/api/contacts/{contact}` | お問い合わせ削除   |

---

## テーブル設計

### users テーブル

| カラム名              | 型               | 制約               |
| ----------------- | --------------- | ---------------- |
| id                | bigint unsigned | primary key      |
| name              | varchar(255)    | not null         |
| email             | varchar(255)    | unique, not null |
| email_verified_at | timestamp       | nullable         |
| password          | varchar(255)    | not null         |
| remember_token    | varchar(100)    | nullable         |
| created_at        | timestamp       | nullable         |
| updated_at        | timestamp       | nullable         |

---

### categories テーブル

| カラム名       | 型               | 制約          |
| ---------- | --------------- | ----------- |
| id         | bigint unsigned | primary key |
| content    | varchar(255)    | not null    |
| created_at | timestamp       | nullable    |
| updated_at | timestamp       | nullable    |

---

### contacts テーブル

| カラム名        | 型               | 制約          |
| ----------- | --------------- | ----------- |
| id          | bigint unsigned | primary key |
| category_id | bigint unsigned | foreign key |
| first_name  | varchar(255)    | not null    |
| last_name   | varchar(255)    | not null    |
| gender      | tinyint         | not null    |
| email       | varchar(255)    | not null    |
| tel         | varchar(255)    | not null    |
| address     | varchar(255)    | not null    |
| building    | varchar(255)    | nullable    |
| detail      | text            | not null    |
| created_at  | timestamp       | nullable    |
| updated_at  | timestamp       | nullable    |

---

### tags テーブル

| カラム名       | 型               | 制約               |
| ---------- | --------------- | ---------------- |
| id         | bigint unsigned | primary key      |
| name       | varchar(255)    | unique, not null |
| created_at | timestamp       | nullable         |
| updated_at | timestamp       | nullable         |

---

### contact_tag テーブル

| カラム名       | 型               | 制約          |
| ---------- | --------------- | ----------- |
| id         | bigint unsigned | primary key |
| contact_id | bigint unsigned | foreign key |
| tag_id     | bigint unsigned | foreign key |
| created_at | timestamp       | nullable    |
| updated_at | timestamp       | nullable    |

---

## ER図

以下にER図を配置してください。

```text
docs/er-diagram.png
```

または、提出時に指定された形式に合わせてER図を添付してください。

---

## テスト

以下のコマンドでテストを実行できます。

```bash
./vendor/bin/sail artisan test
```

実行時にPHPまたはPDOに関する deprecated 警告が表示される場合がありますが、テスト自体は通過しています。

---

## 補足

CSVエクスポートでは、検索条件を保持した状態で出力できます。
また、日本語の文字化け対策としてBOM付きUTF-8で出力しています。

作成日時は `Asia/Tokyo` に設定しています。
