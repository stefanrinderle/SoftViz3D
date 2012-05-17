<?php
$this->pageTitle=Yii::app()->name . ' - File viewer';
$this->breadcrumbs=array(
	'File viewer',
);
?>

<h2>File checker</h2>

<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
		
		<div>
			<?php echo var_dump($exception); ?>
		</div>

<?php elseif(Yii::app()->user->hasFlash('success')):?>
	<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
	<div>
		<?php echo print_r($result); ?>
	</div>
<?php endif; ?>