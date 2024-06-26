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
require_once('C:/xampp/htdocs/1kball/utils/constants.php');
require_once('utils/date_time.php');
require_once('db/db_utils.php');

   $host = 'http://localhost:4444'; // URL of the Selenium server

  // Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
   // $options->addArguments(['--headless', '--disable-gpu', '--window-size=1920,1080',]);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    // $driver = RemoteWebDriver::create($host, $capabilities);
    // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/lotto649');
    // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/bingo_bingo?searchData=true');
    // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/4_d');
    // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=204');
    // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=104');
    // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=102');
    // $driver = $driver->get('https://www.nationale-loterij.be/onze-spelen/pick3/uitslagen-trekking/');
    // $driver = $driver->get('https://lotterytexts.com/uruguay/5-de-oro/');
    // $driver = $driver->get('https://www.lotto.net/german-lotto/results/2024');
    // $driver = $driver->get('https://www.lotterypost.com/results/tx/allornothing');
    // $driver = $driver->get('https://www.arizonalottery.com/draw-games/triple-twist/');
    // $driver = $driver->get('https://www.alc.ca/content/alc/en/winning-numbers.html');
    // $driver = $driver->get('https://australia.national-lottery.com/powerball/results');
    // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=203');
    // $driver = $driver->get('https://bet.hkjc.com/marksix/Results.aspx?lang=en');
    // try{

    // $wait = new WebDriverWait($driver, 10);
    // $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('lnl-draw-numbers')));
    // $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.lnl-draw-numbers__winning-number'));
    // $draw_number = [];
    // foreach ($draw_number_parent as $val) {
    //   $draw_number[] = $val->getText();
    // }
    // return ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)];
    //  }catch(TimeoutException  $e){
    //   echo 'Timeout';
    // }catch(NoSuchElementException  $e){
    //   echo 'No such element';
    // }
    
    // $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('.balls')));
    // $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    // $draw_number = [];
    // foreach ($draw_number_parent as $val) {
    //   # code...
    //   $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    // }
    
    // $driver->quit();
    
    // echo json_encode(['draw_count' => '', 'draw_number'=> implode(',',$draw_number)]);
    // foreach (LOTTERIES_INFO as $gh_time => $lottery_array) {
    //   # code...
    //   foreach ($lottery_array as $key => $val) {
    //     # code...

    //     // Set the default timezone to UTC
    //     date_default_timezone_set($val['timezone']);

    //     if(str_contains($val['start_end'],'~')) continue;
    //    // Create a DateTime object with the current time in UTC
    //   $date = new DateTime($val['start_end'], new DateTimeZone($val['timezone']));

    //   // Convert the DateTime object to the Hong Kong timezone
    //   $date->setTimezone(new DateTimeZone('Africa/Accra'));
    //   if($date->format('H:i') === $gh_time){
    //     // echo "The Lottery {$val['lottery_name']} has the correct time conversion.\n";
    //   }else{
    //     echo "The Lottery {$val['lottery_name']} has an incorrect time conversion.\n";
    //     echo "The Gh Time is: {$gh_time}. \n The start end Time is: {$val['start_end']}. \n The timezone is: {$val['timezone']} \n The Remote Time is: {$date->format('H:i')} \n\n\n\n\n";
    //   }
       
    //   }
    // }

    //   date_default_timezone_set('UTC');
    // $date = new Datetime();
    // echo date_default_timezone_get();
    // echo $date->format('Y-m-d-l H:i:s') ;



    // $res = fetch_all('failed_lotteries');
    // print_r($_SESSION);
      $rr = ['m' => 'failed_lotteries', 'n' => 'failed_lot'];
      unset($rr['m']);
      print_r($rr);
      $start_end = '07:05~23:55';
      $timezone  = 'Asia/Taipei';
      $start_end = explode('~', $start_end);
      $current_time = new DateTime();
      $current_time = $current_time->format('H:i');
      $start_time = convert_time_to_other_timezone($start_end[0], $timezone);
      $end_time   = convert_time_to_other_timezone($start_end[1], $timezone);
      $start_time = explode(' ',$start_time['shortend_date'])[1];
      $end_time   = explode(' ',$end_time['shortend_date'])[1];
   
      if($start_time >= $current_time && $end_time <= $current_time){
        echo "Current time  is between $start_time and $end_time";
      }
      
 