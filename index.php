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
use Facebook\WebDriver\Exception\SessionNotCreatedException;

$host = 'http://localhost:4444'; // URL of the Selenium server



function get_driver(string $url): RemoteWebDriver{
   global $host;

    // Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1920,1080',]);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    $driver = RemoteWebDriver::create($host, $capabilities);
    $handles = $driver->getWindowHandles();
    foreach ($handles as $handle) {
    $driver->switchTo()->window($handle);
    if (count($handles) > 1) {
        $driver->close();
    }
  }
  // // After closing all but one window, switch to the last window and close it
  // if (count($handles) > 0) {
  //     $driver->switchTo()->window($handles[0]);
  //     $driver->close();
  // }
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
     return ['status' => 'error','code' => 1,'data' => 'Timeout.']; 
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element.']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout.']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(NoSuchElementException  $e){
     return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
   
}

function get_taiwan_bingo($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-area')));
    $parent_elements = $parent_element->findElements(WebDriverBy::cssSelector('.result-item'));
    $final_res = [];
    foreach ($parent_elements as $parent_element){
        $issue_number   = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
        $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));
        $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.result-item-simple-area-ball-container > .ball'));
        $draw_number = [];
        foreach ($draw_number_parent as $val) {
          $draw_number[] = $val->getText();
        }
        $draw_number = implode(',',$draw_number);
        $final_res[]   = ['draw_count' => substr($issue_number,-4), 'draw_number'=> $draw_number];
      }
    


    $driver->quit();
    return ['status' => 'success','multiple_draws' => "taiwan_bingo", 'data' => $final_res];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    return ['draw_count' => '', 'draw_number'=> implode(',',$draw_number)];
  //   $driver = get_driver($lottery_url);
  //   $wait = new WebDriverWait($driver, 10);
  //   $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('archive-container')));
  //   $parent_element = $parent_element->findElements(WebDriverBy::cssSelector('.results-vsmall'));
  //   $final_res = [];
  //   foreach($parent_element as $val){
  //   $draw_number_parent = $val->findElements(WebDriverBy::cssSelector('.balls li'));
  //   $date_of_fetch      = $val->findElement(WebDriverBy::cssSelector('.date'));
  //   $day_of_fetch       = $val->findElement(WebDriverBy::cssSelector('.date span'));
  //   $remote_week_day     = $day_of_fetch->getText();
  //   $remote_date_of_fetch    = str_replace($remote_week_day,"",$date_of_fetch->getText());
  //   $draw_number = [];
  //   $res = get_time_in_right_zone("Europe/Berlin")['full_date'];
  //   $separated_date =  explode('-',explode(" ",$res)[0]);
  //   $res = implode('-',array_slice($separated_date,0,3));
  //   $new_date_time = new DateTime($res);
  //   $date = $new_date_time->format('F jS, Y');
  //   $week_day = strtoupper($separated_date[3]);
  //   foreach ($draw_number_parent as $val) {
     
  //     $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
  //   }
  //  $final_res[] = ['draw_date' => $remote_date_of_fetch,'converted_date'=>(new DateTime($remote_date_of_fetch))->format('Y-m-d'), 'draw_day' => $remote_week_day,'draw_count' => '', 'draw_number'=> implode(',',$draw_number)];
  // }
  //      $driver->quit();
  //      print_r($final_res);
  //      return ['status' => 'success','multiple_draws' => true, 'data' => $final_res];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.' .$e->getMessage()]; 
    }
}

