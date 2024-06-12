<?php

require_once('vendor/autoload.php');
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

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
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    $driver = RemoteWebDriver::create($host, $capabilities);
    return $driver->get($url);
    
}

function get_hong_kong_mark6($lottery_url){
    $driver = get_driver($lottery_url);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_count = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell1 > a'));
    echo $draw_count = $draw_count->getText();
    $draw_date = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell2'));
    echo $draw_date = $draw_date->getText();
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
    return ['drawid'=> $draw_count,'draw_count' => explode('/',$draw_count)[1], 'draw_date'=> $draw_date, 'draw_number'=> $draw_numbers];
    // $wait = new WebDriverWait($driver, 10);
    // $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    // $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    // $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    // $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    // $draw_numbers = [];
    // foreach($draw_numbers_parent as $draw_numbers_element){
    //        $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
    //        if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    // }
    // $driver->quit();
    // return json_encode($draw_numbers);
}
function get_belgium_3d($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_china_3d($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_china_happy8($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_china_7_stars($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_china_p5($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_taiwan_3d($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_taiwan_4d($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_taiwan_lotto($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}
function get_taiwan_bingo($lottery_name){
    global $lottery_urls;
    $driver = get_driver($lottery_urls[$lottery_name]);
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    return json_encode($draw_numbers);
}

