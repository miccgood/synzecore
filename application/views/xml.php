

<signage width="1920" height= "1080">
    <?php 
    
//        $data = array();
        foreach ($data as $key => $value) {
            echo getElementXml($value);
        }
        
        
        function getElementXml($array = array()) {
            $ret = "";
            foreach ($array as $key => $value) {
                $ret .= "<$value";
                $element = "";
                if(is_array($value)){
                    $element = getElementXml($value);
                } else if(is_object($value)){
                    foreach ($value as $index) {
                        $element .= $value->$index . " ";
                    }
                }
                $ret .= $element . ">";
            }
            return $ret;
        }
    ?>
    
</signage>
    