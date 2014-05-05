<?php

class StringUtils {
    
    public static function isBlankOrNull($str){
        $null = is_null($str) ;
        $string = is_string($str) ;
        $blank =  $str === "" ;
        return ($null || ($string && $blank));
    }
    
    public static function isStringNotBlankOrNull($str){
        return (is_string($str) && $str !== "" && !is_null($str));
    }
    
    public static function subStringAndGetByIndex($str, $delimeter, $index){
         $mediaType = explode($delimeter, $str);
         return $mediaType[$index];
    }
    
    //แปลง arrayToXml ใช้ในหน้า view/xml.php
    public static function arrayToXml($input){
        $ret = "";
            // จะมีสองกรณีคือถ้าเป็น array จะถือว่าเป็น element 
            // Ex <display id="123" attr="asdf"></display>  
            if(is_array($input)){
                //check ก่อนว่า มี index id, name, attr หรือไม่ 
                if(array_key_exists("id", $input) 
                        && array_key_exists("name", $input)  
                        && array_key_exists("attr", $input) ){
                    //ถ้ามี จะมองว่าเป็น single Element
                    $id = $input["id"];
                    $name = $input["name"];
                    $attr = $input["attr"];

                    $ret .= "<$name id='$id' ";
                    // ดึง attribute (recursive)
                    $ret .= self::arrayToXml($attr) . " >";
                    
                    //เช็คดูว่ามี subElement หรือไม่ ถ้าไม่มีก็จบ
                    if(isset($input["data"])){
                        $data = $input["data"];
                        //ถ้ามี เช็คต่อว่า หรือเป็น string 
                        if(is_array($data) && count($data) > 0){
                            //ถ้าเป็น array ให้ไป gen subElement ออกมา (recursive)
                            $ret .= self::arrayToXml($data);
                        } else if(is_string($data)){
                            //ถ้าเป็น String ให้ต่อ String ไปเลย
                            $ret .= "$data";
                        }
                    }
                    $ret .= "</$name>";
                } else {
                    //ถ้าไม่มี จะมองว่าเป็น mulitiple Element
                    foreach ($input as $value) {
                        //วน loop gen ทีละ element (recursive)
                        $ret .= self::arrayToXml($value);
                    }
                }
                
            } else if(is_object($input)){
                // ถ้าเป็น Object จะถือว่าเป็น attribute 
                // Ex dsp_ID="1" dsp_name="display_name"
                foreach ($input as $index => $value) {
                    $ret .= $index . '="' . $input->$index . '" ';
                }
            }
            return $ret;
    }
    
}

