<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetVersion extends SpotOnSubServer {

    public function index()
    {
//        $uuid = $this->input->get('uuid');
        $uuid = "6ae86830dc3cc15c";
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
            $this->xml_writer->startBranch('story', $checkSum[0]);
            $this->xml_writer->endBranch();
        }
        $this->output();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */