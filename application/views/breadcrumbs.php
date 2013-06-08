<ul id="breadcrumbs">
    
    <?php 
    
    $i = 0;
    $count = count($breadcrumbs);
    
    ?>
    
    <?php foreach ($breadcrumbs as $name => $uri): ?>
    
    <?php $i++; ?>

    <li><a href="<?php echo site_url($uri) ?>"><?php echo $name ?></a> 
	<?php 
	if($i < $count):
	?>
	
	&gt;
	
	<?php
	endif;
	?>
    </li>
	
    <?php endforeach; ?>
</ul>

