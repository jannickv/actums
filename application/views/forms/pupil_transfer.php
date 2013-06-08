<?php

if (count($guardians) > 1)
{
    echo form_open("pupil/do_transfer", array("class" => "validate","id"=>"formpupiltransfer"));



    echo form_label("Deze voogdij ");
    echo form_dropdown("type", array("exchange" => "tijdelijk", "standard" => "definitief"));
    echo form_label( "overdragen aan ");

    $options = array();

    foreach ($guardians as $guardian)
    {
	if ($guardian["id"] != $this->session->userdata("guardian_id"))
	{
	    $options[$guardian["id"]] = $guardian["firstname"] . " " . $guardian["lastname"];
	}
    }

    echo form_dropdown("guardianid", $options);
    echo "<br/>";

    echo "<div id='periode'>";
    echo form_label("vanaf:");
    echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]"));
    echo "<br/>";

    echo form_label("tot:");
    echo form_input(array("name" => "enddate", "class" => "datepicker validate[required]"));

    echo "</div>";

    echo form_hidden("pupilid", $pupilid);

    echo form_submit(array("value" => "Overdragen"));

    echo form_close();
}
else
{
    echo "Er zijn geen andere voogden beschikbaar";
}
?>
