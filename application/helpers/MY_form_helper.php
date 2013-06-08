<?php

function form_field($type = "text", $id = "", $value = "", $required = false, $options = null)
{
    //$CI = & get_instance();
    //$query = $CI->db->query("SELECT name FROM fieldtypes");

    $instance;
 
    $class = "";

    if ($required)
    {
	$class="validate[required]";
    }
    
    switch ($type)
    {
	case "text":
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
	
	case "date":
	    $class.=" datepicker";
	    $instance =  form_input(array("name" => $id, "value" => $value, "class" => $class));
	    break;
	
	case "number":
	    $class.=" validate[custom[number]]";
	    $instance = form_input(array("name" => $id, "value" => $value, "class" => $class));
	    break;
	
	case "select":
	    $options = explode(",", $options);
	    $fulloptions = array();
	    array_walk($options, create_function('&$val', '$val = trim($val);'));
	    foreach ($options as $option)
	    {
		$fulloptions[$option] = $option;
	    }
	    $instance =  form_dropdown($id, $fulloptions, $value);
	    break;
	
	case "phone":
	    $class.=" validate[custom[phone]]";
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
	
	case "url":
	    $class.=" validate[custom[url]]";
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
	
	case "mail":
	    $class.=" validate[custom[email]]";
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
	
	case "textarea":
	    $instance = form_textarea(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
	
	case "zipcode":
	    $class.=" validate[custom[zipcode]]";
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;


	default:
	    $instance = form_input(array("name"=>$id,"value"=>$value,"class"=>$class));
	    break;
    }

    return $instance;
}


?>
