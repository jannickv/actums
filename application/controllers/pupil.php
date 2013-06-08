<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pupil extends CI_Controller
{

    public function __construct()
    {
	parent::__construct();

	//controleer of gebruiker ingelogd is

	check_login();
    }
    
    public function index()
    {
	redirect("pupil/overview");
    }

    public function add()
    {

	$data["linkercontent"] = $this->load->view("pupilinfo", null, true);

	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupil toevoegen" => "pupil/add");

	$data["middencontent"] =
		$this->load->view("breadcrumbs", $breadcrumbs, true) .
		$this->load->view("info/pupil_add", null, true) .
		$this->load->view("forms/pupil_add", null, true); 
        
	$data["pagetitle"] = "pupil toevoegen";
	$data["bodyclass"] = "body_pupil_add";

	$this->load->view("template", $data);
    }

    public function create_pupil()
    {
	$this->load->model("pupil_model");

	$query = $this->pupil_model->create_pupil();

	if ($query)
	{
	    redirect("pupil/overview");
	}
    }

    public function edit($pupilid)
    {

	//controleer of huidige ingelogde gebruiker voogd is van pupil
	check_guardianship($pupilid);

	$this->load->model("pupil_model");

	$editdata = $this->pupil_model->get_pupil($pupilid);



	$editdata["pupilid"] = $pupilid;

	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupil toevoegen" => "pupil/add");

	$data["linkercontent"] = $this->load->view("pupilinfo", $editdata, true);
	$data["middencontent"] =
		$this->load->view("breadcrumbs", $breadcrumbs, true) .
		$this->load->view("info/pupil_edit", null, true) .
		$this->load->view("forms/pupil_edit", $editdata, true);

	$data["pagetitle"] = "pupil bewerken";
	$data["bodyclass"] = "body_pupil_edit";

	$this->load->view("template", $data);
    }

    public function edit_pupil()
    {
	$pupilid = $this->input->post("pupilid");

	check_guardianship($pupilid);

	//modellen laden
	$this->load->model("pupil_model");


	//communicatie met db
	$this->pupil_model->update_pupil();


	//redirect
        // verwijst naar controller pupil (deze hier) 
        // action detail daarin en geeft var $pupilid mee
	redirect("pupil/detail/$pupilid");
    }

    public function overview()
    {
	//modellen laden
	$this->load->model("pupil_model");
	$this->load->model("block_model");

	$guardianid = $this->session->userdata("guardian_id");
        //$sessionTotal = $this->session->userdata("logged_in");
        //var_dump($guardianid);exit;
	$query = $this->pupil_model->get_pupils_by_guardianid($guardianid);

	foreach ($query as $key => $pupil)
	{
	    $blocktypes = $this->block_model->get_blocktypes_by_pupilid($pupil["id"]);

	    $query[$key]["blocktypes"] = $blocktypes;
	}

	$data["pupillen"] = $query;



        //laad view overview en geef de array pupillen mee
        //function view($view, $vars = array(), $return = FALSE)
	$data["linkercontent"] = $this->load->view("pupiloverview", $data, true);
	$data["rechtercontent"] = $this->load->view("pupiltoevoegenactie", null, true);
	$data["searchbar"] = true;

	$data["pagetitle"] = "pupiloverzicht";
	$data["bodyclass"] = "body_pupil_overview";
        
	$this->load->view("template", $data);
    }

    public function detail($pupilid)
    {
	check_guardianship($pupilid);

	$this->load->model("block_model");
	$this->load->model("pupil_model");

	$pupildata = $this->pupil_model->get_pupil($pupilid);

	$blocktypes = $this->block_model->get_all_blocktypes();

	$middendata = array("pupil" => $pupildata, "blocktypes" => $blocktypes);
	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid");

	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);

	$data["middencontent"] =
		$this->load->view("breadcrumbs", $breadcrumbs, true) .
		$this->load->view("info/pupil_detail", array("name" => $pupildata["firstname"] . " " . $pupildata["lastname"]), true) .
		$this->load->view("dossierblokken", $middendata, true);

	$data["rechtercontent"] = $this->load->view("addblocks", $middendata, true);

	$data["pagetitle"] = "pupildossier";
	$data["bodyclass"] = "body_pupil_detail";

	$this->load->view("template", $data);
    }

    public function delete($pupilid)
    {
	check_guardianship($pupilid);

	$this->load->model("pupil_model");
	$query = $this->pupil_model->delete_guardianship($pupilid);

	redirect("pupil/overview");
    }

    public function history($blocktype = null, $pupilid = null)
    {

	//controle van kritieke parameters

	if ($blocktype == null or $pupilid == null)
	{
	    redirect("pupil/overview");
	}

	check_guardianship($pupilid);

	//laden van modellen
	$this->load->model("pupil_model");
	$this->load->model("block_model");

	// informatie over het huidige blocktype en pupil opvragen
	$blockinfo = $this->block_model->get_blocktype($blocktype);
	$blockinfo["pupilid"] = $pupilid;

	$pupildata = $this->pupil_model->get_pupil($pupilid);

        //toegevoegd door team 2 2012-2013
        switch ($blocktype) {
            //voor block kosten pupil (team 2)
            case 14:
                $blocks = $this->block_model->get_blocks_by_blocktype_and_pupilid(13, $pupilid);
                break;
            //voor block kosten voogd (team 2)
            case 15:
                $guardianid = $this->session->userdata("guardian_id");
                $blocks = $this->block_model->get_blocks_by_blocktype_for_all_pupils_and_guardianid(13, $guardianid);
                break;
            //voor alle andere blocks (van team 1)
            default:
                $blocks = $this->block_model->get_blocks_by_blocktype_and_pupilid($blocktype, $pupilid);
                break;
        }
        
	foreach ($blocks as $key => $block)
	{
	    $query = $this->block_model->get_block($block["id"]);
	    $blocks[$key] = array_merge($blocks[$key], $query);

	    $blockdata = $this->block_model->get_block_data($block["id"]);
	    $blocks[$key]["data"] = $blockdata;
	}

	$historydata["blocks"] = $blocks;

	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);

	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid", "Historiek" => "pupil/history/$blocktype/$pupilid");

        switch ($blocktype) {
            //voor block kosten pupil (team 2)
            case 14:
                //$historydata = getCostOverviewData();
                $data["middencontent"] =
                    $this->load->view("breadcrumbs", $breadcrumbs, true) .
                    $this->load->view("info/pupil_history", $blockinfo, true) .
                    $this->load->view("cost_overview", $historydata, true);

                //$data["rechtercontent"] = $this->load->view("blocktoevoegenactie", $blockinfo, true);
                
                $data["bodyclass"] = "body_pupil_history";
                $data["pagetitle"] = "Pupil geschiedenis";

                $this->load->view("template_cost", $data);
                
                break;
            //voor block kosten voogd (team 2)
            case 15:
                $data["middencontent"] =
                    $this->load->view("breadcrumbs", $breadcrumbs, true) .
                    $this->load->view("info/pupil_history", $blockinfo, true) .
                    $this->load->view("cost_overview", $historydata, true);


                $data["bodyclass"] = "body_pupil_history";
                $data["pagetitle"] = "Pupil geschiedenis";

                $this->load->view("template_cost", $data);

                break;
            //voor alle andere blocks (van team 1)
            default:
                $data["middencontent"] =
                    $this->load->view("breadcrumbs", $breadcrumbs, true) .
                    $this->load->view("info/pupil_history", $blockinfo, true) .
                    $this->load->view("history", $historydata, true);

                $data["rechtercontent"] = $this->load->view("blocktoevoegenactie", $blockinfo, true);
                
                $data["bodyclass"] = "body_pupil_history";
                $data["pagetitle"] = "Pupil geschiedenis";

                $this->load->view("template", $data);
                break;
        }

	
    }

    public function profilepicture($pupilid = null)
    {
	// controle
	check_guardianship($pupilid);

	//modellen laden
	$this->load->model("pupil_model");

	$pupildata = $this->pupil_model->get_pupil($pupilid);


	$breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid", "Profielfoto" => "pupil/profilepicture/$pupilid");

	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);
	$data["middencontent"] =
		$this->load->view("breadcrumbs", $breadcrumbs, true) .
		$this->load->view("info/pupil_profilepicture", null, true) .
		$this->load->view("forms/pupil_profilepicture", $pupildata, true);

	$data["bodyclass"] = "body_pupil_profilepicture";
	$data["pagetitle"] = "Pupil profielfoto";

	$this->load->view("template", $data);
    }

    public function upload_profilepicture($pupilid)
    {
	//controle
	check_guardianship($pupilid);


	$config['upload_path'] = './uploads/';
	$config['allowed_types'] = 'gif|jpg|png';
	$config['max_size'] = '10240';
	$config['max_width'] = '4000';
	$config['max_height'] = '4000';
	$config['encrypt_name'] = true;

	$this->load->library('upload', $config);

	if (!$this->upload->do_upload())
	{
	    $error = array('error' => $this->upload->display_errors('', ''));

	    $this->load->model("pupil_model");

	    $pupildata = $this->pupil_model->get_pupil($pupilid);
	    $pupildata["error"] = $error["error"];

	    $breadcrumbs["breadcrumbs"] = array("Overzicht" => "pupil/overview", "Pupildossier" => "pupil/detail/$pupilid", "Profielfoto" => "pupil/profilepicture/$pupilid");

	    $data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);
	    $data["middencontent"] =
		    $this->load->view("breadcrumbs", $breadcrumbs, true) .
		    $this->load->view("info/pupil_profilepicture", null, true) .
		    $this->load->view("forms/pupil_profilepicture", $pupildata, true);

	    $data["bodyclass"] = "body_pupil_profilepicture";
	    $data["pagetitle"] = "Pupil profielfoto";

	    $this->load->view("template", $data);
	}
	else
	{

	    $data = $this->upload->data();
	    $profilepicture = $data["file_name"];
	    $this->load->model("pupil_model");

	    $this->pupil_model->set_profilepicture($pupilid, $profilepicture);


	    redirect("pupil/detail/$pupilid");
	}
    }

    public function transfer($pupilid)
    {
	check_guardianship($pupilid);
	
	$this->load->model("membership_model");
	$this->load->model("pupil_model");

	$guardians = $this->membership_model->get_guardians();

	$pupil_transfer_data["guardians"] = $guardians;
	$pupil_transfer_data["pupilid"] = $pupilid;
	
	$pupildata = $this->pupil_model->get_pupil($pupilid);
	$breadcrumbs["breadcrumbs"] = array("Overzicht" =>"pupil/overview","Pupildossier"=>"pupil/detail/$pupilid","Pupil overdragen"=>"pupil/transfer/$pupilid");
	
	$data["linkercontent"] = $this->load->view("pupilinfo", $pupildata, true);
	$data["middencontent"] =
		$this->load->view("breadcrumbs", $breadcrumbs, true) .
		$this->load->view("info/pupil_transfer", $pupildata, true) .
		$this->load->view("forms/pupil_transfer", $pupil_transfer_data, true);

	$data["bodyclass"] = "body_pupil_transfer";
	$data["pagetitle"] = "Pupil tijdelijk overdragen";

	$this->load->view("template", $data);
    }
    
    public function do_transfer()
    {
	$this->load->model("pupil_model");
	
	$this->pupil_model->transfer_pupil();
	
	redirect("pupil/overview");
    }
    
    public function activate($pupilid)
    {
	check_guardianship($pupilid);
	
	$this->load->model("pupil_model");
	
	$this->pupil_model->activate_pupil($pupilid);
	
	redirect("pupil/overview");
    }

}