function get_australia_powerball_7_35($lottery_url){
  try{
      $driver = get_driver($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.elem1')));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.balls li'));
      $draw_count_and_date = $parent_element->findElement(WebDriverBy::cssSelector('.fluid'));
      $draw_count = explode(' ',explode('-',$draw_count_and_date->getText())[0])[1];
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        # code...
        $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => $draw_count, 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }

  function get_badger_5_5_31($lottery_url){
  try{
      $host = 'http://localhost:4444'; // URL of the Selenium server
        // Set up Chrome options to enable headless mode
      $options = new ChromeOptions();
      $capabilities = DesiredCapabilities::chrome();
      $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
      $driver = RemoteWebDriver::create($host, $capabilities);
      $driver = $driver->get($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultsgame')));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.resultsnums li'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }
  function get_baloto_5_43($lottery_url){
  try{
      $driver = get_driver($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('scp-list')));
      $parent_element = $parent_element->findElement(WebDriverBy::cssSelector('.ng-star-inserted'));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.absolute'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }
  function get_banco_20_70($lottery_url){
  try{
      $host = 'http://localhost:4444'; // URL of the Selenium server

      // Set up Chrome options to enable headless mode
      $options = new ChromeOptions();
       //$options->addArguments(['--headless', '--disable-gpu', '--window-size=1920,1080',]);
      $userDataDir = "C:\Users\Administrator\AppData\Local\Google\Chrome\User Data\Profile 6";
      $options->addArguments(["user-data-dir={$userDataDir}"]);
      $capabilities = DesiredCapabilities::chrome();
      $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
      $driver = RemoteWebDriver::create($host, $capabilities);
      $driver = $driver->get($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('restbl')));
      $parent_element = $parent_element->findElement(WebDriverBy::cssSelector('tbody'));
      $draw_number_table_row = $parent_element->findElement(WebDriverBy::cssSelector('tr:nth-child(2)'));
      $draw_number_parent = $draw_number_table_row->findElements(WebDriverBy::cssSelector('li'));
      $draw_count = $draw_number_table_row->findElement(WebDriverBy::cssSelector('td:first-child'));
      $draw_count = $draw_count->getText();
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => str_replace(".","",$draw_count), 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }
  function get_belgium_lotto_6_45($lottery_url){
  try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('game-number')));
    $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    $draw_number = [];
    foreach ($draw_number_parent as $val) {
      array_push($draw_number, $val->getText());
    }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }

function get_bonoloto_6_49($lottery_url){
  try{
      $driver = get_driver($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('scp-list')));
      $parent_element = $parent_element->findElement(WebDriverBy::cssSelector('.ng-star-inserted'));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.absolute'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }
function get_bonus_match_5_5_39($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}
function get_bucko_5_41($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}


function get_carolina_cash_5_5_43($lottery_url){
  try{
      $driver = get_driver($lottery_url);
      $wait = new WebDriverWait($driver, 10);
      $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('scp-list')));
      $parent_element = $parent_element->findElement(WebDriverBy::cssSelector('.ng-star-inserted'));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.absolute'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
      return ['status' => 'success', 'data' =>['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];

    }catch(TimeoutException  $e){
      echo $e->getMessage() . "\n";
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }
    
  }

function get_cash_25_west_virginia_6_25($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}
function get_cash_5_colorado_5_32($lottery_url){
     try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('draw')));
      $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.draw span'));
      $draw_number = [];
      foreach ($draw_number_parent as $val) {
        array_push($draw_number, $val->getText());
      }
      $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}
function get_cash_5_connecticut_5_35($lottery_url){
     try{
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('gvWinningNumbers')));
    $draw_number_parent = $parent_element->findElement(WebDriverBy::cssSelector('tr td:nth-child(2) '));
    $draw_number = str_replace("-",",",str_replace(' ','',$draw_number_parent->getText()));
    $driver->quit();
    return ['status' => 'success', 'data' => ['draw_count' => '', 'draw_number'=> $draw_number]];
    }catch(TimeoutException  $e){
    return ['status' => 'error','code' => 1,'data' => 'Timeout']; 
    }catch(NoSuchElementException  $e){
    return ['status' => 'error','code' => 2,'data' => 'No such element']; 
    }catch(WebDriverCurlException  $e){
     return ['status' => 'error','code' => 3,'data' => 'Curl Timeout']; 
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}

function get_cash_5_indiana_5_45($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}

function get_cash_5_new_jersey_5_45($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}
function get_cash_5_pennsylvania_5_43($lottery_url){
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
    }catch(SessionNotCreatedException  $e){
     return ['status' => 'error','code' => 4,'data' => 'Session not created.']; 
    }

}