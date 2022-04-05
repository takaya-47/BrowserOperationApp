<?php

require_once('./operation.php');

class createCsv
{
  /**
   * 収集したデータを使ってCSVファイルを生成します
   *
   * @return void
   */
  public static function create_csv_file(): void
  {
    $browser = new Operation();
    $browser->init();
    // サイトログイン
    $browser->login();
    // 注文履歴等を取得
    list($item_name_list, $item_url_list, $order_date_list, $amount_list) = $browser->collect_product_data();
    // csvファイルを開く
    $file = fopen('./orderhistory.csv', 'w');
    // ヘッダーを書き込む
    $header = ['商品名', '商品URL', '注文日', '金額'];
    fputcsv($file, $header);
    for ($i = 0; $i < count($item_name_list); $i++) {
      $row = [];
      array_push($row, $item_name_list[$i], $item_url_list[$i], $order_date_list[$i], $amount_list[$i]);
      fputcsv($file, $row);
    }
    // csvファイルを閉じる
    fclose($file);

    $browser->quit();
  }
}

// メソッドの実行
createCsv::create_csv_file();
