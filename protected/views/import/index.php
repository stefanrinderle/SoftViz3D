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
	
	<h3>Upload own file</h3>
	
	<div class="form">
		<?php echo $uploadDotForm; ?>
	</div>
	
	<div class="form">
		<?php echo $uploadJDependForm; ?>
	</div>
	
	<h3>Generate dot file from server directory structure</h3>
	
	<div class="form">
		<?php echo $directoryPathForm; ?>
	</div>
	
	<h3>Load example files:</h3>
	
	<table>
	  <tr>
	    <th>Showcase</th>
	    <th>Goanna export</th>
	  </tr>
	  <tr>
	    <td>
	    	<p><?php echo CHtml::link('Simple tree example', array('import/simpleTree')); ?></p>
	
			<p><?php echo CHtml::link('Simple metric tree example', array('import/simpleMetricTree')); ?></p>
			
			<p><?php echo CHtml::link('Simple graph example', array('import/simpleGraph')); ?></p>
			
			<p><?php echo CHtml::link('MVC example', array('import/mvc')); ?></p>
	    </td>
	    <td>
	    	<p><?php echo CHtml::link('Irssi', array('import/goanna', project => "irssi")); ?></p>
	    	<p><?php echo CHtml::link('Mongrel2', array('import/goanna', project => "mongrel2")); ?></p>
	    	<p><?php echo CHtml::link('Nusmv', array('import/goanna', project => "nusmv")); ?></p>
	    	<p><?php echo CHtml::link('Firefox warnings', array('import/goanna', project => "firefox")); ?></p>
	    	<p><?php echo CHtml::link('Wireshark', array('import/goanna', project => "wireshark")); ?></p>
			<p><?php echo CHtml::link('Postgresql', array('import/goanna', project => "postgresql")); ?></p>
			<p><?php echo CHtml::link('Chromium', array('import/goanna', project => "chromium")); ?></p>
	    </td>
	  </tr>
	</table>
<?php endif; ?>