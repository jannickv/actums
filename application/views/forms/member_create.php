<?php

echo form_open('member/create_member',array('id'=>'register'));

echo form_input(array("placeholder" => "voornaam", "name" => "firstname", "class" =>"validate[required]"));
echo form_input(array("placeholder" => "naam", "name" => "name","class" =>"validate[required]"));
echo "<br/>";
echo form_input(array("placeholder" => "email", "name" => "email","class" =>"validate[required,custom[email]]"));
echo form_input(array("placeholder" => "gsm", "name" => "gsm","class" =>"validate[required,custom[phone]]"));
echo "<br/>";

echo form_password(array("placeholder"=>"wachtwoord", "name" => "password","id"=> "password", "class" => "validate[required,minSize[6],maxSize[20]]"));
echo "<br/>";
echo form_password(array("placeholder"=>"herhaal wachtwoord", "name" => "repeatpassword","class" =>"validate[required, equals[password]]"));

echo form_submit(array("id"=>"submitregistration"));

echo form_close();


?>
