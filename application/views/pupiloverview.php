<div id="pupiloverviewacties">
    <a href="" class="show">Toon verlopen voogdijschappen</a>
    <a href="" class="hide">Verberg verlopen voogdijschappen</a>
</div>
<div id="pupilscontainer">


    <?php
    foreach ($pupillen as $pupil):
	?>


        <div class="pupilrow <?php if ($pupil["active"] == false)
    {
	echo "inactive";
    } ?>">
    	<span class="pupilimage">
		<?php
		$filename = @$pupil["profilepicture"];
		$filepath = "uploads/" . $filename;

		if (file_exists($filepath) && $filename != ""):
		    ?>

		    <img src="<?php echo base_url() . "./" . $filepath; ?>">
    <?php endif; ?>
    	</span>
    	<div class="pupildetails">
    	    <span class="name"><a href="<?php echo site_url(array("pupil", "detail", $pupil["id"])) ?>"><?php echo $pupil["firstname"] . " " . $pupil["lastname"]; ?></a><?php if ($pupil["type"] == "exchange"): ?> (tijdelijk)<?php endif;?></span>
    	    <div class="extra">
    		<span class="detail">Geboortedatum:</span><span class="detail"><?php echo ($pupil["birthdate"] != ""? date("d-m-Y", strtotime($pupil["birthdate"])):""); ?></span>
    		<span class="detail">Telefoon:</span><span class="detail"><?php echo $pupil["gsm"]; ?></span>
    		<span class="detail">E-mail:</span><span class="detail"><?php echo $pupil["email"]; ?></span>
    	    </div>

    	</div>
    	<ul class="pupilblokken">
		<?php foreach ($pupil["blocktypes"] as $blocktype): ?>
		    <li style="background-color: <?php echo $blocktype["color"]; ?>">
			<a href="<?php echo site_url(array("pupil", "history", $blocktype["id"], $pupil["id"])); ?>" ><?php echo substr($blocktype["name"], 0, 3); ?></a></li>	    
		<?php endforeach; ?>
    	</ul>
    	<ul class="pupilacties">
    	    <li class="knopbekijkpupil"><a href="<?php echo site_url(array("pupil", "detail", $pupil["id"])) ?>">Dossier bekijken</a></li>
		<?php if ($pupil["type"] == "standard"): ?>
		    <li class="knopverplaatspupil"><a href="<?php echo site_url(array("pupil", "transfer", $pupil["id"])) ?>">Voogdij overdragen</a></li>
		<?php endif; ?>
		<?php if ($pupil["active"]): ?>
		    <li class="knopverwijderpupil"><a href="<?php echo site_url(array("pupil", "delete", $pupil["id"])) ?>" class="confirm" title="Voogdij over <?php echo $pupil["firstname"] . " " . $pupil["lastname"] ?> deactiveren?">Pupil verwijderen</a></li>
		<?php endif; ?>
    <?php if ($pupil["active"] == false): ?><!-- is dit niet zelfde als if (!$pupil["active"])-->
		    <li class="knopverwijderpupil"><a href="<?php echo site_url(array("pupil", "activate", $pupil["id"])) ?>" class="confirm" title="Voogdij over <?php echo $pupil["firstname"] . " " . $pupil["lastname"] ?> heractiveren?">Pupil Heractiveren</a></li>
    <?php endif; ?>

    	</ul>
    	<div class="clear"></div>
        </div>

    <?php
endforeach;
?>

</div>