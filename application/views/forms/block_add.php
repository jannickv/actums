<?php

echo form_open('block/create_block',array("id"=>"formblockadd","class"=>"validate"));

echo form_fieldset("Periode:");

echo form_label("startdatum:");
echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]"));
echo "<br/>";
echo form_label("einddatum: *");
echo form_input(array("name" => "enddate", "class" => "datepicker"));
echo "<br/>";
echo "<p>* De einddatum is niet verplicht. Indien u een einddatum weet kan u deze ingeven.</p>";

echo "<br/>";

echo form_fieldset_close();



echo form_fieldset("Welke actie wil u ondernemen?",array("name"=>"action"));

$options = array(
    ""=> "-- maak een keuze --",
    "new"=>"Maak een nieuwe ".$blocktype['name']." aan:");

if(count($blocks) > 0)
{
    $options = array_merge($options,array("Kies een bestaande ".$blocktype['name']=>$blocks));
}

echo form_dropdown("existingblock", $options,null,"class = validate[required]");

echo form_fieldset_close();

echo form_fieldset("Titel *",array("name"=>"titel"));

echo form_label($blocktype["label"]);
echo form_input(array("name"=>"label","class" => "validate[required]"));

echo "<p>*  </p>";

echo form_fieldset_close();



echo form_fieldset("Informatie over nieuwe ".$blocktype['name'].":",array("name"=>"new"));

echo "<br/>";

foreach ($fields as $field)
{
    echo form_label($field["label"] . ": ");
    echo form_field($field["htmltype"], $field["id"],"", $field["required"], $field["options"]);
    echo "<br/>";
}



echo form_fieldset_close();

echo form_label("Commentaar:");

echo form_textarea(array("name"=>"comment"));

echo "<br/>";
echo form_hidden("blocktype", $blocktype["id"]);
echo form_hidden("pupilid", $pupilid);
echo form_hidden("level", $blocktype['level']);
echo form_submit(array("value"=>"toevoegen"));

echo form_close();
?>


