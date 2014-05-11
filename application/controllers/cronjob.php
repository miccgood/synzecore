<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CronJob extends SpotOnTerminalCronJob {
    
    public function __construct() {
        parent::__construct();
    }
    
//    protected function execute() {
//        
//    }
    
    protected function test() {
        // step 1 ตรวจสอบก่อนว่า incedent_per_day ข้ามวันแล้วรึยัง 
        //        ถ้าข้ามวันแล้ว ให้ incedent_per_month += incedent_per_day
        //          incedent_per_day = 0;
        
        // step 2 ตรวจสอบว่า player ตัวไหนขาดการติดต่อเกิน 10 วินาที 
        // ถ้าขาดการติดต่อเกิน 10 วินาที ให้ update tmn_status_update = NOW()
        // incedent_per_day++ 
        echo $this->m->cronJobUpdateStatus();
        
        // step 3 ดึงข้อมูลจาก Parent Server 
        $this->m->requestDataFromParentServer();
        
        
    }
    
    
    
    public function requestDataFromParentServer() {
        
//        $url = $this->parentServer."getversion/json?uuid=6ae86830dc3cc15c";
        $url = "http://localhost/synzecore/responseversion/json?uuid=6ae86830dc3cc15c,b11c94f457bebd84,eccb55feb3b20bd1,48e7b737efcd0b2e,72e4fe3c46e85726,33d7f88e467d62eb";
        $sXml = $this->getDataFromUrl($url);
        
        
        $array = json_decode($sXml, true);
        
        $changeVersion = array();
        $isCheck = array();
        $signage = $array["signage"];
        foreach ($signage["story"] as $key => $value) {
            $attributes = ($key === "@attributes" ? $value : $value["@attributes"]);

            $tmnUUID = $attributes["tmn_uuid"];
            $checkSumNew = $attributes["check_sum"];
            $storyId = $attributes["story_id"];
            
            //ถ้ามีใน array ชุดนี้แล้วแปลว่าตรวจสอบแล้วให้ข้ามไป
            if(array_key_exists($storyId, $isCheck)){
                continue;
            }
            
            $isCheck[$storyId] = $checkSumNew;
            
            var_dump($attributes);
//            array_push($isCheck, $storyId);
            
            $checkSumLocal = $this->selectDisplayAndMediaByStoryIdForCheckSum($storyId);
            
            //ถ้าไม่เท่ากัน แปลว่ามีการเปลี่ยนแปลง
            if($checkSumNew != $checkSumLocal){
                $changeVersion[$storyId] = $checkSumLocal;
            }
            
            
        }
        
        
        
        
    }
    
    
}
