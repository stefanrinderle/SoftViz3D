<?php

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	function getTime()
	{
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function actionIndex()
	{
		/* reset database */
		$connection=Yii::app()->db;
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		// STEP 1: Create an input dot file (string)
		
		/* directory */
		$path = "/Users/stefan/Sites/3darch/protected";
		
		/* Parse to a file to view the result */
		$outputFile = '/Users/stefan/Sites/3dArch/x3d/parser.dot';
		Yii::app()->directoryToDotParser->parseToFile($path, $outputFile);
		$result = Yii::app()->dotFileParser->parseFile($outputFile);
		
		/* parse to string in memory */
// 		$dotString = Yii::app()->directoryToDotParser->parseToDotString($path);
// 		$result = Yii::app()->dotFileParser->parseString($dotString);
		
		// STEP 2: Write parsed data into database
		
		$start = $this->getTime();
		Yii::app()->dotInfoToDb->writeToDb($result);
		
// 		print_r("write to db: " . number_format(($this->getTime() - $start),2) . "<br />");
		
		// STEP 3: Normalize edges
		
		$edges = EdgeElement::model()->findAll();
		
		foreach ($edges as $edge) {
			// are the nodes of the edge in the same layer?
			$out = $edge->outElement;
			$in = $edge->inElement;
			if ($out->parent_id == $in->parent_id) {
				$edge->parent_id = $out->parent_id;
				$edge->save();
			} else {
				// search for the common ancestor
				$this->expandEdge($out, $in);
				$edge->delete();
			}
		}
		
		// STEP 4: calculate the view layout
		
		$layout = new LayoutVisitor();
		$root = TreeElement::model()->findByPk(1);
		$root->accept($layout);
		
		// STEP 5: show the calculated layout
		$this->render('index', array(tree=>$root));
	}
	
	public function getDependencyNode($parentId, $level) {
		$depPrefix = "dep_";
		$depNodeLabel = $depPrefix . $parentId;
	
		//TODO dont retrieve the whole object, just the id
		$depNode = TreeElement::model()->findByAttributes(array('parent_id'=>$parentId, 'label'=>$depNodeLabel));
		if (!$depNode) {
			$depNodeId = TreeElement::createAndSaveLeafTreeElement($depNodeLabel, $parentId, $level);
		} else {
			$depNodeId = $depNode->id;
		}
	
		return $depNodeId;
	}
	
	public function expandEdge($source, $dest) {
		$depEdgeLabel = "depEdge";
		
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {

				$depNodeId = $this->getDependencyNode($source->parent_id, $source->level);
				EdgeElement::createAndSaveEdgeElement($depEdgeLabel, $source->id, $depNodeId, $source->parent_id);
				
				$source = $source->parent;
			} else {
				
				$depNodeId = $this->getDependencyNode($dest->parent_id, $dest->level);
				EdgeElement::createAndSaveEdgeElement($depEdgeLabel, $depNodeId, $dest->id, $dest->parent_id);
				
				$dest = $dest->parent;
			}
		}
	
		//compute till both have the same parent
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {

				$depNodeId = $this->getDependencyNode($source->parent_id, $source->level);
				EdgeElement::createAndSaveEdgeElement($depEdgeLabel, $source->id, $depNodeId, $source->parent_id);
				
				$source = $source->parent;
			} else {
	
				$depNodeId = $this->getDependencyNode($dest->parent_id, $dest->level);
				EdgeElement::createAndSaveEdgeElement($depEdgeLabel, $depNodeId, $dest->id, $dest->parent_id);
				
				$dest = $dest->parent;
			}
		}
		
		EdgeElement::createAndSaveEdgeElement($depEdgeLabel, $source->id, $dest->id, $dest->parent_id);
		
	}
}
