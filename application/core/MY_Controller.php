<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SpotOnTerminal extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');
        
        /* ------------------ */
        
        // Load XML writer library
        
        $this->load->library('synze');
        $this->load->library('xml_writer');
        $this->load->library('stringutils', FALSE);
        
        
        $this->config->load('stream', true);
        $this->stream = $this->config->item('stream');
        
//        $this->load->library('session');
        $this->load->model('my_model', 'my_model');
        $this->load->model('spot_on_model', 'm');
        
        $this->xml_writer->setRootName('signage');
        $this->xml_writer->initiate(array("width" => "1920", "height" => "1080"));
        
        
        
    }

    
    protected function convertXmlToArray($xml){
        $array = array();
        $tagName = $xml->getName();
        $array[$tagName] = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
        return $array;
        
//        return unserialize(serialize(json_decode(json_encode((array) $xml), 1)));;
    }
       
    protected function convertXmlToJson($xml){
        $array = array();
        $tagName = $xml->getName();
        $array[$tagName] = (array) $xml;
        return json_encode((array) $array);
        
//        return unserialize(serialize(json_decode(json_encode((array) $xml), 1)));;
    }
    
    protected function convertXmlObjToArr($obj, &$arr) 
    { 
        $children = $obj->children(); 
        foreach ($children as $elementName => $node) 
        { 
            $nextIdx = count($arr); 
            $arr[$nextIdx] = array(); 
            $arr[$nextIdx]['@name'] = strtolower((string)$elementName); 
            $arr[$nextIdx]['@attributes'] = array(); 
            $attributes = $node->attributes(); 
            foreach ($attributes as $attributeName => $attributeValue) 
            { 
                $attribName = strtolower(trim((string)$attributeName)); 
                $attribVal = trim((string)$attributeValue); 
                $arr[$nextIdx]['@attributes'][$attribName] = $attribVal; 
            } 
            $text = (string)$node; 
            $text = trim($text); 
            if (strlen($text) > 0) 
            { 
                $arr[$nextIdx]['@text'] = $text; 
            } 
            $arr[$nextIdx]['@children'] = array(); 
            $this->convertXmlObjToArr($node, $arr[$nextIdx]['@children']); 
        } 
        return; 
    }  
    
    protected function getDataFromUrl($path){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$path);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$retValue = curl_exec($ch);			 
	curl_close($ch);
	return $retValue;
    }
    
    
    
    
    protected function getPkFormReq($index) {
        $pk = $this->input->get($index);
        return ($pk != null ? null : $pk);
    }
    
    protected function nullToZero($param , $ret = "0") {
        return ($param === FALSE || $param === NULL || $param === "" ? $ret : $param);
    }
    
    protected function setDefaultValue($array, $mode = null) {
        
        $state = ($mode != null ? $mode : $this->crud->getState());
        
        switch ($state) {
            case "insert":
                $array["create_date"] = date("YmdHis", time());
                $array["create_by"] = $this->userId;
            case "update":
                $array["update_date"] = date("YmdHis", time());
                $array["update_by"] = $this->userId;
                break;
            default:
                
                break;
        }
        return $array;
    }
    
    protected function subString($string, $charFirst) {
        return substr($string, strpos($string, $charFirst));
    }
    
    public function getValueFromObj($obj, $attr){
        if($obj != null){
            if(property_exists($obj, $attr)){
                return $obj->$attr;
            }
            return NULL;
        }
        return NULL;
    }
    
    public function getValueFromArray($obj, $attr){
        if($obj != null){
            if(array_key_exists($attr, $obj)){
                return $obj[$attr];
            }
            return NULL;
        }
        return NULL;
    }
    
    protected function getStringFormDuration($sec){
        //3600;
//        $h = floor($sec / 3600);
//        $sec = $sec - $h * 3600
//        $m = floor($sec / 3600);
//        $h = $sec / 3600;
//        $h = $sec / 3600;
        $ret = "";
        foreach(array(3600=>':',60=>':',1=>'') as $p=>$suffix){

            if ($sec >= $p){

                $sec -= $d = $sec-$sec % $p;

                $temp = $d/$p;
                
                if(strlen($temp) === 0){
                    $temp = "00";
                }else if(strlen($temp) === 1){
                    $temp = "0". $temp;
                }
                $ret .= $temp."$suffix"; 

            } else {
                $ret .= "00"."$suffix";
            }

        }
        return $ret;
    }
    
    protected function getDurationFormString($string){
        //3600;
//        $h = floor($sec / 3600);
//        $sec = $sec - $h * 3600
//        $m = floor($sec / 3600);
//        $h = $sec / 3600;
//        $h = $sec / 3600;
//        $time = strtotime($string);
//        return $time;
//        if(strpos(":", $string) < 0){
//            return $string;
//        }
        $string = explode(":", $string );
        $count = count($string); 
        $ret = 0;
        if($count == 3){
            $ret = $string[0] * 3600 + $string[1] * 60 + $string[2];
        }else if($count == 2){
            $ret = $string[1] * 60 + $string[2];
        }else { 
            $ret = $string[0];
        }
        return $ret;
        
    }
    
    public function isReadonly(){
        return $this->isReadonly;
    }

    protected function getVarname(&$var)
    {
        $ret = '';
        $tmp = $var;
        $var = md5(uniqid(rand(), TRUE));

        $key = array_keys($GLOBALS);
        foreach ( $key as $k )
            if ( $GLOBALS[$k] === $var )
            {
                $ret = $k;
                break;
            }
        $var = $tmp;
        return $ret;
    }
    
    public function getMedia($url, $type, $fileName){
         
        $this->config->load('stream', true);
        $this->stream = $this->config->item('stream');
        
        $this->load->library('getid3/getid3');
        $getID3 = new getID3;
        
        $path = ($type === "scrolling text" ? $this->stream["text_path"] : $this->stream["media_path"]);
        
//        $root = $this->media['media_path'];
//        $fileObj = $files_to_upload[0];
//        $fileName = $root . $fileObj->name;
//        $url = "http://localhost/synze/assets/uploads/media/0093f-movie.mp4";

        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$retValue = curl_exec($ch);			 
	curl_close($ch);
        
        $pathFile = trim($path, "/")."/".$fileName;
        if(!$file = fopen($pathFile, 'wb')){
            echo 'Write File Fail!'."\n";
            return false;
        }
        else
        {
            fwrite($file, $retValue);
        }
        
        $this->fileInfo = $getID3->analyze($pathFile);
        fclose($file);
        return  true;
    }
}

