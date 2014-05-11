<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class requestVersion extends SpotOnTerminalRequest {

    public function index() {
        
//        $url = $this->parentServer."getversion/json?uuid=6ae86830dc3cc15c";
        $url = "http://localhost/synzecore/responseversion/json?uuid=6ae86830dc3cc15c,b11c94f457bebd84,eccb55feb3b20bd1,48e7b737efcd0b2e,72e4fe3c46e85726,33d7f88e467d62eb";
        // ยิง request ไป server เอาข้อมูลมา
        $sXml = $this->getDataFromUrl($url);
        
        
        $array = json_decode($sXml, true);
        
        $changeVersion = array();
        $isCheck = array();
        $signage = $array["signage"];
        foreach ($signage["story"] as $key => $value) {
            $attributes = ($key === "@attributes" ? $value : $value["@attributes"]);

            // ดึงข้อมูลที่ต้องการออกมาก่อน
            $tmnUUID = $attributes["tmn_uuid"];
            $checkSumServer=  $attributes["check_sum"];
            $storyId = $attributes["story_id"];
            
            //ถ้ามีใน array ชุดนี้แล้วแปลว่าตรวจสอบแล้วให้ข้ามไป
            if(array_key_exists($storyId, $isCheck)){
                continue;
            }
            
            $isCheck[$storyId] = $checkSumServer;
            
            var_dump($attributes);
//            array_push($isCheck, $storyId);
            
            // ดึง story ออกมาจาก local 
            $storylist = $this->m->selectStoryByUUID($tmnUUID);
            
            foreach ($storylist as $story) {
                
                $checkSumLocal = $this->selectDisplayAndMediaByStoryIdForCheckSum($story->story_ID);
            
                
                //ถ้าไม่เท่ากัน แปลว่ามีการเปลี่ยนแปลง
                if($checkSumServer != $checkSumLocal){
                    $changeVersion[$tmnUUID] = $checkSumLocal;
                }
            }
            
            
            
        }
        
        
        
        
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */