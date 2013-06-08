<?php 
$i = 0;
$len = count($blocks);
?>
<div id="chronologie">
    <?php foreach($blocks as $block):?>
    <?php $i++?>
    <div class="chronodeel">
        <div class="chronodeelheader">
	    <span class="toewijsdatum">Actief vanaf: <?php echo date("d-m-Y", strtotime($block["startdate"]));?></span>
	    <div class="acties">
	    <a href="<?php echo site_url(array("block","delete",$block["matchid"]))?>" class="chronobewerken confirm" title="<?php echo $block["label"] ?> verwijderen uit de lijst?">Verwijderen</a>
	    <a href="<?php echo site_url(array("block","edit",$block["matchid"]))?>" class="chronobewerken">Bewerken</a>
	    
	    </div>
	</div>
        <div class="chronodeeltitel"><h3><?php echo $block["label"];?></h3>
	</div>
        <div class="chronodeelcontent">
	    <?php foreach($block["data"] as $field):?>
	    
	    
	    
	    <span class="chronodeellabel"><?php echo $field["label"]?>: </span>
	    <?php echo $field["value"]?>
	    <br/>
	    
	    <?php endforeach; ?>
	    
	    <div class="chronodeelcommentaar">
                <span class="chronodeellabel">commentaar: </span>
                <?php echo $block["comment"]?>
            </div>
	    <!--<div class="chronodeelaanpassingsdatum">Laatste aanpassing door  op </div>-->
        </div>
    </div>
    <?php if($i < $len):?>
    <div class="chronoonderscheider"></div>
    <?php endif; ?>
    <?php endforeach;?>
    
    <?php if($len < 1): ?>
    
    Er is geen data om weer te geven. Rechts vind u een knop om data toe te voegen.
    
    <?php endif; ?>
</div>