<?php

class Block_model extends CI_Model
{

    function __construct()
    {
	parent::__construct();
    }

    function get_block_fields_by_blocktype($blocktype)
    {

	$query = $this->db->query("SELECT blockfields.*, fieldtypes.name, fieldtypes.htmltype FROM blockfields JOIN fieldtypes ON fieldtypes.id = blockfields.fieldtype_id WHERE blocktype_id = ?", array($blocktype));

	return $query->result_array();
    }

    function get_block_fields_and_data_by_blockid($blockid)
    {
	$this->db->select("blockfields.*, blockdata.value, fieldtypes.htmltype");
	$this->db->join("blockfields", "blockfields.blocktype_id = blocks.blocktype_id");
	$this->db->join("fieldtypes", "blockfields.fieldtype_id = fieldtypes.id");
	$this->db->join("blockdata", "blockdata.block_id = blocks.id AND blockdata.blockfield_id = blockfields.id", "left");
	$query = $this->db->get_where("blocks", array("blocks.id" => $blockid));

	return $query->result_array();
    }

    function add_new_block($data)
    {

	$guardianid = $this->session->userdata("guardian_id");

	$insertdata = array(
	    "blocktype_id" => $data["blocktype"],
	    "guardian_id" => $guardianid,
	    "label" => $data["label"],
	);

	$query = $this->db->insert("blocks", $insertdata);

	$blockid = $this->db->insert_id();




	if ($blockid)
	{
	    $insertdata = array(
		"existingblock" => $blockid,
		"pupilid" => $data["pupilid"],
		"startdate" => $data["startdate"],
		"comment" => @$data["comment"],
		"enddate" => $data["enddate"],
	    );
	    
	    $this->add_existing_block_to_pupil($insertdata);


	    //reeds gebruikte data verwijderen zodat enkel op te slagen data overblijft

	    unset($data["pupilid"]);
	    unset($data["startdate"]);
	    unset($data["enddate"]);
	    unset($data["blocktype"]);
	    unset($data["label"]);
	    unset($data["comment"]);
	    unset($data["existingblock"]);
	    unset($data["level"]);

	    foreach ($data as $key => $value)
	    {
		$query = $this->db->query("INSERT into blockdata (block_id, blockfield_id, value,guardian_id) VALUES ($blockid,$key,'$value',$guardianid)");
	    }
	}
    }

    function add_existing_block_to_pupil($data)
    {

	$insertdata = array(
	    "block_id" => $data["existingblock"],
	    "pupil_id" => $data["pupilid"],
	    "startdate" => date("Y-m-d", strtotime($data["startdate"])),
	    "comment" => @$data["comment"],
	);

	if ($data["enddate"] != "")
	{
	    $insertdata["enddate"] = date("Y-m-d", strtotime($data["enddate"]));
	}
	else
	{
	    $insertdata["enddate"] = null;
	}

	$query = $this->db->insert("pupils_has_blocks", $insertdata);
    }

    function get_blocktypes_by_pupilid($pupilid)
    {
        $this->db->select("blocktypes.name, blocktypes.id, blocktypes.color");

        $this->db->join("blocks", "pupils_has_blocks.block_id = blocks.id");
        $this->db->join("blocktypes", "blocks.blocktype_id = blocktypes.id");
        $this->db->group_by("blocktypes.id");
        $query = $this->db->get_where("pupils_has_blocks", array("pupil_id" => $pupilid));


	return $query->result_array();
    }

    //aangepast door team 2 2012-2013 voor gebruik in onkost, kosten pupil
    function get_blocks_by_blocktype_and_pupilid($blocktype, $pupilid)
    {
        //aangepast door team 2 2012-2013  -> ophalen commentaar voor blocks
        //$this->db->select("blocks.*, pupils_has_blocks.startdate, pupils_has_blocks.enddate, pupils_has_blocks.id as matchid");
        $this->db->select("blocks.*, pupils_has_blocks.comment, pupils_has_blocks.startdate, pupils_has_blocks.enddate, pupils_has_blocks.id as matchid");

        $this->db->join("pupils_has_blocks", "pupils_has_blocks.block_id = blocks.id");
        $this->db->where(array("pupils_has_blocks.pupil_id" => $pupilid, "blocks.blocktype_id" => $blocktype));
        $this->db->order_by("pupils_has_blocks.startdate", "desc");
        $query = $this->db->get("blocks");

        return $query->result_array();
    }

//    //aangemaakt door team 2 2012-2013 voor gebruik in onkost, kosten voogd
    function get_blocks_by_blocktype_for_all_pupils_and_guardianid($blocktype, $guardianid)
    {
        //hoewel alle data voor een block wordt opgeslaan in de tabel blockdata
        //wordt de startdatum, einddatum en comment in pupil_has_blocks opgeslaan.
        //dit zorgt natuurlijk voor wat last.
        //zeker voor blocktype 15 kosten voogd.
        //voor deze block (de enige die deze methode gebruikt) is eigenlijk alleen de methode
        //get_blocks_by_blocktype_and_guardianid($blocktype, $guardianid) nodig
        //want we kunnen querien in blocks op blocktype En guardianid.
        //MAAR: we hebben ook de comment, startdate en enddate nodig. deze staan dus niet in blockdata
        //waardoor de volgende complexe join nodig is om alle nodige data terug te krijgen.

        $this->db->select("blocks.*, pupils_has_blocks.comment, pupils_has_blocks.startdate, pupils_has_blocks.enddate, pupils_has_blocks.id as matchid");
        $this->db->join("pupils_has_blocks", "pupils_has_blocks.block_id = blocks.id");
        $this->db->where(array("blocks.guardian_id" => $guardianid, "blocks.blocktype_id" => $blocktype));
        $this->db->order_by("pupils_has_blocks.startdate", "desc");
        $query = $this->db->get("blocks");

        return $query->result_array();
    }

