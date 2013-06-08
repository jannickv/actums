<?php

echo form_open("pupil/create_pupil",array("id"=>"formuliertoevoegen", "class"=>"validate"));
//function create pupil wordt aangeroepen
echo form_input(array("placeholder"=>"voornaam", "name"=>"firstname","class"=>"medium marginright validate[required]" ));

echo form_input(array("placeholder"=>"familienaam", "name"=>"lastname","class"=>"large validate[required]"));
echo form_input(array("placeholder"=>"telefoon", "name"=>"gsm","class"=>"medium marginright validate[custom[phone]]"));
echo form_input(array("placeholder"=>"email", "name"=>"email","class"=>"large validate[custom[email]]"));
echo form_input(array("placeholder"=>"geboortedatum", "name"=>"birthdate","class"=>"datepicker medium"));

echo "<br/>";

echo form_input(array("placeholder"=>"startdatum voogdij", "name"=>"startdate","class"=>"datepicker medium validate[required]"));

echo "<br/>";

echo form_submit(array("value"=>"","class"=>"knoptoevoegen"));

echo form_close();


?>
