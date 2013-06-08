<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Block extends CI_Controller
{

    public function __construct()
    {
	parent::__construct();

	//controleer of gebruiker ingelogd is
	
	check_login();
	 
    }
    
    public function index()
    {
	$this->add();
    }

    public function add($blocktype = null, $pupilid = null)
    {
	// controle 

	if (!is_numeric($blocktype) or !is_numeric($pupilid))
	{
	    redirect("pupil/overview");
	}
	
	check_guardianship($pupilid);

	//benodigde modellen inladen
	$this->load->model('block_model');
	$this->load->model('pupil_model');


	//benodigde variabelen

	$guardianid = $this->session->userdata("guardian_id");
	
	$blocktypeinfo = $this->block_model->get_blocktype($blocktype);
	
	switch ($blocktypeinfo["level"])
	{
	    case "global":
		$blocks = $this->block_model->get_blocks_by_blocktype($blocktype);
		break;
	    
	    case "guardian":
		$blocks = $this->block_model->get_blocks_by_blocktype_and_guardianid($blocktype, $guardianid);
		break;
	    
	    case "pupil":
		$blocks = $this->block_model->get_blocks_by_blocktype_and_pupilid($blocktype, $pupilid);
		break;

	    default:
		$blocks = $this->block_model->get_blocks_by_blocktype_and_guardianid($blocktype, $guardianid);
		break;
	}
	/**
         * @todo gfhfg
         */
	$currentblocks = array();
        
        /**
         * @todo fdfg
         */
	foreach ($blocks as $block)
	{
	    $currentblocks[$block["id"]] = $block["label"];
	}

	//data voor formulier
	$formdata["fields"] = $this->block_model->get_block_fields_by_blocktype($blocktype);
	$formdata["blocktype"] = $blocktypeinfo;
	$formdata["pupilid"] = $pupilid;
	$formdata["blocks"] = $currentblocks;

	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid", "Geschiedenis" => "pupil/history/$blocktype/$pupilid", "Informatie toevoegen" => "block/add/$blocktype/$pupilid");

        //toegevoegd door team 2012-2013
        switch ($blocktype) {
            case 13:
                $advocaten = $this->getEntitiesForSelectList($pupilid, 1);
                $formdata["advocaten"] = $advocaten;

                $vrederechters = $this->getEntitiesForSelectList($pupilid, 7);
                $formdata["vrederechters"] = $vrederechters;

                $scholen = $this->getEntitiesForSelectList($pupilid, 2);
                $formdata["scholen"] = $scholen;

                $data["middencontent"] =
                $this->load->view('breadcrumbs', $breadcrumbs, true) .
                $this->load->view('info/block_add', $blocktypeinfo, true) .
                $this->load->view('forms/block_add_cost', $formdata, true);
                break;
            
            default:
                $data["middencontent"] =
                $this->load->view('breadcrumbs', $breadcrumbs, true) .
                $this->load->view('info/block_add', $blocktypeinfo, true) .
                $this->load->view('forms/block_add', $formdata, true);
                break;
        }
	

	//data voor huidige pupil
	$pupildata = $this->pupil_model->get_pupil($pupilid);
	$pupildata["pupilid"] = $pupilid;

	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);

	$data["bodyclass"] = "body_block_add";
	$data["pagetitle"] = "Data toevoegen";

	$this->load->view("template", $data);
    }

    public function create_block()
    {
	$this->load->model("block_model");

	$postdata = $this->input->post();

        //module onkosten: toegevoegd door team 2 2012-2013
        //check keuze aard(dienst) en concat de info met selectbox item van advocaten of ...
        //hier merk je goed hoe beperkt de code is: want we moeten filteren op de PK van de tabel 'blockfields'
        //om de juiste input field terug te vinden.
        // de input velden worden dus automatisch gegenereerd door de block te koppelen aan blocktype
        //en vervolgens voor dat blocktype alle blockfields op te halen.
        //dat geeft als gevolg dat de volgorde van het tonen van de input fields vastligt door de volgorde op PK
        //in de DB (blockfields. er valt DUS niet in te grijpen in die volgorde.
        //en aanspreken van een specifiek veld zoals hieronder kan enkel door de PK (die dus tevens de name attribuut
        //voor de input op te zoeken in de DB.
        //eveneens betekent dit dat toevoegen van een extra input field achteraf automatisch achteraan de rij komt
        //tenzij de tabel heropgebouwd wordt (na truncate) om te kunnen zelf de PK's bepalen.
        //46 geeft dus de blockfield voor aard(dienst) aan waaronder onder andere advocaten etc valt.
        switch ($postdata["46"]) {
            case "Advocaat":
                $advocaat = $postdata["advocaten"];
                $postdata["46"] = "Advocaat: ".$advocaat;
                break;
            case "Vrederechter":
                $vrederechter = $postdata["vrederechters"];
                $postdata["46"] = "Vrederechter: ".$vrederechter;
                break;
            case "School":
                $school = $postdata["scholen"];
                $postdata["46"] = "School: ".$school;
                break;
            default:
                break;
        }
        //verwijder data onbestaande kolommen uit array
        //want array namen zijn tevens ook tabelnamen
        unset($postdata["advocaten"]);
        unset($postdata["vrederechters"]);
        unset($postdata["scholen"]);

	if ($postdata["existingblock"] == "new")
	{
	    $this->block_model->add_new_block($postdata);
	}
	else
	{
	    //bestaande block koppelen
	    $this->block_model->add_existing_block_to_pupil($postdata);
	}

	$blocktype = $postdata['blocktype'];
	$pupilid = $postdata['pupilid'];

	redirect("pupil/history/$blocktype/$pupilid");
    }

    public function delete($matchid = null)
    {
	if(!is_numeric($matchid))
	{
	    redirect("pupil/overview");
	}
	
	check_match($matchid);
	
	$this->load->model("block_model");

	$query = $this->block_model->delete_block_from_pupil($matchid);

	if ($query)
	{
	    redirect($_SERVER["HTTP_REFERER"]);
	}
    }

    public function edit($matchid = null)
    {
	if(!is_numeric($matchid))
	{
	    redirect("pupil/overview");
	}
	
	check_match($matchid);
	
	$this->load->model("block_model");
	$this->load->model('pupil_model');

	$match = $this->block_model->get_match($matchid);

	$blockid = $match["block_id"];
	$pupilid = $match["pupil_id"];

	//data voor formulier
	$block = $this->block_model->get_block($blockid);
	$blocktype = $block["blocktype_id"];
	
	$blocktypeinfo = $this->block_model->get_blocktype($blocktype);
	
	$formdata["startdate"] = $match["startdate"];
	$formdata["enddate"] = $match["enddate"];
	$formdata["label"] = $block["label"];
	$formdata["comment"] = $match["comment"];
	$formdata["fields"] = $this->block_model->get_block_fields_and_data_by_blockid($blockid);



	$formdata["blockid"] = $blockid;
	$formdata["pupilid"] = $pupilid;
	$formdata["blocktype"] = $blocktypeinfo;
	
	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid", "Geschiedenis" => "pupil/history/$blocktype/$pupilid", "Informatie bewerken" => "block/add/$blocktype/$pupilid");

        //voor block onkosten, toegevoegd door team 2 2012-2013
        if($blocktype == 13)
        {
            $value = $formdata["fields"]["3"]["value"];
            $formdata["fields"]["3"]["options"] = $value.",".$formdata["fields"]["3"]["options"];
            
            $advocaten = $this->getEntitiesForSelectList($pupilid, 1);
            $formdata["advocaten"] = $advocaten;
            
            $vrederechters = $this->getEntitiesForSelectList($pupilid, 7);
            $formdata["vrederechters"] = $vrederechters;
            
            $scholen = $this->getEntitiesForSelectList($pupilid, 2);
            $formdata["scholen"] = $scholen;
            
            $data["middencontent"] =
		$this->load->view('breadcrumbs', $breadcrumbs, true) .
		$this->load->view('info/block_edit', $blocktypeinfo, true) .
		$this->load->view('forms/block_edit_cost', $formdata, true);
        }
        else //voor alle andere blocks
        {
            $data["middencontent"] =
		$this->load->view('breadcrumbs', $breadcrumbs, true) .
		$this->load->view('info/block_edit', $blocktypeinfo, true) .
		$this->load->view('forms/block_edit', $formdata, true);
        }

	$blockdata = $this->block_model->get_block_data($blockid);

	//data voor huidige pupil
	$pupildata = $this->pupil_model->get_pupil($pupilid);
	$pupildata["pupilid"] = $pupilid;

	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);

	$data["bodyclass"] = "body_block_edit";
	$data["pagetitle"] = "Data toevoegen";

	$this->load->view("template", $data);
    }

    public function update($blocktype, $pupilid)
    {
	check_guardianship($pupilid);
	
	$this->load->model("block_model");

	$query = $this->block_model->update_block();

	redirect("pupil/history/$blocktype/$pupilid");
    }
    
    //toegevoegd door team 2
    public function getEntitiesForSelectList($pupilid, $blocktypeid) 
    {
        $entities = $this->block_model->get_blocks_by_blocktype_and_pupilid($blocktypeid,$pupilid);
        if($entities != NULL){
            foreach ($entities as $entity) 
            {
                $entityList[$entity["label"]] = $entity["label"];
            }
            $data = $entityList;
        }
        else {
            $data = "geen gekend voor deze pupil";
        }
        
        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */