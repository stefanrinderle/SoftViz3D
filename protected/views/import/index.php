<h2>Import data - choose one of the following:</h2>

<h3>Read server file structure</h3>

<div class="form">
	<?php echo $directoryPathForm; ?>
</div>

<h3>Upload dot file</h3>

<div class="form">
	<?php echo $uploadForm; ?>
</div>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="info">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>