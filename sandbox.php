<?php

require_once('vendor/autoload.php');
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

$host = 'http://localhost:4444'; // URL of the Selenium server

  // Set up Chrome options to enable headless mode
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--disable-gpu', '--window-size=1280,1024']);
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver = $driver->get('https://bet.hkjc.com/marksix/Results.aspx?lang=en');
    $wait = new WebDriverWait($driver, 10);
    $parent_element = $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('resultMainTable')));
    $first_child_element = $parent_element->findElement(WebDriverBy::cssSelector(':first-child'));
    $draw_count = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell1 > a'));
    echo $draw_count = $draw_count->getText()."\n";
    $draw_date = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell2'));
    echo $draw_date = $draw_date->getText()."\n";
    $draw_numbers_parent = $first_child_element->findElement(WebDriverBy::cssSelector('.resultMainCell4'));
    $draw_numbers_parent = $draw_numbers_parent->findElements(WebDriverBy::cssSelector('.resultMainCellInner > img'));
    $draw_numbers = [];
    foreach($draw_numbers_parent as $draw_numbers_element){
           $value = explode('_',$draw_numbers_element->getAttribute('src'))[1];
           if(intval($value) > 0 && strlen($value) <= 2) $draw_numbers[] = intval($value);
    }
    $driver->quit();
    echo json_encode($draw_numbers);