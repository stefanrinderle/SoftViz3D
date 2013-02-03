<?php
$this->pageTitle=Yii::app()->name . ' - Thesis';
$this->breadcrumbs=array(
	'Thesis',
);
?>

<p>This project started as a master thesis at the National ICT Australia
	in Sydney (2012). Thanks to Franck Cassez and Ralf Huuck for the help during
	the thesis.</p>
	
<h3>Abstract:</h3>

<p>Software systems are complex, intangible structures, which are hard
	to understand. Therefore, visualization of software properties,
	structure and dependencies in different views play an important role
	within the lifecycle of a software system. Adding a third dimension to
	the visualization provides new opportunities by the technical progress
	of the last year. The software visualization research community has
	developed a suite of useful and promising tools. But most of them are
	not available, based on obsolete technologies or they require a huge
	effort to install.</p>
<p>This work presents a tool for 3D visualization of software structures
	and dependencies that can be integrated in the software development
	process. The main challenge was to and an appropriate representation
	and layout mechanism for hierarchical structures including
	dependencies. The analysis of the existing tools has shown that the
	visualization should be platform and language-independent, based on
	standard technologies and provide a well-defined input format. One key
	solution to meet this requirements is the web-based approach to
	generate and display 3D representations of software structures using
	X3DOM.</p>
<p>The dependency view shows a new approach for visualizing dependencies
	within the hierarchical structure of the project. The analysis and
	subsequent classification of the input dependencies allows a
	fine-grained representation. This leads to a clear overview even for
	large-scale projects and will help to understand the structure and
	dependencies of software for reuse, maintenance, re-engineering and
	reverse engineering.</p>
	
<h3><a href="<?php echo Yii::app()->request->baseUrl; ?>/protected/data/masterthesis.pdf">Master thesis download</a></h3>
	