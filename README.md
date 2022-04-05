# 使い方
1. ターミナルを開き、以下コマンドを実行
**chromedriver --port=4444**
2. ターミナルで別タブを開き、以下コマンドを実行
**cd** *プログラムのディレクトリパス*
**php createCsv.php**

# 要件定義
## 機能要件
以下のデータをブラウザの自動操作により取得する
1. 対象
    過去にAmazonで購入した商品
2. 資料の中身
    1. 資料１
        以下のデータをCSVで作成すること
        1. 注文日
        2. 商品名
        3. 商品URL
        4. 商品価格
    2. 資料２
        以下のデータをPDFで取得すること
        1. 納品書兼領収書
## 非機能要件
1. CSVにはヘッダーを設けること
# 設計
1. [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/downloads)（ブラウザ自動化ツール）をインストール（ブラウザのバージョンとメジャーバージョンは一致している必要あり。今回は99。）（[https://sites.google.com/chromium.org/driver/](https://sites.google.com/chromium.org/driver/)）
2. Composerを使ってphp-webdriver（ChromedriverをPHPで動かすのに必要なライブラリ。Metaが作ってくれたらしい。）をインストール
    1. composer require php-webdriver/webdriver
    （参考）https://github.com/php-webdriver/php-webdriver
3. CSVデータ作成プログラムを実装
    1. Amazonのログインページに遷移
    2. ログイン
    3. 注文履歴ページに遷移
    4. ドロップダウンリストで年度を選択
    5. 各データを取得及びデータごとに配列に格納する
    6. 作成したデータごとの配列からCSVファイルを生成
4. 納品書兼領収書のダウンロードプログラムを実装
# 参考資料等
- [https://qiita.com/tsuuuuu_san/items/61379b797878d0e0f4d7#準備後の例](https://qiita.com/tsuuuuu_san/items/61379b797878d0e0f4d7#%E6%BA%96%E5%82%99%E5%BE%8C%E3%81%AE%E4%BE%8B)
- [https://github.com/php-webdriver/php-webdriver/wiki/Chrome](https://github.com/php-webdriver/php-webdriver/wiki/Chrome)