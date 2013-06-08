<?php

echo form_open("block/update/".$blocktype["id"]."/$pupilid",array("id" => "formblockedit","class"=>"validate"));

echo form_fieldset("1. Periode:");

echo form_label("startdatum:");
echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]","value"=>date("d-m-Y", strtotime($startdate))));
echo "<br/>";
echo form_label("einddatum (optioneel):");
echo form_input(array("name" => "enddate", "class" => "datepicker","value"=>($enddate != "" ? date("d-m-Y", strtotime($enddate)) : "")));
echo "<br/>";

echo form_fieldset_close();

echo form_fieldset("Titel *",array("name"=>"titel"));

echo form_label($blocktype["label"]);
echo form_input(array("name"=>"label","value"=> $label,"class" => "validate[required]"));

echo form_fieldset_close();

echo form_fieldset("2. Informatie ".$blocktype['name'].":");

echo "<br/>";
foreach ($fields as $field)
{
    echo form_label($field["label"] . ": ");
    echo form_field($field["htmltype"], $field["id"], $field["value"], $field["required"],$field["options"]);
    echo "<br/>";
}


echo form_fieldset_close();

echo form_label("Commentaar:");

echo form_textarea(array("name"=>"comment","value"=>$comment));

echo "<br/>";
echo form_hidden("blockid", $blockid);
echo form_hidden("pupilid", $pupilid);
echo form_hidden("level", $blocktype['level']);
echo form_submit(array("value"=>"opslaan"));

echo form_close();
?>


