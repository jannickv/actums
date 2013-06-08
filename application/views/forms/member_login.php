<?php

echo form_open('member/validate_credentials',array("id"=>"login"));
echo form_label("email");
echo form_input(array("name"=>"email","class"=>"validate[required,custom[email]]"));
echo "<br/>";
echo form_label("wachtwoord");
echo form_password(array("name"=>"password","class"=>"validate[required]"));
echo "<br/>";
echo form_submit(array("id"=>"submitlogin"));
?>
<span id="of">of</span>
        <a href="" id="registreerbutton"></a>


<?php

echo form_close();

?>
