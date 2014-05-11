<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResponseLayout extends SpotOnTerminalGet {

    protected function execute()
    {
        $storyId = $this->input->get('story_id');
//        $uuid = "6ae86830dc3cc15c";
        if ($storyId)
	{
            $this->getLayoutToXml($storyId);
	}
    }
    
    private function getLayoutToXml($storyId)
    {
        $storyId = "'".  str_replace(",", "', '", $storyId)."'";
        $storylist = $this->m->selectWhereIn("mst_story", array("story_ID" => $storyId));
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