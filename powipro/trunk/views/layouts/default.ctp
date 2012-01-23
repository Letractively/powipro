<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset('UTF-8'); ?>
	<?php echo $this->Html->script('jquery.min');
		  echo $this->Html->script('date'); 
		  echo $this->Html->script('jquery.datePicker');
		  echo $this->Html->script('cake.datePicker');?>
	<title>Call for Proposals (SPL21)
		<?php /*echo $title_for_layout;*/ ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('cake.generic', 'datePicker.css'));
		echo $scripts_for_layout;
	?>
	<script>
		$(document).ready(function() {
			if ($("#powiproMessage").children().length > 0 ) {
				$("#powiproMessage").delay(300).fadeIn(700).delay(4500).fadeOut(700);
			}
		});
	</script>
</head>
<body>
	
	
	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link('Call for Proposals', array('controller' => 'pages', 'action' => 'home')); ?></h1>
		</div>
		<div id="menu">
			<?php
			foreach ($menu as $menuName => $menuLink) {
				echo '<div class="menuItem">' . $this->Html->link($menuName, $menuLink) . '</div>';
			} ?>
		</div>
			
		<div id="content">
			<div id="powiproMessage">
			<?php echo $this->Session->flash(); ?>
			</div>
			<?php echo $content_for_layout; ?>
			<br/>
			<p><small>
			<?php echo $this->Html->link('Hilfe, ich kenne mich nicht aus!', array('controller' => 'pages', 'action' => 'help')); ?>
			</small></p>
		</div>
		<div id="footer">
			<?php echo $this->Session->flash('auth'); ?>
		</div>
	</div>
	<?php /*echo $this->element('sql_dump'); */?>
	<?php echo $this->Js->writeBuffer(); ?>
	
</body>
</html>
