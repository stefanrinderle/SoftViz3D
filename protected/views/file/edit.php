<?php
$this->pageTitle=Yii::app()->name . ' - File viewer - edit';
$this->breadcrumbs=array(
	'File viewer', 'Edit'
);
?>

<h2>File Editor</h2>

<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>

<?php else: ?>
		
	<div class="form">
		<?php echo $form; ?>
	</div>

<?php endif; ?>