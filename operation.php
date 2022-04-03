<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Dotenv\Dotenv;

require_once('vendor/autoload.php');

class OperateBrowser
{
  protected $driver;

  const DOMAIN = 'https://www.amazon.co.jp/';

  /**
   * ブラウザ自動操作のための初期設定を行います
   *
   * @return void
   */
  public function init(): void
  {
    $host = 'http://localhost:4444';
    $capabilities = DesiredCapabilities::chrome();
    $this->driver = RemoteWebDriver::create($host, $capabilities);

    echo '*****************************' . PHP_EOL;
    echo 'ブラウザ自動操作を開始します' . PHP_EOL;
    echo '*****************************' . PHP_EOL;
  }

  /**
   * サイトにログインします
   *
   * @return void
   */
  public function login(): void
  {
    // ログインIDやパスワードなどの環境変数を読み込んでおく
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // ログインページに遷移する
    $this->driver->get('https://www.amazon.co.jp/ap/signin?openid.pape.max_auth_age=0&openid.return_to=https%3A%2F%2Fwww.amazon.co.jp%2Fref%3Dnav_ya_signin&openid.identity=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.assoc_handle=jpflex&openid.mode=checkid_setup&openid.claimed_id=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&');
    // メールアドレスの入力
    $this->driver->findElement(WebDriverBy::id('ap_email'))
      ->sendKeys($_ENV['MAIL_ADDRESS']);
    $this->driver->findElement(WebDriverBy::id('continue'))
      ->click();
    // パスワードの入力
    $this->driver->findElement(WebDriverBy::id('ap_password'))
      ->sendKeys($_ENV['PASSWORD']);
    $this->driver->findElement(WebDriverBy::id('signInSubmit'))
      ->click();
  }

  /**
   * 注文データを収集し、それぞれのデータを配列として返却します
   *
   * @return array
   */
  public function collect_product_data(): array
  {
    // 商品名リスト
    $item_name_list  = [];
    // 商品URLリスト
    $item_url_list   = [];
    // 注文日のリスト
    $order_date_list = [];
    // 注文金額のリスト
    $amount_list     = [];

    // 注文履歴ページへのリンクをクリック
    $this->driver->findElement(WebDriverBy::id('nav-orders'))
      ->click();
    // 古い年度の注文データから収集していく
    $this->driver->findElement(WebDriverBy::id('a-autoid-1'))
      ->click();



    $this->driver->findElement(WebDriverBy::id('orderFilter_4'))
      ->click();

    // １ページにある全ての商品要素
    $items_per_page = $this->driver->findElements(WebDriverBy::cssSelector('div.a-box-group.a-spacing-base.order.js-order-card'));
    // 1ページにある商品要素の数だけ処理を繰り返す
    for ($i = 0; $i < count($items_per_page); $i++) {
      $elements_item_name_and_url = $this->driver->findElements(WebDriverBy::cssSelector('div.a-fixed-left-grid-col.yohtmlc-item.a-col-right > div.a-row > a.a-link-normal'));
      $elements_order_date        = $this->driver->findElements(WebDriverBy::cssSelector('div.a-fixed-right-grid-inner > div.a-fixed-right-grid-col.a-col-left > div.a-row > div.a-column.a-span3 > div.a-row.a-size-base > span.a-color-secondary.value'));
      $elements_amount            = $this->driver->findElements(WebDriverBy::cssSelector('div.a-fixed-right-grid-col.a-col-left > div.a-row > div.a-column.a-span2.yohtmlc-order-total > div.a-row.a-size-base > span.a-color-secondary.value'));

      // 取得した要素を一つずつリストに格納
      $item_name_list[]  = $elements_item_name_and_url[$i]->getText();
      $item_url_list[]   = self::DOMAIN . $elements_item_name_and_url[$i]->getAttribute('href'); // getAttribute()ではパラメータしか取得しなかったので先頭のドメインも付け足す
      $order_date_list[] = $elements_order_date[$i]->getText();

      // 金額は¥マークを取り除いてから格納する
      $amount = $this->remove_dollars($elements_amount[$i]->getText());
      $amount_list[] = $amount;
    }
  }

  /**
   * 金額から¥マークを取り除いた文字列を返却します
   *
   * @param  mixed $amount_with_dollars
   * @return string
   */
  public function remove_dollars(string $amount_with_dollars): string
  {
    $index_of_space = strpos($amount_with_dollars, ' ') + 1;
    return substr($amount_with_dollars, $index_of_space);
  }

  /**
   * ブラウザ操作を終了します
   *
   * @return void
   */
  public function quit(): void
  {
    $this->driver->quit();
    echo '*****************************' . PHP_EOL;
    echo '自動操作を終了しました' . PHP_EOL;
    echo '*****************************' . PHP_EOL;
  }
}

$browser = new OperateBrowser();
$browser->init();
$browser->login();
$browser->collect_product_data();
$browser->quit();
