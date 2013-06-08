<?php ?>

<div id="dossierblokken">
    <ul>
	
	<li> <a href="<?php echo site_url(array("pupil","edit",$pupil["id"])); ?>" class="id">ID</a> </li>
	
	<?php foreach($blocktypes as $blocktype): ?>
        <li> <a href="<?php echo site_url(array("pupil","history",$blocktype["id"],$pupil["id"])); ?>" style="background-color: <?php echo $blocktype["color"]; ?>"><?php echo $blocktype["name"]; ?></a> </li>
	<?php endforeach; ?>
	
    </ul>
</div>