<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GetLayout extends SpotOnTerminalGet {

    public function execute()
    {
        $uuid = $this->input->get('uuid');
//        $uuid = "6ae86830dc3cc15c";
        if ($uuid)
	{
            $this->getLayoutToXml($uuid);
	}
    }
    
    private function getLayoutToXml($uuid)
    {
        $storylist = $this->m->selectStoryByUUID($uuid);
        foreach ($storylist as $story) {
            // Start branch 'cars'
            $this->xml_writer->startBranch('story', $story);
            $storyId = $story->story_ID;
            $dsplist = $this->m->selectDisplayByStoryId($storyId);
            foreach ($dsplist as $dsp){
                $dspId = $dsp->dsp_ID;
                $this->xml_writer->startBranch('display', $dsp);
                $medialist = $this->m->selectMediaByStoryIdAndDspId($storyId, $dspId);
                foreach ($medialist as $media){
                    $this->xml_writer->startBranch('media', $media);
                    $this->xml_writer->endBranch();
                }
                $this->xml_writer->endBranch();
            }
            $this->xml_writer->endBranch();
        }
        
//        $this->output();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */