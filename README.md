44  rails new実行
    ソースコード移動
    Gemパッケージのインストール
    rspecをsptingにインストール
    database設定
        database.ymlの修正
53      bin/rake db:create
    起動確認
        bin/rails s
    タイムゾーン・ロケールの設定
    ジェネレータの設定
        テストフレームワークをrspecに変更
63  rspecの設定
        bin/rails g rspec:install
        設定いろいろ

    ルーティングの設定
        routes.rb設定
        generate

    コントローラ記述
    ERBテンプレート作成
84      app/views/layouts/application.html.erbが基本のHTML
    部分テンプレート作成
        ファイル名は「_」で始まる

    CSS

103 シークレットキーベースの設定
        config/secrets.yml
        秘密データの管理

104 assets:precompileタスクの実行

112 例外の補足
        rescue_from
        500
        403
        404

135 テーブル作成

149 セッション

160 フォームオブジェクト

192 resources

199 resource

205 ルーティングのspec

234 Strong Parameters

262 アクセス制御

282 モデルの関連

299 ページネーション
      kaminari

316 バリデーション
