<?php

echo form_open("block/update/".$blocktype["id"]."/$pupilid",array("id" => "formblockedit","class"=>"validate"));


echo form_hidden("enddate", "00-00-0000" );
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
//echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]"));
echo form_input(array("name" => "startdate", "class" => "datepicker validate[required]","value"=>date("d-m-Y", strtotime($startdate))));
echo "<br/>";

echo form_fieldset_close();

echo form_fieldset("2. Informatie ".$blocktype['name'].":");

echo "<br/>";

$i = 0;
foreach ($fields as $field)
{
    switch ($i) {
        case 4:
            //form_dropdown("46", $optionsCurrent,null,"class = validate[required]");
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
            $set = FALSE;
            if ($field["value"] != NULL){
                $set = TRUE;
            }
            echo form_checkbox($field["id"],$field["label"],$set)." ".$field["label"];
            break;
        default:
            echo form_label($field["label"] . ": ");
            echo form_field($field["htmltype"], $field["id"], $field["value"], $field["required"],$field["options"]);
            break;
    }
    echo "<br/>";
    $i++;
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


