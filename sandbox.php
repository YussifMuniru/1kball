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
    $options->addArguments(["--headless",'--disable-gpu', '--window-size=1920,1080',]);
    // $userDataDir = "C:\Users\Administrator\AppData\Local\Google\Chrome\User Data\Profile 6";
    // $options->addArguments(["user-data-dir={$userDataDir}"]);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    $driver = RemoteWebDriver::create($host, $capabilities); 

  //   foreach(LOTTERIES_INFO as $key => $group_lotteries){
  //   foreach($group_lotteries as $val){} 
  // }

    // // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/lotto649');
    // // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/bingo_bingo?searchData=true');
    // // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/4_d');
    // // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=204');
    // // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=104');
    // // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=102');
    // // $driver = $driver->get('https://www.nationale-loterij.be/onze-spelen/pick3/uitslagen-trekking/');
    // // $driver = $driver->get('https://lotterytexts.com/uruguay/5-de-oro/');
    // // $driver = $driver->get('https://www.lotto.net/german-lotto/results/2024');
    // // $driver = $driver->get('https://www.lotterypost.com/results/tx/allornothing');
    // // $driver = $driver->get('https://www.arizonalottery.com/draw-games/triple-twist/');
    // // $driver = $driver->get('https://www.alc.ca/content/alc/en/winning-numbers.html');
    // // $driver = $driver->get('https://australia.national-lottery.com/powerball/results');
    // // $driver = $driver->get('https://sports.sina.com.cn/l/kaijiang/detail.shtml?game=203');
    // // $driver = $driver->get('https://bet.hkjc.com/marksix/Results.aspx?lang=en');
    // //$driver = $driver->get('https://www.lotterypost.com/results/wi/badger5/past');
    // / $driver = $driver->get('https://yesplay.bet/lucky-numbers/colombia_baloto/results');
    // / $driver = $driver->get('https://lotteryguru.com/belgium-lottery-results/be-lotto/be-lotto-results-history');
    // // $driver = $driver->get('https://yesplay.bet/lucky-numbers/spain_daily_6_from_49/results');
    // // $driver = $driver->get('https://www.lotterypost.com/results/md/bonusmatch5/past');
    // // $driver = $driver->get('https://www.lotterypost.com/results/ac/bucko/past');
    // // $driver = $driver->get('https://yesplay.bet/lucky-numbers/usa_north_carolina_cash_5/results');
    // // $driver = $driver->get('https://www.coloradolottery.com/en/games/cash5/drawings/');
    // $driver = $driver->get('https://www.taiwanlottery.com/lotto/result/bingo_bingo?searchData=true');
    // $wait = new WebDriverWait($driver, 10);
    // $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('result-area')));
    // $parent_elements = $parent_element->findElements(WebDriverBy::cssSelector('.result-item'));
    // $res = fetch_one('taiwan_bingo_1kb');
    // print_r($res);
    // $final_res = [];
    // foreach ($parent_elements as $parent_element){
    //     $issue_number   = $parent_element->findElement(WebDriverBy::cssSelector('.result-item-simple-area-period  > .period-title'));
    //     $issue_number = implode(array_slice(str_split($issue_number->getText()),3,9));
    //     $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('.result-item-simple-area-ball-container > .ball'));
    //     $draw_number = [];

    //     foreach ($draw_number_parent as $key => $val) {
    //       $draw_number[] = $val->getText();
    //     }
    //     $draw_number = implode(',',$draw_number);
    //    // if($res['data']['draw_number'] === $draw_number){
    //        $final_res[] = ['draw_count' => $issue_number, 'draw_numbers'=> $draw_number];
    //     // }  
       
    // }
  
    //      $driver->quit();
    //      echo json_encode(['data' => $final_res]);
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
  //  $res = get_time_in_right_zone("Europe/Berlin")['full_date'];
  //  $separated_date =  explode('-',explode(" ",$res)[0]);
  //  $res = implode('-',array_slice($separated_date,0,3));
  //  $new_date_time = new DateTime($res);
  //   $date = $new_date_time->format('F jS, Y');
  //   $week_day = strtoupper($separated_date[3]);
  //   echo "Remote week day is {$remote_week_day} and remote date is {$remote_date_of_fetch} \n";
  //   echo "Local week day is {$week_day} and remote date is {$date} \n";
  //   foreach ($draw_number_parent as $val) {
     
  //     $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
  //   }
  //  $final_res[] = ['draw_date' => $remote_date_of_fetch, 'draw_day' => $remote_week_day,'draw_count' => '', 'draw_number'=> implode(',',$draw_number)];
  // }

    //  $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultsnums')));
    // $draw_number_parent = $parent_element->findElements(WebDriverBy::cssSelector('li'));
    // $draw_number = [];
    // foreach ($draw_number_parent as $val) {
    //   $draw_number[] = !is_numeric($val->getText()) ? substr($val->getText(),0,1) : $val->getText();
    // }
   

    //return ['draw_count' => substr($issue_number,-4), 'draw_number'=> implode(',',$draw_number)];
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
      // $rr = ['m' => 'failed_lotteries', 'n' => 'failed_lot'];
      // unset($rr['m']);
      // print_r($rr);
      // $start_end = '07:05~23:55';
      // $timezone  = 'Asia/Taipei';
      // $start_end = explode('~', $start_end);
      // $current_time = new DateTime();
      // $current_time = $current_time->format('H:i');
      // $start_time = convert_time_to_other_timezone($start_end[0], $timezone);
      // $end_time   = convert_time_to_other_timezone($start_end[1], $timezone);
      // $start_time = explode(' ',$start_time['shortend_date'])[1];
      // $end_time   = explode(' ',$end_time['shortend_date'])[1];
   
      // if($start_time >= $current_time && $end_time <= $current_time){
      //   echo "Current time  is between $start_time and $end_time";
      // }
      
 
      // $res = fetch_one('');

      $m = ['m','n','t','d','e'];
      $n = ['n','t','d','e','s','u'];
      print_r(array_diff($n,$m));
      