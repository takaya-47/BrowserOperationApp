<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Dotenv\Dotenv;

require_once('vendor/autoload.php');

class OperateBrowser
{
  protected $driver;

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
   * 注文データを収集します
   *
   * @return void
   */
  public function collect_product_data(): void
  {
    // 注文履歴ページへのリンクをクリック
    $this->driver->findElement(WebDriverBy::id('nav-orders'))
      ->click();
    // ******************************
    // 2020年の注文データを収集する
    // ******************************
    $this->driver->findElement(WebDriverBy::id('a-autoid-1'))
      ->click();
    $this->driver->findElement(WebDriverBy::id('orderFilter_4'))
      ->click();

    $items  = $this->driver->findElement(WebDriverBy::cssSelector('div.a-fixed-left-grid-col.yohtmlc-item.a-col-right > div.a-row > a.a-link-normal'));
    $items->click();
    // 商品名リスト
    $item_name_list = [];
    // 商品URLリスト
    // $item_url_list = [];
    // for ($i = 0; $i < count($items); $i++) {
    //   $item_name_list[] = $items[$i]->getText();
    //   $item_url_list[] = $items[$i]->getAttribute('href');
    // }
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

