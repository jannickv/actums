<html>

    <?php
    $this->load->view("includes/head");
    ?>
    
    <body class="<?php echo @$bodyclass; //bodyclass meegegevn vanuit oproepende action ?>">
	
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

	    <div id="middenCost">
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


