<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SpotOnSubServer extends CI_Controller {
    
    private $outputXml = array();
    private $mapId = array(
                            "display" => "dsp_ID"
                            ,"layout" => "lyt_ID"
                            ,"story" => "story_ID"
                            ,"media" => "media_ID"
                            ,"playlist" => "pl_ID"
                            ,"scheduling" => "sch_ID"
                            ,"deployment" => "dpm_ID"
                            ,"player" => "tmn_ID"
                        );

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
        
//        $this->load->library('session');
        $this->load->model('my_model', 'my_model');
        $this->load->model('spot_on_model', 'm');
        
        $this->xml_writer->setRootName('signage');
        $this->xml_writer->initiate(array("width" => "1920", "height" => "1080"));
    }

    /* --------------- start create output ----------------- */
    protected function output() {
        $data = array();
        $data['xml'] = $this->xml_writer->getXml(FALSE);
        $this->load->view('xml', $data);
    }
    /* -------------- End create output --------------- */
    
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

    
}