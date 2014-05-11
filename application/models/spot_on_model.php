<?php

class spot_on_model extends MY_Model  {
        
    public function __construct() {
        parent::__construct();
    }    
    
    public function select($table, $where){
        return $this->db->where($where)->get($table)->result_array();
    }
    
    public function selectWhereIn($table, $where){
        return $this->db->get($table)->result_array();
    }
    
    public function getDisPlay() {
        return $this->db->limit(1)->get('mst_dsp')->result();
    }
    
    // --- Start getLayout --- //
    public function selectStoryByUUID($uuid){
        $uuid = explode(",", $uuid);
        $bind = array(":uuid" => $uuid);
        $sql = $this->createNameQuery("selectStoryByUUID", $bind);
        return $this->db->query($sql)->result();  

    }
    
    public function selectDisplayByStoryId($storyId){
        $bind = array(":story_ID" => $storyId);
        $sql = $this->createNameQuery("selectDisplayByStoryId", $bind);
        return $this->db->query($sql)->result();  

    }
    
    public function selectMediaByStoryIdAndDspId($storyId, $dspId){
        $bind = array(":story_ID" => $storyId, ":dsp_ID" => $dspId);
        $sql = $this->createNameQuery("selectMediaByStoryIdAndDspId", $bind);
        return $this->db->query($sql)->result();  
    }
    
    
    public function selectDisplayAndMediaByStoryIdForCheckSum($storyId){
        $bind = array(":story_ID" => $storyId);
        $sql = $this->createNameQuery("selectDisplayAndMediaByStoryIdForCheckSum", $bind);
        return $this->db->query($sql)->result_array();  
    }
    
    // --- End getLayout --- //
    
    // --- Start addLog --- //
    public function selectTmnByUUID($uuid){
        $bind = array(":uuid" => $uuid);
        $sql = $this->createNameQuery("selectTmnByUUID", $bind);
        return $this->db->query($sql)->result();  

    }
    
    public function insertIgnoreLogItem($logItem){
        $insert_string = $this->db->insert_string('log_item', $logItem);
        $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_string);
        $this->db->query($insert_query);
    }
    
    public function insertLog($log){
        $this->db->insert("log", $log);
    }
    
    // --- End addLog --- //
    
    
    // --- Start Update Status & Update Status Upload --- //
    
    public function updatePlayerByPlayerId($player, $where){
        return $this->db->update("mst_tmn", $player, $where);
    }
    
    // --- End Update Status --- //
    
    
    // --- Start Cron Job --- //
    
    public function cronJobUpdateStatus(){
        $sql = $this->createNameQuery("cronJobUpdateStatus");
        return $this->db->query($sql); 
    }
     
    // --- End Cron Job --- //
}
