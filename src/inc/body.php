<?php
	if(!isset($noHead) && !isset($noShow)) 
	{
		require_once('inc/head.php');
		require_once('inc/nav-bar.php');
		if(isset($bodyFluid))
		{
?>
		<div class="container-fluid papper <?php echo (isset($noWell))? "":"well"; ?>">
<?
		}
		else
		{
?>
		<div class="container papper <?php echo (isset($noWell))? "":"well"; ?>">
<?
		}
	}
?>
<?php
	if(!isset($noShow)) 
	{
		require_once('views/'.$controller."/".$content.".php");
	}
?>
<?php
	if(!isset($noFooter) && !isset($noShow)) 
	{
		require_once('inc/footer.php');
	}
?>