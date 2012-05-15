<?php
$this->pageTitle=Yii::app()->name . ' - File viewer';
$this->breadcrumbs=array(
	'File viewer',
);
?>

<h2>File viewer</h2>

<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>

<?php else: ?>
		
	<p><?php echo $filename; ?> - <?php echo CHtml::link('Edit file', array('file/edit')); ?></p>
	
	<p>
	<?php 
	foreach ($fileContent as $value) {
		echo $value . "<br />";
	}
	?>
	</p>

<?php endif; ?>