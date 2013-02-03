<?php $this->pageTitle=Yii::app()->name; ?>

<h1>
	Welcome to the project homepage
</h1>

<center style="margin-top: 30px;">
<h3>
	You now have the opportunity to try this 3d visualization tool with
	example files<br />
	<?php 
	echo CHtml::link('Go to example page', array('/site/page', 'view'=>'examples'));
	?>
</h3>
<h3>Please feel free to give Feedback<br /> 
	<?php 
	echo CHtml::link('Go to feedback page', array('/site/feedback'));
	?>
</h3>
</center>

<p>The main goal for the next months is to extend this homepage to a point where you are able to 
upload your own files and visualize your own projects. This is just the beginning :-)</p>

<table><tr>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/firefox" width="400px"></td>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/nusmv" width="400px"></td>
</tr>
</table>

<p>This project started as a master thesis as the National ICT Australia
	in Sydney. Thanks to Franck Cassez and Ralf Huuck for the help during
	the thesis.</p>
	
<p>The example files are created by the Goanna tool. <a href="http://redlizards.com/">Goanna</a> (http://redlizards.com/) is a
	static program analysis tool for C/C++ software projects based on model
	checking. The technology has been developed at National ICT Australia
	(NICTA) in Sydney since 2005. Goanna can be easily integrated in the
	software development process by a seamless integration into Microsoft
	Visual Studio and the Eclipse IDE.</p>