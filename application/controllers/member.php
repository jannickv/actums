<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends CI_Controller
{

    public function index()
    {
	$this->load->view("login");
    }

    public function login()
    {
	$this->load->view("login");
    }

    public function logout()
    {
	$this->session->unset_userdata("guardian_id");
	$this->session->unset_userdata("logged_in");
	$this->session->unset_userdata("guardian_email");
	$this->session->sess_destroy();
	redirect("member","login");
	
    }

    public function validate_credentials()
    {
	$this->load->model("membership_model");
	$query = $this->membership_model->validate();

	if ($query)
	{
	    $data = array(
		'guardian_id' => $query,
		'guardian_email' => $this->input->post('email'),
		'logged_in' => true,
	    );

	    $this->session->set_userdata($data); //userdata in sessie wegschrijven
	    redirect("pupil/overview"); //naar overzichtspagina gaan
	}
	else
	{
	    $data["loginerror"] = true;
	    $this->load->view("login", $data);
	}
    }

    //action wordt opgeroepen in view->login
    //als daarin user kies voor 'registreer nu'
    //dan wordt form member_create opgeroepen (echo $this->load->view('forms/member_create');
    //dan wordt in die form member_create de actiion create_member uit controller member aangerpoepe = deze hieronder dus
    //dus 1 login view
    //2 gebruiker kiest registreren
    // 3 form member_ceate oproepen
    //4 daarin wordt deze onderstaande functie opgereoepe
    public function create_member()
    {
        //5 laad model membership_model
	$this->load->model("membership_model");
        //6 voer create member uit in membership_model
	$query = $this->membership_model->create_member();
        //geeft true of false terug
        
	if ($query)
	{
            //geef aan var $newmembersuccess waarde true terug 
	    $data["newmembersuccess"] = true;
            //laadt nu OPNIEUW originele vie nl/ login 
            $this->load->view("login", $data);
            //dddarin zal nu eesrt opnieuw var $registrationerror gecheckt worden
            //f (!isset($registrationerror))  deze is false (not true dus verder gaan
            //
            //dan wordt var $newmembersuccess gecheckte en die is true dus boodschap weeergeven
            //<?php if (isset($newmembersuccess) && $newmembersuccess === true): 
            //<span class="success">Je account werd aangemaakt. Je kan nu inloggen.</span>
	    //
            //dan wordt formulier voor member_login geladen
            //<?php echo $this->load->view('forms/member_login');
            //deze komt in pagina van view login bijgezet en daarin wordt email en wachtwoord getzet
            //daarin wordt dan ook weer een action uit deze controller gedaan nl. validate_credentials
            //....
	    //als die klopt dan zie die functie waarin de overview wordt geladen
            // nl redirect("pupil/overview") 
            //dus geen rechtstreeks view laden maar controller pupil
            //met daarin action overview (die dan wel weer view laadt)
	}
	else
	{
            //anders de eerste var registrationerror gzete op true
	    $data["registrationerror"] = true;
            //en bij laden view wordt die meegegeven
	    $this->load->view("login", $data);
	}
    }
}