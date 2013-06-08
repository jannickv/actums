<div id="toevoegenblokken">
    <h2>Info toevoegen</h2>
    <ul>
	<?php foreach($blocktypes as $blocktype): ?>
        <li><a href="<?php echo site_url(array("block","add",$blocktype["id"],$pupil["id"])); ?>">+ <?php echo $blocktype["name"] ?></a></li>
	<?php endforeach; ?>
    </ul>
</div>