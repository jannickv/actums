<html>

    <?php
    $this->load->view("includes/head");
    ?>
    
    <body class="<?php echo @$bodyclass; //bodyclaas meegegevn vanuit oproepende action ?>">
	
	<?php
	$this->load->view("header");
	?>
	
	<div id="content">
	    <?php
	    if (isset($searchbar) && @$searchbar === true)
	    {
		$this->load->view("searchbar");
	    }
	    ?>

	    <div id="links" class="left">
		<?php
		echo @$linkercontent;
		?>
	    </div>

	    <div id="rechts" class="right">
		<?php
		echo @$rechtercontent;
		?>
	    </div>

	    <div id="midden">
		<?php
		echo @$middencontent;
		?>
	    </div>
	    <div class="clear"></div>

	</div>
	<div class="clear"></div>

	<?php
	$this->load->view("includes/footer");
	?>
	
    </body>
</html>


