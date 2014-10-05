<?php $this->pageTitle=Yii::app()->name; ?>

<h1>@Deprecated</h1>
<p>
I have decided to stop any work on this project. 
Although all used components are open source, it is difficult 
to get the required data out of the projects.
</p><p>
I will continue my work on 3d software visualization with
a plugin for sonar, which hopefully will do the same as this one.
The crucial advantage is that sonar is used by a lot of people and
also can also deliver all required data.
</p>

<h1>
    <a href="https://github.com/stefanrinderle/sonar-softviz3d">Sonar plugin on GitHub</a>
</h1>

<p></p>
<h2>
	Welcome to the project homepage
</h2>

<p>
This work presents a tool for 3D visualization of software structures and dependencies that can be integrated in the software development process.
The analysis of the existing tools has shown that the visualization should be platform and language-independent, based on standard technologies and provide a well-defined input format. One key solution to meet this requirements is the web-based approach to generate and display 3D representations of software structures using X3DOM.
</p>
<p>
The dependency view shows a new approach for visualizing dependencies within the hierarchical structure of the project. The analysis and subsequent classification of the input dependencies allows a fine-grained representation. This leads to a clear overview even for large-scale projects and will help to understand the structure and dependencies of software for reuse, maintenance, re-engineering and reverse engineering.
</p>
<h4>
	You now have the opportunity to try this 3d visualization tool with
	example files
	<?php 
	echo CHtml::link(' here', array('/site/page', 'view'=>'examples'));
	?>
</h4>

<p>

<table><tr>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/firefox.jpg" width="400px"></td>
<td><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/nusmv.jpg" width="400px"></td>
</tr>
</table>

<center>
<h1>Tool workflow</h1>

<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/tool_workflow.jpg" width="800px">
</center>

<p>This project started as a master thesis as the National ICT Australia
	in Sydney. Thanks to Franck Cassez and Ralf Huuck for the help during
	the thesis.</p>
	
<p>The example files are created by the Goanna tool. <a href="http://redlizards.com/">Goanna</a> (http://redlizards.com/) is a
	static program analysis tool for C/C++ software projects based on model
	checking. The technology has been developed at National ICT Australia
	(NICTA) in Sydney since 2005. Goanna can be easily integrated in the
	software development process by a seamless integration into Microsoft
	Visual Studio and the Eclipse IDE.</p>