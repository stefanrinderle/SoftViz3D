<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<h2>System configuration check:</h2>

<!-- DATABASE -->
<?php 
$filePath = Yii::app()->basePath . '/data/graph.db';
if (is_writeable($filePath)) {
	$this->renderPartial('//main/_showSuccess', array(message=>"DB: file " . $filePath . " is writable"));
} else {
	$this->renderPartial('//main/_showError', array(message=>"DB: file " . $filePath . " is NOT writable"));
}
?>

<!-- TEMP DOT FILE -->
<?php 
$filePath = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
if (is_writeable($filePath)) {
	$this->renderPartial('//main/_showSuccess', array(message=>"Temporary dot file: " . $filePath . " is writable"));
} else {
	$this->renderPartial('//main/_showError', array(message=>"Temporary dot file: " . $filePath . " is NOT writable"));
}
?>

<!-- DOT COMMAND / GRAPHVIZ -->
<?php 
$filePath = '/usr/local/bin/dot';
if (is_executable($filePath)) {
	$this->renderPartial('//main/_showSuccess', array(message=>"Graphviz layout command: " . $filePath . " is executable"));
} else {
	$this->renderPartial('//main/_showError', array(message=>"Graphviz layout command: " . $filePath . " is NOT executable"));
}
?>

<!-- PHP/LEXERGENERATOR -->
<?php 
if(@require_once('PHP/LexerGenerator.php') ) {
	$this->renderPartial('//main/_showSuccess', array(message=>"PHP/LexerGenerator.php: installed"));
} else {
	$this->renderPartial('//main/_showError', array(message=>"LexerGenerator: Please install --> pear install PHP_LexerGenerator"));
}
?>