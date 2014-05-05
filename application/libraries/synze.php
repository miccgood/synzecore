<?php

//	require_once 'connectdb.php';
//
//	$DBH = new PDO("mysql:host=$db_host;dbname=$db_database", $db_username, $db_password);
//	$STH = $DBH->prepare("SET time_zone='+07:00';");
//	$STH->execute();

class Synze {

    static private $mapDbField = array('c_Media_ID' => 'media_ID',
        'c_Media' => 'media_name',
        'c_StartTime' => 'start_time',
        'c_StopTime' => 'stop_time',
        'c_Duration' => 'duration',
        'c_Pl_ID' => 'pl_ID',
        'c_Playlist' => 'pl_name',
        'c_Display_ID' => 'dsp_ID',
        'c_Zone' => 'dsp_name',
        'c_Shd_ID' => 'shd_ID',
        'c_Shd_name' => 'shd_name',
        'c_Lyt_ID' => 'lyt_ID',
        'c_Dpm_ID' => 'dpm_ID',
        'c_Story' => 'story_name',
        'c_StoryID' => 'story_ID');

    public static function toDBField($key) {
        return ( array_key_exists($key, self::$mapDbField) ? self::$mapDbField[$key] : $key);
    }

    public static function createLogItem($itemId, $itemName, $itemType, $cpnId) {
        return array('item_ID' => $itemId,
            'item_name' => $itemName,
            'item_type' => $itemType,
            'cpn_ID' => $cpnId);
    }

}