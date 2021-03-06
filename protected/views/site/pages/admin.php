<?php $this->pageTitle=Yii::app()->name; ?>

<h2>System configuration check:</h2>

<!-- DATABASE -->
<?php 
/**$filePath = Yii::app()->basePath . '/data/graph.db';
if (is_writeable($filePath)) {
	$this->renderPartial('//common/_showSuccess', array("message" =>"DB: file " . $filePath . " is writable"));
} else {
	$this->renderPartial('//common/_showError', array("message" =>"DB: file " . $filePath . " is NOT writable"));
}
**/
?>

<!-- TEMP DOT FILE -->
<?php 
$filePath = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
if (is_writeable($filePath)) {
	$this->renderPartial('//common/_showSuccess', array("message" =>"Temporary dot file: " . $filePath . " is writable"));
} else {
	$this->renderPartial('//common/_showError', array("message" =>"Temporary dot file: " . $filePath . " is NOT writable"));
}
?>

<!-- LAYOUT TEMP DOT FILE -->
<?php 
$filePath = Yii::app()->basePath . Yii::app()->params['tempDotFile'];
if (is_writeable($filePath)) {
	$this->renderPartial('//common/_showSuccess', array("message" =>"Layout dot file: " . $filePath . " is writable"));
} else {
	$this->renderPartial('//common/_showError', array("message" =>"Layout dot file: " . $filePath . " is NOT writable"));
}
?>

<!-- DOT COMMAND / GRAPHVIZ -->
<?php 
$filePath = Yii::app()->params['dotFolder'];
if (is_executable($filePath)) {
	$this->renderPartial('//common/_showSuccess', array("message" =>"Graphviz layout command: " . $filePath . " is executable"));
} else {
	$this->renderPartial('//common/_showError', array("message" =>"Graphviz layout command: " . $filePath . " is NOT executable"));
}
?>

<!-- PHP/LEXERGENERATOR -->
<?php 

// if(@require_once('PHP/LexerGenerator.php') ) {
// 	$this->renderPartial('//common/_showSuccess', array("message" =>"PHP/LexerGenerator.php: installed"));
// } else {
// 	$this->renderPartial('//common/_showError', array("message" =>"LexerGenerator: Please install --> pear install PHP_LexerGenerator"));
// }
?>