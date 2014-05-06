<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Programmer extends SpotOnSubServer {

    public function index()
    {
//        $ch = curl_init();
//
//        // set URL and other appropriate options
        $url = base_url("getlayout?uuid=6ae86830dc3cc15c");
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//
//        // grab URL and pass it to the browser
//        curl_exec($ch);
//
//        // close cURL resource, and free up system resources
//        curl_close($ch);
        
        $sXML = $this->getDataFromUrl($url);
        $oXML = new SimpleXMLElement($sXML);

        foreach($oXML->entry as $oEntry){
                echo $oEntry->title . "\n";
        }
        
        $xmlArr = array(); 
        $this->convertXmlObjToArr($oXML, $xmlArr);
        var_dump( json_encode($xmlArr));
    }
}
