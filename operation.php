<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Dotenv\Dotenv;

require_once('vendor/autoload.php');

class OperateBrowser extends RemoteWebDriver
{
  protected $host;
  protected $capabilities;
  protected $driver;

  function __construct()
  {
    $this->host = 'http://localhost:4444';
    $this->capabilities = DesiredCapabilities::chrome();
    $this->driver = RemoteWebDriver::create($this->host, $this->capabilities);
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

// 以下でメソッドを実行していく
$driver = new OperateBrowser();
// amazonにログインする
$driver->login();
// ブラウザを閉じて自動操作を終了する
$driver->quit();