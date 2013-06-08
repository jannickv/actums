<?php

class Pupil_model extends CI_Model
{

    function __construct()
    {
	parent::__construct();
    }

    function get_pupil($pupilid)
    {

	$query = $this->db->get_where("pupils", array("id" => $pupilid));

	return $query->row_array();
    }

    function create_pupil()
    {
	$data = array(
	    "firstname" => $this->input->post("firstname"),
	    "lastname" => $this->input->post("lastname"),
	    "birthdate" => ($this->input->post("birthdate") != "" ? date("Y-m-d", strtotime($this->input->post("birthdate"))): null),
	    "gsm" => $this->input->post("gsm"),
	    "email" => $this->input->post("email"),
	    "guardian_id" => $this->session->userdata("guardian_id"),
	);

	$query = $this->db->insert("pupils", $data);

	$pupilid = $this->db->insert_id();

	$data = array(
	    "guardians_id" => $this->session->userdata("guardian_id"),
	    "pupils_id" => $pupilid,
	    "startdate" => date("Y-m-d", strtotime($this->input->post("startdate"))),
	);


	$query = $this->db->insert("guardians_has_pupils", $data);

	return $this->db->affected_rows() > 0 ? true : false;
    }

    function update_pupil()
    {
	$pupilid = $this->input->post("pupilid");

	$data = array(
	    "firstname" => $this->input->post("firstname"),
	    "lastname" => $this->input->post("lastname"),
	    "birthdate" => ($this->input->post("birthdate") != "" ? date("Y-m-d", strtotime($this->input->post("birthdate"))): null),
	    "email" => $this->input->post("email"),
	    "gsm" => $this->input->post("gsm"),
	);

	$this->db->update("pupils", $data, array("id" => $pupilid));
    }

    function get_pupils_by_guardianid($guardianid)
    {
	$this->db->join("pupils", "pupils.id = guardians_has_pupils.pupils_id");
	$this->db->order_by("pupils.firstname", "ASC");
        //geef array mee
	$query = $this->db->get_where("guardians_has_pupils", array("guardians_id" => $guardianid));

	$result = $query->result_array();

	$return = array();
	foreach ($result as $pupil)
	{
	    $pupilreturn = $this->get_pupil($pupil["pupils_id"]);
	    $pupilreturn["type"] = $pupil["type"];
            
	    if (strtotime($pupil["enddate"]) < time() && $pupil["enddate"] != null)
	    {
		//voogdij verlopen, alleen standaard voogdijen kunnen verlopen voogdijschappen zien
		if ($pupil["type"] == "exchange")
		{
		    //is dit voor overdragen pupils???
		}
		else
		{
		    $pupilreturn["active"] = false;
		    $return[] = $pupilreturn;
		}
	    }
	    else
	    {
		$pupilreturn["active"] = true;
		$return[] = $pupilreturn;
	    }
	}

	return $return;
    }

    function delete_guardianship($pupilid)
    {
	$guardianid = $this->session->userdata("guardian_id");
        
	$updatedata = array(
	    "enddate" => date("Y-m-d"),
	);
        
	$this->db->where(array("guardians_id" => $guardianid, "pupils_id" => $pupilid));
	$query = $this->db->update("guardians_has_pupils", $updatedata);

	if ($this->db->affected_rows() > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    function set_profilepicture($pupilid, $profilepicture)
    {
	$this->load->helper('file');
        
	$this->db->where(array("id" => $pupilid));
	$query = $this->db->get("pupils");

	$row = $query->row_array();

	$fullpath = "uploads/" . $row["profilepicture"];

	if ($row["profilepicture"] != "" && file_exists($fullpath))
	{
	    //delete old file
	    unlink($fullpath);
	}

	$this->db->where(array("id" => $pupilid));
	$this->db->update("pupils", array("profilepicture" => $profilepicture));
    }

    function transfer_pupil()
    {
	$postdata = $this->input->post();

	$guardianid = $this->session->userdata("guardian_id");

	
	// huidige verwijderen bij echte overdracht
	if ($postdata["type"] == "standard")
	{
	    $this->db->delete("guardians_has_pupils", array("guardians_id" => $guardianid, "pupils_id" => $postdata["pupilid"]));
	}

	$insertdata = array(
	    "guardians_id" => $postdata["guardianid"],
	    "pupils_id" => $postdata["pupilid"],
	    "startdate" => ($postdata["type"] == "exchange" ? date("Y-m-d", strtotime($postdata["startdate"])) : date("Y-m-d")),
	    "enddate" => ($postdata["enddate"] != "" && $postdata["type"] == "exchange" ? date("Y-m-d", strtotime($postdata["enddate"])) : null ),
	    "type" => $postdata["type"],
	);

	$insertstring = $this->db->insert_string("guardians_has_pupils", $insertdata);
	$insertstring = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insertstring);

	$query = $this->db->query($insertstring);
    }

    function activate_pupil($pupilid)
    {
	$updatedata = array(
	    "enddate" => null
	);

	$guardianid = $this->session->userdata("guardian_id");

	$this->db->where(array("guardians_id" => $guardianid, "pupils_id" => $pupilid, "type !=" => "exchange"));
	$this->db->update("guardians_has_pupils", $updatedata);
    }

}

?>