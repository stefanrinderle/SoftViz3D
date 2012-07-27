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
	
	<table>
	<tr>
	<td>
		<div class="form">
			<?php echo $uploadDotForm; ?>
		</div>
	</td><td>
		<div class="form">
			<?php 
			if (Yii::app()->params['import']['jdepend']) {
				echo $uploadJDependForm;
			} else {
				echo "jdepend import disabled";
			}
			 ?>
		</div>
	</td>
	</tr>
	</table>
	
	<h3>Load example files:</h3>
	
	<table>
	  <tr>
	    <th>Showcase</th>
	    <th colspan="2">Goanna example projects</th>
	  </tr>
	  <tr>
	    <td>
	    	<?php 
	    		$handle = opendir (Yii::app()->basePath . "/data/exampleFiles/");
	    		
				while ($file = readdir($handle)) {
					if ($file != "." && $file != ".." && $file != "goanna") {
						echo "<p>";
					 	echo CHtml::link($file, array('import/exampleFile', 'file' => $file, 'projectId' => $projectId));
					 	echo "</p>";
					}
				}
				closedir($handle);
	    	?>
	    </td>
	    <td>
	    	<?php 
	    		$handle = opendir (Yii::app()->basePath . "/data/exampleFiles/goanna");
	    		
	    		$counter = 0;
	    		
				while ($file = readdir($handle)) {
					if ($file != "." && $file != "..") {
						$counter = $counter + 1;
						
						echo "<p>";
					 	echo CHtml::link($file, array('import/goanna', 'file' => $file, 'projectId' => $projectId));
					 	echo "</p>";
					}	
					if ($counter != 0 && $counter % 4 == 0) {
						echo "</td><td>";
					}
				}
				closedir($handle);
	    	?>
	    </td>
	  </tr>
	</table>
	
	<h3>Generate dot file from server directory structure</h3>

	<div class="form">
			<?php 
			if (Yii::app()->params['import']['serverDirectory']) {
				echo $directoryPathForm; 
			} else {
				echo "server directory path import disabled";
			}
			?>
	</div>
	
<?php endif; ?>