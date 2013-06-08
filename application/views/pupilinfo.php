<?php ?>

<div id="pupilinfo">
    <div id="profilepic">
	
	<?php 
	    
	    $filename = @$profilepicture;
	    $filepath = "uploads/".$filename;
	   
	    if (file_exists($filepath) && $filename != ""): ?>
	
	    <img src="<?php echo base_url()."./".$filepath; ?>">
	    
	    <?php endif; ?>
	
	
	<a href="<?php echo site_url(array("pupil","profilepicture",@$id))?>">Foto wijzigen</a>
    </div>
    <div id="pupilgegevens">
        <p><span class="pupilgegevenlabel">Naam pupil:</span> <?php echo @$firstname." ".@$lastname;?></p>
        <p>&nbsp;</p>
        <p><span class="pupilgegevenlabel">Geboortedatum pupil:</span> <?php if (@$birthdate !="") echo date("d/m/Y",  strtotime(@$birthdate));?></p>
        <p>&nbsp;</p>
	<p><span class="pupilgegevenlabel">Email pupil:</span> <?php echo @$email;?></p>
        <p>&nbsp;</p>
	<p><span class="pupilgegevenlabel">GSM pupil:</span> <?php echo @$gsm;?></p>
        <p>&nbsp;</p>
    </div>
</div>