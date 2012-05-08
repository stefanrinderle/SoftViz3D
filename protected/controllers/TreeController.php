<?php

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public function actionIndex()
	{
		/* directory */
		$path = "/Users/stefan/Sites/3dArch/yii/";
		$outputFile = '/Users/stefan/Sites/3dArch/x3d/parser.dot';
		
		Yii::app()->directoryToDotParser->parse($path, $outputFile);
		
// 		$fileContent = file($outputFile);
		
// 		$this->render('directory', array(fileContent=>$fileContent, fileName=>$outputFile));
		
		
		
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');
		
		$connection=Yii::app()->db;
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		$result = Yii::app()->dotFileParser->parse($outputFile);
		
		Yii::app()->dotArrayParser->parse($result);
		
// 		Yii::app()->dotParser->parse($this->sourceFile);
		
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
		
		$layout = new LayoutVisitor();
		$root = TreeElement::model()->findByPk(1);
		$root->accept($layout);
		
		print_r(memory_get_usage() / 8 / 1024 . "<br />");
		print_r(memory_get_peak_usage() / 8 / 1024 . "<br />");
		
		$this->render('index', array(tree=>$root));
// 		$this->render('default');
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
