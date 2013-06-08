<div id="acties">
    <a href="<?php echo site_url(array("block", "add", @$id, @$pupilid)) ?>">
	<span class="actieimage">
	    <img src="<?php echo base_url().'assets/images/toevoegen.png' ?>" width="29" height="29">
	</span>
	<span class="actietekst"><?php echo(@$name); ?> toevoegen</span>
    </a>
</div>