    function get_blocks_by_blocktype($blocktype)
    {
	$query = $this->db->get_where("blocks", array("blocktype_id" => $blocktype));

	return $query->result_array();
    }

    function get_block($blockid)
    {
	$query = $this->db->get_where("blocks", array("id" => $blockid));

	return $query->row_array();
    }

    function get_block_data($blockid)
    {
	$this->db->select("blockdata.value, blockfields.label");
	$this->db->join("blockfields", "blockfield_id = blockfields.id");
	$query = $this->db->get_where("blockdata", array("block_id" => $blockid));

	return $query->result_array();
    }
    
    //added by team2: 2012-2013: voor ophalen cost
    function get_block_data_by_blockfieldid_and_blockid($blockid, $blockfieldid)
    {
	$this->db->select("blockdata.value");
	$query = $this->db->get_where("blockdata", array(
                                                    "block_id" => $blockid,
                                                    "blockfield_id" => $blockfieldid
                                                    ));
	return $query->result_array();
    }

    function delete_block_from_pupil($id)
    {
	//rechten nog checken

	$this->db->where("id", $id);
	$query = $this->db->delete("pupils_has_blocks");

	if ($this->db->affected_rows() > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }

    function get_match($matchid)
    {
	$query = $this->db->get_where("pupils_has_blocks", array("id" => $matchid));

	return $query->row_array();
    }

    function update_block()
    {
	$data = $this->input->post();

        //added by team2 2012-2013 voor opslaan cost data
        //check keuze aard(dienst) en concat de info met selectbox item
        switch ($data["46"]) {
            case "Advocaat":
                $advocaat = $data["advocaten"];
                $data["46"] = "Advocaat: ".$advocaat;
                break;
            case "Vrederechter":
                $vrederechter = $data["vrederechters"];
                $data["46"] = "Vrederechter: ".$vrederechter;
                break;
            case "School":
                $school = $data["scholen"];
                $data["46"] = "School: ".$school;
                break;
            default:
                break;
        }
        //verwijder data onbestaande kolommen uit array
        unset($data["advocaten"]);
        unset($data["vrederechters"]);
        unset($data["scholen"]);
        
	$blockid = $data["blockid"];
	$pupilid = $data["pupilid"];
	$guardianid = $this->session->userdata("guardian_id");

	// koppeling updaten
	$updatedata = array(
	    "startdate" => date("Y-m-d", strtotime($data["startdate"])),
	    "comment" =>$data["comment"],
	);
	if ($data["enddate"] != "")
	{
	    $updatedata["enddate"] = date("Y-m-d", strtotime($data["enddate"]));
	}
	else
	{
	    $updatedata["enddate"] = null;
	}

	//$this->db->where(array("pupil_id" => $pupilid, "block_id" => $blockid));
	$this->db->update("pupils_has_blocks", $updatedata, array("pupil_id" => $pupilid, "block_id" => $blockid));


	// block update
	
	$updatedata = array(
	    "label"=> $data["label"],
	);
	
	$this->db->update("blocks", $updatedata, array("id" => $blockid));
	
	
	// overbodige data verwijderen
	unset($data["pupilid"]);
	unset($data["startdate"]);
	unset($data["enddate"]);
	unset($data["blockid"]);
	unset($data["label"]);
	unset($data["comment"]);
	unset($data["level"]);

	// data zelf updaten

        //toegevoegd 4 maart 2013
        //delete checkboxes verslag, onkosten, chronologie
        //indien aangevinkt worden ze toch toegevoegd
        for ($index = 60; $index < 63; $index++) 
        {
            $this->db->where(array(
                "block_id"      => $blockid,
                "blockfield_id" => $index
            ));
            $query = $this->db->delete("blockdata");
        }
            

	foreach ($data as $key => $value)
	{
	    //key is field_id, value is waarde
	    $this->db->query("INSERT into blockdata (block_id, blockfield_id, value,guardian_id) VALUES ($blockid, $key, '$value', $guardianid) ON DUPLICATE KEY UPDATE value = '$value'");
	}
    }

    function get_blocktype($blocktype)
    {
	$query = $this->db->get_where("blocktypes", array("id" => $blocktype));

	return $query->row_array();
    }

    function get_all_blocktypes()
    {
	$query = $this->db->get("blocktypes");

	return $query->result_array();
    }

    function get_blocks_by_blocktype_and_guardianid($blocktype, $guardianid)
    {
	$query = $this->db->get_where("blocks", array("blocktype_id" => $blocktype, "guardian_id" => $guardianid));

	return $query->result_array();
    }

}

?>
