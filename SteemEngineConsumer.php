<?php

namespace Facebook\WebDriver;




use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

class SteemEngineConsumer {
    const HOST_URL_SET_UP = "http://localhost:4444/wd/hub";
    const URL_TO_CONSUME = "https://steem-engine.com/?p=tokens";
    private $oDriver;
    private $oBrowserCapabilities;

    public function __construct()
    {
        $this->oBrowserCapabilities = DesiredCapabilities::firefox();
        $this->oBrowserCapabilities->setCapability('acceptSslCerts', false);
        $this->oBrowserCapabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);
        $this->oDriver = RemoteWebDriver::create(self::HOST_URL_SET_UP, $this->oBrowserCapabilities);
        $this->oDriver->get(self::URL_TO_CONSUME);
        }//__construct



    public function stopWebDriver(){

    }//closeWebDriver;




    public function extractAllTokensInformation():array{
        $aTokensInformation=array();
        $this->oDriver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName("tbody")));
        echo("presente");
        $aTokensHTMLTable = $this->oDriver->findElements(WebDriverBy::tagName('tbody'));
        $index =0;
        $idx=0;
        /*while(empty($aTokensHTMLTable)){
            echo "bateu";
            $this->oDriver->navigate()->refresh();
            $aTokensHTMLTable = $this->oDriver->findElements(WebDriverBy::tagName('tbody'));
        }*/

        foreach($aTokensHTMLTable as $aTokensTableLines){
            foreach ($aTokensTableLines->findElements(WebDriverBy::tagName('tr')) as $aTokensTableCells ){
                $aTokensInformation[$idx]=array();
                foreach ($aTokensTableCells->findElements(WebDriverBy::tagName('td')) as $TokensTableCell){
                    $aTokensInformation[$idx][$index]= $TokensTableCell->getText();
                        $index++;
                }//foreach
                $idx++;
            }//foreach
        }//foreach

        return $aTokensInformation;

    }//extractAllTokensInformation


}//SteemEngineConsumer