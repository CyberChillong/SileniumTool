<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');
require_once "CoinMarketConsumer.php";
require_once "SteemEngineConsumer.php";



$host = 'http://localhost:4444/wd/hub';


$capabilities =DesiredCapabilities::firefox();

// Disable accepting SSL certificates
$capabilities->setCapability('acceptSslCerts', true);

// Run headless firefox
$capabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);




    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver->get('https://steem-engine.com/?p=tokens');
    //$driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName("tbody")));
   $elements = $driver->findElements(WebDriverBy::tagName("tbody"));
    echo empty($elements);
    $strTable = "<body><table>";
    foreach ($elements as $subElements) {
        foreach ($subElements->findElements(WebDriverBy::tagName("tr")) as $element) {
            $strTable .= "<tr>";
            foreach ($element->findElements(WebDriverBy::tagName("td")) as $finalResult) {
                $strTable .= "<td>" . $finalResult->getText() . "</td>";
            }
            $strTable .= "<tr>";
        }

    }
    $strTable .= "</table></body>";
    $oFile = fopen("SteemEngineTokens.html" , "w");
    fwrite($oFile,$strTable);
    fclose($oFile);

/*
$Se = new SteemEngineConsumer();
//$Cm = new CoinMarketConsumer();
//$O->loadAllCoinsFromWebSite();
//$O->acceptCookiesPolicy();
var_dump( $Se->extractAllTokensInformation());

//var_dump($Cm->extractAllTokensInformation());
*/