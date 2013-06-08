<?php
//if(count($advocaten) > 0)
//{
//    $advocaatOptions = array_merge($advocaatOptions,array("Kies een bestaande ".$blocktype['name']=>$blocks));
//}

echo form_open('block/create_block',array("id"=>"formblockadd","class"=>"validate"));

echo form_hidden("enddate", "00-00-0000" );
echo form_hidden("existingblock", "new");
echo form_hidden("label", "onkost");

$optionsAdvocaten = array();
if(count($advocaten) > 0)
{
    $optionsAdvocaten = array_merge($optionsAdvocaten,array("Kies een bestaande advocaat"=>$advocaten));
}

$optionsVrederechters = array();
if(count($vrederechters) > 0)
{
    $optionsVrederechters = array_merge($optionsVrederechters,array("Kies een bestaande vrederechter"=>$vrederechters));
}

$optionsScholen = array();
if(count($scholen) > 0)
{
    $optionsScholen = array_merge($optionsScholen,array("Kies een bestaande school"=>$scholen));
}


echo form_fieldset("Datum:");

echo form_label("datum:");
echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]"));
echo "<br/>";

echo form_fieldset_close();

echo form_fieldset("Informatie over nieuwe ".$blocktype['name'].":",array("name"=>"new"));

echo "<br/>";

$i = 0;
foreach ($fields as $field)
{
    switch ($i) {
        case 4:
            echo "<div class='hidden'>";
                echo form_label("");
                echo form_dropdown("advocaten", $optionsAdvocaten,null,"class = validate[required]");
            echo "<br/>";
                echo form_label("");
                echo form_dropdown("vrederechters", $optionsVrederechters,null,"class = validate[required]");
            echo "<br/>";
                echo form_label("");
                echo form_dropdown("scholen", $optionsScholen,null,"class = validate[required]");
            echo "<br/>";
            echo "</div>";
            break;
        case 5:
            echo '<h3>Start adres</h3>';
            break;
        case 9:
            echo '<h3>Eind adres</h3>';
            break;
        case 17:
            echo '<h3>Opname in</h3>';
            break;
        default:
            break;
    }
    switch ($field["htmltype"]) {
        case 'radio':
            echo form_label("");
            echo form_checkbox($field["id"],$field["label"],FALSE)." ".$field["label"];
            break;
        default:
            echo form_label($field["label"] . ": ");
            echo form_field($field["htmltype"], $field["id"],"", $field["required"], $field["options"]);
            break;
    }
    echo "<br/>";
    $i++;
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


