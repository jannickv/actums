<?php

class Membership_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function validate()
    {
        $this->db->where('email', $this->input->post('email'));
        $this->db->where('password', sha1($this->input->post('password')));
        $query = $this->db->get('guardians');
        
        if($query->num_rows() == 1)
        {
            $row = $query->row();
            return $row->id;
        }
        else
        {
            return false;
        }
    }
    
    function create_member()
    {
        //haal data op uit form member_create
        $data = array(
            'email' => $this->input->post('email'),
            'password' => sha1($this->input->post('password')),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('name'),
	    'phone' => $this->input->post('gsm'),
        );
        
        //maak insert string aan
        $insertstring = $this->db->insert_string('guardians',$data);
        //kuis op
        $insertstring = str_replace('INSERT INTO','INSERT IGNORE INTO',$insertstring);
        
        //voer uit
        $this->db->query($insertstring);
        
        //geef boolean terug
        return $this->db->affected_rows() > 0? true : false;
    }
    
    
    function check_guardianship($guardianid, $pupilid)
    {
	$query = $this->db->get_where("guardians_has_pupils", array("guardians_id"=>$guardianid, "pupils_id"=>$pupilid));
	
	if($query->num_rows() > 0)
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    function get_guardians()
    {
	$query = $this->db->get_where("guardians",array("admin"=>false,"active"=>true));
	
	return $query->result_array();
    }
    
    
    
}


?>
