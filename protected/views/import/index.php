<?php
$this->pageTitle=Yii::app()->name . ' - Import';
$this->breadcrumbs=array(
	'Import',
);
?>

<h2>Import data</h2>

<?php if(Yii::app()->user->hasFlash('success')): ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
	
	<p>
		<?php echo CHtml::link('Show file', array('file/index')); ?> <br /><br />
		<?php echo CHtml::link('Edit file', array('file/edit')); ?>	
	</p>

<?php else: ?>
	
	<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
	<?php endif; ?>
	

	<p>Please choose one of the following methods:<p/>
	
	<h3>Upload own dot file</h3>
	
	<div class="form">
		<?php echo $uploadForm; ?>
	</div>
	
	<h3>Generate dot file from server directory structure</h3>
	
	<div class="form">
		<?php echo $directoryPathForm; ?>
	</div>
	
	<h3>Load example files:</h3>
	
	<p><?php echo CHtml::link('Simple tree example', array('import/simpleTree')); ?></p>
	
	<p><?php echo CHtml::link('Simple graph example', array('import/simpleGraph')); ?></p>

<?php endif; ?>