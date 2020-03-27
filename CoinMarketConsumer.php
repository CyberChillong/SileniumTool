<?php

namespace Facebook\WebDriver;



use Facebook\WebDriver\Exception\ElementNotInteractableException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');


class CoinMarketConsumer{
    const HOST_URL_SET_UP = "http://localhost:4444/wd/hub";
    const URL_TO_CONSUME = "https://coinmarketcap.com/all/views/all/";
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

    public function loadAllCoinsFromWebSite(){
        $this->acceptCookiesPolicy();
      do{
          $oContainerOfLoadButton = $this->oDriver->findElements(WebDriverBy::className("cmc-table-listing__loadmore"));
          $oContainerOfLoadButton[0]->findElement(WebDriverBy::tagName("button"))->click();

      }while($this->oDriver->findElements(WebDriverBy::className("cmc-table-listing__loadmore"))!=null);


    }//loadAllCoinsFromWebSite

    public function acceptCookiesPolicy(){
        $aCookiesBanner = $this->oDriver->findElements(WebDriverBy::id("cmc-cookie-policy-banner"));
        foreach ( $aCookiesBanner as $aCookiesBannerElements){
            $aCookiesBannerElements->findElement(WebDriverBy::className("cmc-cookie-policy-banner__close"))->click();

        }//foreach

    }//acceptCookiesPolicy


    public function extractAllTokensInformation():array{
        $aTokensInformation [][]= array() ;
        $this->loadAllCoinsFromWebSite();
        $aTokensHTMLTable = $this->oDriver->findElements(WebDriverBy::tagName('table'));
        foreach($aTokensHTMLTable as $aTokensTableLines){
            $idx=0;
            foreach ($aTokensTableLines->findElements(WebDriverBy::tagName('tr')) as $aTokensTableCells ){
                $index =0;

                foreach ($aTokensTableCells->findElements(WebDriverBy::tagName('td')) as $TokensTableCell){

                    if($TokensTableCell->getText()!=null){
                        $aTokensInformation[$idx][$index]= $TokensTableCell->getText();
                        $index++;
                    }//if

                }//foreach
                $idx++;
            }//foreach

        }//foreach

        return $aTokensInformation;

    }//extractAllTokensInformation












}//CoinMarketConsumer