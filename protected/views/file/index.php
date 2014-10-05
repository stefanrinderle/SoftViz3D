<?php
$this->pageTitle=Yii::app()->name . ' - File viewer';
$this->breadcrumbs=array(
	'File viewer',
);
?>

<h2>File viewer</h2>

<?php if(Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
<?php endif; ?>

<p><?php echo CHtml::link('Back to Projects', array('project/index', 'projectId' => $projectId)); ?></p>

	<p>
		<?php echo CHtml::link('Edit file', array('file/edit', 'projectId' => $projectId)); ?> -
		<?php echo CHtml::link('Check file', array('file/check', 'projectId' => $projectId)); ?>
	</p>
	
	<p style="font-family: monospace;">
	<?php 
	foreach ($fileContent as $value) {
		echo $value . "<br />";
	}
	?>
	</p>