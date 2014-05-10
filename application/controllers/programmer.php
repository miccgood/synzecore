<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Programmer extends SpotOnTerminal {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('getid3/getid3');
    }
    public function index()
    {
        $url = "http://localhost/synze/assets/uploads/text/533aea8c05fda-test.txt";
        $type = "scrolling text";
        $fileName = "533aea8c05fda-test.txt";
        $this->getMedia($url, $type, $fileName);
    }
    
    public function testXml(){
        
//        $ch = curl_init();
//
//        // set URL and other appropriate options
        $url = base_url("getversion/xml?uuid=6ae86830dc3cc15c");
//        $url = "http://localhost/synze/assets/uploads/media/0093f-movie.mp4";
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//
//        // grab URL and pass it to the browser
//        curl_exec($ch);
//
//        // close cURL resource, and free up system resources
//        curl_close($ch);
        
        $sXML = $this->getXmlFromUrl($url);
        $oXML = new SimpleXMLElement($sXML);

//        foreach($oXML->entry as $oEntry){
//                echo $oEntry->title . "\n";
//        }
//        
//        $xmlArr = array(); 
////        $xmlArr = $this->xmlToArray($oXML);
//        $this->convertXmlObjToArr($oXML, $xmlArr);
//        var_dump($xmlArr);
        
        
//        var_dump( $this->convertXmlToArray($oXML));
        var_dump( $this->convertXmlToJson($oXML));
//        $this->output($sXML);
    }
    
    public function getMediat(){
        
//        $ch = curl_init();
//
//        // set URL and other appropriate options
//        $url = base_url("getlayout?uuid=6ae86830dc3cc15c");
        $url = "http://localhost/synze/assets/uploads/media/0093f-movie.mp4";
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, 0);


        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$retValue = curl_exec($ch);			 
	curl_close($ch);
//	$retValue;
        

//
//        // grab URL and pass it to the browser
//        $retValue = curl_exec($ch);
//
//        // close cURL resource, and free up system resources
//        curl_close($ch);
        
//        $html = $this->output->get_output();
        
//        $base=$_REQUEST['image'];
        $binary=base64_decode($retValue);
//        header('Content-Type: image/jpg; charset=utf-8');

        if(!$file = fopen('assets/media.mp4', 'wb')){

            echo 'Image upload Fail!'."\n";
            return;
        }
        else
        {

            fwrite($file, $retValue);
            fclose($file);

        }
    }
    
//    public function getMedia(){
//        $getID3 = new getID3;
////        $root = $this->media['media_path'];
////        $fileObj = $files_to_upload[0];
////        $fileName = $root . $fileObj->name;
//        $url = "http://localhost/synze/assets/uploads/media/0093f-movie.mp4";
//
//        $ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL,$url);
//	curl_setopt($ch, CURLOPT_FAILONERROR,1);
//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
//	$retValue = curl_exec($ch);			 
//	curl_close($ch);
//        
//        if(!$file = fopen('assets/temp/tempfile.temp', 'wb')){
//
//            echo 'Image upload Fail!'."\n";
//            return;
//        }
//        else
//        {
//
//            fwrite($file, $retValue);
////            fclose($file);
//
//        }
//        
//        $this->fileInfo = $getID3->analyze('assets/temp/tempfile.temp');
//        fclose($file);
//    }
}
