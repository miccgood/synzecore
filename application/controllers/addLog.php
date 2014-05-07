<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AddLog extends SpotOnTerminalAdd {

    protected function execute() {
        $uuid = $this->input->post('uuid');
        $data = $this->input->post('data');
//        $uuid = "6ae86830dc3cc15c";
//        $data = '[{"media_ID":"7","stop_time":"2014-04-21 17:16:26","shd_name":"POV","story_ID":"6","dsp_ID":"5","duration":"15125","story_name":"POV-image","dsp_name":"Video","shd_ID":"","pl_name":"POV_video","pl_ID":"6","start_time":"2014-04-21 17:16:11","media_name":"8bc3a-biore_facial_foam_spot_2d_15sec.mp4","dpm_ID":"26","lyt_name":"POV screen","lyt_ID":"3"}]';

        return ($uuid && $data ? $this->addLogAndLogItemByUUID($uuid, $data) : false);
    }

    private function addLogAndLogItemByUUID($uuid, $data) {
        $tmnList = $this->m->selectTmnByUUID($uuid);
        foreach ($tmnList as $tmn) {
            $tmnGrpId = $tmn->tmn_grp_ID;
            $tmnGrpName = $tmn->tmn_grp_name;
            $tmnId = $tmn->tmn_ID;
            $tmnName = $tmn->tmn_name;
            $cpnId = $tmn->cpn_ID;

            $tmnLogItem = Synze::createLogItem($tmnId, $tmnName, 'tmn', $cpnId);

            $this->m->insertIgnoreLogItem($tmnLogItem);


            $tmnGrpLogItem = Synze::createLogItem($tmnGrpId, $tmnGrpName, 'tmn', $cpnId);

            $this->m->insertIgnoreLogItem($tmnGrpLogItem);

            $arr = json_decode($data);

            $count = 0;
            try {
                foreach ($arr as $row) {
                    $log = array();

                    $log['tmn_grp_ID'] = $tmnGrpId;
                    $log['tmn_grp_name'] = $tmnGrpName;
                    $log['tmn_ID'] = $tmnId;
                    $log['tmn_name'] = $tmnName;

                    foreach ($row as $field => $value) {
                        if (Synze::toDBField($field) == 'start_time') {
                            $log['start_date'] = date('Y-m-d', strtotime($value));
                            $log['start_time'] = date('H:i:s', strtotime($value));
                            //echo 'start_time'.date('H:m:s', strtotime($value)).';';
                        } else if (Synze::toDBField($field) == 'stop_time') {
                            $log['stop_date'] = date('Y-m-d', strtotime($value));
                            $log['stop_time'] = date('H:i:s', strtotime($value));
                            //echo 'stop_time'.date('H:m:s', strtotime($value)).';';
                        } else {
                            $log[Synze::toDBField($field)] = $value;
                        }
                    }

                    foreach (array('media', 'pl', 'story', 'shd', 'dsp') as $field => $value) {
                        $logItem = Synze::createLogItem($log[$value . '_ID'], $log[$value . '_name'], $value, $cpnId);

                        $this->m->insertIgnoreLogItem($logItem);
                    }
                    $this->m->insertLog($log);
                    $count++;
                }
            } catch (Exception $exc) {
                return false;
            } 
            return $count;
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */