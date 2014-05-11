<?php


class MY_Model extends CI_Model{
    private $sqlMapName = array();
    
    function __construct() {
        $this->config->load('sql', TRUE);
 
        // get all config values
        $this->sqlMapName = $this->config->item('sql');
 
    }


    protected function createNameQuery($name, $binds = NULL){
        return $this->process_bind($this->sqlMapName[$name], $binds);
    }

    protected function process_bind($sql, $bind){
        if($bind === NULL){
            return $sql;
        }
        $bindOrder = null;
        $bindList = null;

        $pattern = "/[^']:[A-Za-z0-9_]+[^']/";
        $preg = preg_match_all($pattern, $sql, $matches, PREG_OFFSET_CAPTURE);
        if($preg !== 0 and $preg !== false){
            foreach($matches[0] as $key=>$val){
                $bindOrder[$key] = trim($val[0]);
            }
            foreach($bindOrder as $field){
                if(array_key_exists($field, $bind)){
                    $bind_marker = $bind[$field];
                    if(is_string($bind_marker)){
                        $bind_marker = $this->db->escape($bind_marker);
                    } else {
                        $bind_marker = "'".join("', '", $bind_marker)."'";
                    }
                    $sql = str_replace($field, $bind_marker, $sql);
                    $bindList[] = $bind[$field];
                } else {
                    show_error("SpotOnModel Line : ".__LINE__." <br/> Error Field [$field] Not In Config ");
                }
            }
        }else{
            $bindList = $bind;
        }

        return $sql;
    }

}