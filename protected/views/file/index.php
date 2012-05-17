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
		
	<p><?php echo $filename; ?> - 
		<?php echo CHtml::link('Edit file', array('file/edit')); ?> -
		<?php echo CHtml::link('Check file', array('file/check')); ?>
	</p>
	
	<p style="font-family: monospace;">
	<?php 
	foreach ($fileContent as $value) {
		echo $value . "<br />";
	}
	?>
	</p>

<?php endif; ?>