<?php

require_once('vendor/autoload.php');
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\Internal\WebDriverCurlException;

$host = 'http://localhost:4444'; // URL of the Selenium server
$lottery_urls = [
    'belgium_3d'      => 'https://www.nationale-loterij.be/onze-spelen/pick3/uitslagen-trekking/13-08-2023?', 
    'china_3d'        => 'https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=102', 
    'china_p5'        => 'https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=203',
    'china_happy8'    => 'https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=104',
    'china_7_stars'   => 'https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=204',
    'taiwan'          => 'https://www.taiwanlottery.com/lotto/result/3_d',
    'taiwan_4d'       => 'https://www.taiwanlottery.com/lotto/result/4_d',
    'taiwan_bingo'    => 'https://www.taiwanlottery.com/lotto/result/bingo_bingo?searchData=true', 
    'taiwan_lotto'    => 'https://www.taiwanlottery.com/lotto/result/lotto649', 
    'hong_kong_mark6' => 'https://bet.hkjc.com/marksix/Results.aspx?lang=ch'
];


function get_driver(string $url): RemoteWebDriver{
   global $host;

    // Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1280,1024']);
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1920,1080']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    $driver = RemoteWebDriver::create($host, $capabilities);
    return $driver->get($url);
    
}

function get_hong_kong_mark6($lottery_url){
    try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_count = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell1 > a'));
    $draw_count = $draw_count->getText();
    $draw_date = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell2'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = '';
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers .= (string)intval($value).",";
    }
    // if(str_ends_with($draw_numbers,',')) substr($draw_numbers,-1,1);
    $driver->quit();
    $draw_count = explode(' ',$draw_count)[0];
    $draw_count = explode('/',$draw_count)[1];
    $draw_count = strlen($draw_count) < 4 ? str_pad($draw_count,4,0,STR_PAD_LEFT) : substr($draw_count,0,4);
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    return ['status' => 'success', 'data' => ['draw_count' =>  $draw_count, 'draw_number'=> $draw_numbers]];

    }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
   
}
function get_belgium_3d($lottery_url){
  try{
    $host = 'http://localhost:4444'; // URL of the Selenium server
    //Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver = $driver->get($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lnl-draw-numbers')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lnl-draw-numbers__winning-number'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      $draw_number[] = $val->getText();
    }
    $driver->quit();
    $draw_count = $_SESSION['belgium_3d_draw_count'] = isset($_SESSION['belgium_3d_draw_count']) ? intval($_SESSION['belgium_3d_draw_count']) + 1 : 1;
    return ['status' => 'success', 'data' => ['draw_count' => str_pad($draw_count,4,0,STR_PAD_LEFT), 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }


}
function get_china_3d($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lot_js')));
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('#kj_detail_issue > option'));
    $issue_number = substr($issue_number->getText(),0,7);
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lot_kjmub > #kj_detail_result > i'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_china_happy8($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lot_js')));
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('#kj_detail_issue > option'));
    $issue_number = substr($issue_number->getText(),0,7);
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lot_kjmub > #kj_detail_result > i'));
    $draw_number = [];
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)]];
     }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_china_7_stars($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lot_js')));
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('#kj_detail_issue > option'));
    $issue_number = substr($issue_number->getText(),0,5);
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lot_kjmub > #kj_detail_result > i'));
    $draw_number = [];
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_china_p5($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lot_js')));
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('#kj_detail_issue > option'));
    $issue_number = substr($issue_number->getText(),0,5);
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lot_kjmub > #kj_detail_result > i'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)]];
   }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_taiwan_3d($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-item-simple-area')));
    
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
    $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));
    $lottery_date = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-date'));
    $lottery_date = explode(':',$lottery_date->getText())[1];

    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.winner-number-other-container > .ball'));
    $draw_number = '';
    //echo $draw_number_parent->getText();
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number .= intval($val->getText());
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> $draw_number]];
    }catch(TimeoutException  $e){
     return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_taiwan_4d($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-item-simple-area')));
    
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
    $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));
    $lottery_date = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-date'));
    $lottery_date = explode(':',$lottery_date->getText())[1];

    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.winner-number-other-container > .ball'));
    $draw_number = '';
    //echo $draw_number_parent->getText();
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number .= intval($val->getText());
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> $draw_number]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_taiwan_lotto($lottery_url){
    try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-item-simple-area')));
    
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
    $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));
    $lottery_date = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-date'));
    $lottery_date = explode(':',$lottery_date->getText())[1];

    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.winner-number-other-container > .ball'));
    $draw_number = '';
    //echo $draw_number_parent->getText();
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number .= intval($val->getText());
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> $draw_number]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
   
}

function get_taiwan_bingo($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
     $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-item-simple-area')));
    
    $issue_number = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
    $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));


    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.result-item-simple-area-ball-container > .ball'));
    $draw_number = [];
    //echo $draw_number_parent->getText();
    foreach ($draw_number_parent as $key => $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    
    
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}

function get_five_de_oro_5_48($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lottery-balls')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = $val->getText();
    }
    
    $driver->quit();
    return ['status' => 'success', 'data' =>  ['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_sixaus49_6_49($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('balls')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    }
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_all_or_nothing_day_texas_12_24($lottery_url){
     try{
      $host = 'http://localhost:4444'; // URL of the Selenium server

     // Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver = $driver->get($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultsnums')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    }
    
    $driver->quit();
    $draw_count = $_SESSION['all_or_nothing_day_texas_12_24'] = isset($_SESSION['all_or_nothing_day_texas_12_24']) ? intval($_SESSION['all_or_nothing_day_texas_12_24']) + 1 : 1;
    return ['status' => 'success', 'data' => ['draw_count' =>  $draw_count, 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }

}
function get_arizona_triple_twist_6_42($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('draw')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('span'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    }
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}

function get_atlantic_6_49($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#lotto-Lotto649')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      # code...
      $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    }
    
    $driver->quit();
    $draw_count = $_SESSION['all_or_nothing_day_texas_12_24'] = isset($_SESSION['all_or_nothing_day_texas_12_24']) ? intval($_SESSION['all_or_nothing_day_texas_12_24']) + 1 : 1;
    return ['status' => 'success', 'data' => ['draw_count' => $draw_count, 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
}
function get_australia_powerball_7_35($lottery_url){
  try{
      $driver = get_driver($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.balls')));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        # code...
        $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }
    
  }

