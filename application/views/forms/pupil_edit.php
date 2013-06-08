<?php

echo form_open("pupil/edit_pupil",array("class"=>"validate","id"=>"formpupiledit"));

echo form_label("voornaam:");
echo form_input(array("placeholder"=>"voornaam", "name"=>"firstname", "value" => $firstname, "class"=>"validate[required]"));
echo "<br/>";
echo form_label("naam:");
echo form_input(array("placeholder"=>"familienaam", "name"=>"lastname","value" => $lastname, "class"=>"validate[required]"));
echo "<br/>";
echo form_label("geboortedatum:");
echo form_input(array("placeholder"=>"geboortedatum", "name"=>"birthdate","class"=>"datepicker","value" => $birthdate));
echo "<br/>";
echo form_label("email:");


echo form_input(array("name"=>"email", "value" => $email,"class"=>"validate[custom[email]]"));
echo "<br/>";
echo form_label("gsm:");

echo form_input(array("name"=>"gsm","value"=> $gsm,"class"=>"validate[custom[phone]]"));
echo "<br/>";
echo form_hidden("pupilid",$pupilid);

echo form_submit(array("value"=>"opslaan"));

echo form_close();


?>