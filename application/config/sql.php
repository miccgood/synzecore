<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// --- Start getlayout --- // 
//select_story_by_uuid
$config["selectStoryByUUID"] = 
        "SELECT shd.story_ID
                ,story.story_name
                ,story.lyt_ID
                ,lyt.lyt_name
                ,shd.shd_ID
                ,shd.shd_name
                ,shd.shd_start_date
                ,shd.shd_stop_date
                ,shd.shd_start_time
                ,shd.shd_stop_time
                ,lyt.lyt_width
                ,lyt.lyt_height
                ,dpm.dpm_ID
                ,dpm.tmn_uuid
        FROM mst_shd shd
            INNER JOIN mst_story story on(shd.story_ID = story.story_ID)
            INNER JOIN mst_lyt lyt on(lyt.lyt_ID = story.lyt_ID)
            INNER JOIN (
                SELECT shd_ID, dpm_ID, tmn.tmn_uuid 
                FROM trn_dpm dpm
                INNER JOIN mst_tmn tmn on(dpm.tmn_ID = tmn.tmn_ID OR dpm.tmn_grp_ID = tmn.tmn_grp_ID)
                WHERE tmn.tmn_uuid IN ( :uuid )

            ) AS dpm on(dpm.shd_ID = shd.shd_ID)
        WHERE  
             CURDATE() BETWEEN shd.shd_start_date AND shd.shd_stop_date
        ORDER BY shd.shd_start_time
        
        ";





//select_display_by_story_ID
$config["selectDisplayByStoryId"] = 
        "SELECT dsp.dsp_ID
            ,dsp.dsp_name
            ,dsp.dsp_left
            ,dsp.dsp_top
            ,dsp.dsp_width
            ,dsp.dsp_height
            ,dsp.dsp_zindex
            ,pl.pl_ID
            ,pl.pl_name
        FROM mst_story story
        INNER JOIN mst_dsp dsp on(dsp.lyt_ID = story.lyt_ID)
        INNER JOIN trn_dsp_has_pl  dsp_has_pl on(dsp.dsp_ID = dsp_has_pl.dsp_ID AND dsp_has_pl.story_ID = story.story_ID)
        INNER JOIN mst_pl pl on(dsp_has_pl.pl_ID = pl.pl_ID)

        WHERE story.story_ID = :story_ID
        ";

//select_media_by_story_ID_and_dsp_ID
$config["selectMediaByStoryIdAndDspId"] = 
        "SELECT media.media_ID
	,media.media_type
	,media.media_name
	,media.media_path
	,media.media_filename
	,media.media_lenght
	,media.media_timeout
	,media.media_expire
	,media.media_checksum
FROM trn_dsp_has_pl dsp_has_pl
INNER JOIN trn_pl_has_media has_media on (dsp_has_pl.pl_ID = has_media.pl_ID)
INNER JOIN mst_media media on (has_media.media_ID = media.media_ID)
WHERE dsp_has_pl.story_ID = :story_ID
	AND dsp_has_pl.dsp_ID = :dsp_ID

ORDER BY has_media.seq_no


";

// --- End getlayout --- //



// --- Start getVersion --- //

$config["selectDisplayAndMediaByStoryIdForCheckSum"] = 
        "SELECT 
            MD5(GROUP_CONCAT(`dsp`.`dsp_ID`,`dsp`.`dsp_name`,`dsp`.`dsp_left`
                ,`dsp`.`dsp_top`,`dsp`.`dsp_width`,`dsp`.`dsp_height`
                ,`dsp`.`dsp_zindex`
                ,`pl`.`pl_ID`,`pl`.`pl_name`,`media`.`media_ID`
                ,`media`.`media_type`,`media`.`media_name`,`media`.`media_path`
                ,`media`.`media_filename`
                ,`media`.`media_lenght`
                ,`media`.`media_expire`
                ,`media`.`media_checksum`)) as check_sum 
        FROM mst_story story
            INNER JOIN mst_dsp dsp on(dsp.lyt_ID = story.lyt_ID)
            INNER JOIN trn_dsp_has_pl  dsp_has_pl on(dsp.dsp_ID = dsp_has_pl.dsp_ID AND dsp_has_pl.story_ID = story.story_ID)
            INNER JOIN mst_pl pl on(dsp_has_pl.pl_ID = pl.pl_ID)
            INNER JOIN trn_pl_has_media has_media on (dsp_has_pl.pl_ID = has_media.pl_ID)
            INNER JOIN mst_media media on (has_media.media_ID = media.media_ID)
        WHERE 
            story.story_ID = :story_ID
        ";

// --- End getVersion --- //

// --- Start addLog --- //

//select_tmn_by_uuid
$config["selectTmnByUUID"] = "
    SELECT 
        tmn_grp.tmn_grp_ID,
        tmn_grp.tmn_grp_name,
        tmn.tmn_ID,
        tmn.tmn_name,
        tmn.cpn_ID
    FROM mst_tmn_grp tmn_grp
    INNER JOIN mst_tmn tmn ON(tmn_grp.tmn_grp_ID = tmn.tmn_grp_ID)
    WHERE
        tmn.tmn_UUID = :uuid 
        ";
        
// --- End addLog ---//




$config["cronJobUpdateStatus"] = "update mst_tmn set `tmn_status_update` = NOW() WHERE ABS(TIME_TO_SEC(TIMEDIFF(`tmn_status_update`, NOW() ))) > 10";

$config["cronJobUpdateUploadStatus"] = "update mst_tmn set `tmn_status_upload_update` = NOW() WHERE ABS(TIME_TO_SEC(TIMEDIFF(`tmn_status_upload_update`, NOW() ))) > 10";