<?php

function check_login()
{
    $CI = get_instance();
    
    if ($CI->session->userdata("guardian_id") == null)
    {
	redirect("member/login");
    }
}

function check_guardianship($pupilid)
{
    $CI = get_instance();
    
    $guardianid = $CI->session->userdata("guardian_id");
    
    $CI->load->model("membership_model");
    
    $query = $CI->membership_model->check_guardianship($guardianid,$pupilid);
    
    if(!$query)
    {
	redirect("pupil/overview");
    }
}

function check_match($matchid)
{
    $CI = get_instance();
    
    $guardianid = $CI->session->userdata("guardian_id");
    
    $CI->load->model("block_model");
    
    $match = $CI->block_model->get_match($matchid);
    
    $pupilid = $match["pupil_id"];
    
    check_guardianship($pupilid);
}

?>