class SpotOnTerminalAdd extends SpotOnTerminal{
    
    function __construct() {
        parent::__construct();
        
        
        
    }
    
    public function index(){
        return false;
    }
}

abstract class SpotOnTerminalGet extends SpotOnTerminal{
    
    
    public function index(){
        $this->xml();
    }
    
    public function xml() {
        $data = $this->execute();
        $this->outputXml($data);
    }
    
    public function json() {
        $data = $this->execute();
        $this->outputJson($data);
    }
    
    abstract protected function execute();

    /* --------------- start create output ----------------- */
   
    private function outputXml($data = FALSE) {
        header ("Content-Type:text/xml");
        //ไม่ส่งข้อมูลเข้ามา
        if($data === FALSE || $data === null){
            //ให้ดึงมาจาก xml_writer
            $data = array("dataOutput" => $this->xml_writer->getXml(FALSE));
        } else {
            //ถ้าส่งข้อมูลเข้ามา ให้เอาข้อมูลไปแสดงผล
            $data = array("dataOutput" => $data);
        }
        $this->load->view('output', $data);
    }
    
    private function outputJson($data = FALSE) {
        header('Content-Type: application/json');
        //ไม่ส่งข้อมูลเข้ามา
        if($data === FALSE || $data === null){
            //ให้ดึงมาจาก xml_writer
            $sXML = $this->xml_writer->getXml(FALSE);
            $oXML = new SimpleXMLElement($sXML);
            $data = array("dataOutput" => $this->convertXmlToJson($oXML));
        } else {
            //ถ้าส่งข้อมูลเข้ามา ให้เอาข้อมูลไปแสดงผล
            $data = array("dataOutput" => $data);
        }
        $this->load->view('output', $data);
    }
    
    /* -------------- End create output --------------- */
}


class SpotOnTerminalRequest extends SpotOnTerminal{
    
    protected $parentServer = "";
            
    function __construct() {
        parent::__construct();
        $this->config->load('server', true);
        $this->server = $this->config->item('server');
        
        $this->parentServer = $this->server["parent_server"];
        
    }
    
    public function index(){
        return false;
    }
}

class SpotOnTerminalCronJob extends SpotOnTerminal{
    
    protected $parentServer = "";
            
    function __construct() {
        parent::__construct();
        $this->config->load('server', true);
        $this->server = $this->config->item('server');
        
        $this->parentServer = $this->server["parent_server"];
        
    }
    
    public function index(){
        date_default_timezone_set('Asia/Bangkok');
        
        //setting 
        $interval = 10;// sec
        $totalTime = (1 * 60 * 5);//sec
        
        $now = time();
        $start    = new DateTime(date("YmdHis", $now));
        $end      = new DateTime(date("YmdHis", $now + $totalTime));
        $interval = new DateInterval('PT'.$interval.'S');
        $period   = new DatePeriod($start, $interval, $end);
        
        $timeInterval = $interval->s;
        foreach ($period as $dt)
        {
            // do something
//            ob_start();
            
            $this->execute($dt);
            
//            ob_flush();
            sleep($timeInterval);
        }
    }
    
    protected function execute($dt) {
        echo $dt->format('H:i:s') . "\n";
    }
}


