<?php

echo form_open_multipart("pupil/upload_profilepicture/".$id);

if(isset($error))
{
    echo "<span class='error'>".$error."</span>";
}

echo form_label("Kies een profielfoto:");
echo "<br/>";
echo "<br/>";

echo form_input(array("type"=>"file","name"=>"userfile"));
echo "<br/>";
echo "<br/>";
echo form_submit(array("value"=>"Uploaden"));

echo form_close();


?>
