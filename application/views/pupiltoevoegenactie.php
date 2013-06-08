<div id="acties">
    <!--site_url(array("controller", "action") is hier dus add action in pupil controller -->
    <a href="<?php echo site_url(array("pupil", "add")) //dus hier opreoepn van action add uit controller pupil ?>">
	<span class="actieimage">
	    <img src="<?php echo base_url().'assets/images/toevoegen.png' ?>" width="29" height="29">
	</span>
	<span class="actietekst">Pupil toevoegen</span>
    </a>
</div>