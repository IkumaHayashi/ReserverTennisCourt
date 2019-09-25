<?php
require_once __DIR__ . '/vendor/autoload.php';
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;

putenv('webdriver.chrome.driver=/bin/chromedriver');

$options = new ChromeOptions();
//$options->addArguments(['--headless']);
$options->addArguments(['--no-sandbox']);

$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);
$driver = ChromeDriver::start($caps);

$driver->get('http://www.yahoo.co.jp/');
$driver->takeScreenshot('./screenshot.png');

$driver->close();