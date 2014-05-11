<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResponseVersion extends SpotOnTerminalGet {

    protected function execute()
    {
        $uuid = $this->input->get('uuid');
//        $uuid = "6ae86830dc3cc15c";
        if ($uuid)
	{
            $this->getVersionToXml($uuid);
	}
    }
    
    private function getVersionToXml($uuid)
    {
        $storylist = $this->m->selectStoryByUUID($uuid);
        
        foreach ($storylist as $story) {
            // Start branch 'cars'
            $storyId = $story->story_ID;
            $checkSum = $this->m->selectDisplayAndMediaByStoryIdForCheckSum($storyId);
            $tmn_uuid = array("tmn_uuid" => $story->tmn_uuid, "story_id" => $storyId);
            $this->xml_writer->startBranch('story', array_merge($tmn_uuid, $checkSum[0]));
            $this->xml_writer->endBranch();
        }
//        $this->output();
    }

//    protected function execute() {
//        
//    }

}