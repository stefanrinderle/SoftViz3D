<?php

class TreeController extends Controller
{
	private $sourceFile = '/Users/stefan/Sites/3dArch/x3d/dependency.dot';
	
	public function actionIndex()
	{
		//Yii::log("bla", 'error', 'parser');
		//Yii::log($this->actualLine, 'error', 'parser');
	
		$connection=Yii::app()->db;
		TreeElement::model()->deleteAll();
		EdgeElement::model()->deleteAll();
		
		Yii::app()->dotParser->parse($this->sourceFile);
		
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
		
		$this->render('index', array(tree=>$root));
	}
	
	public function expandEdge($source, $dest) {
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {
	
				// TODO: check if theres already an dependeny node
				$depNodeId = TreeElement::createAndSaveTreeElement("dependency_" . $dest->parent_id, $source->parent_id, $source->level);
				EdgeElement::createAndSaveEdgeElement($label, $source->id, $depNodeId, $source->parent_id);
				
				$source = $source->parent;
			} else {
				//array_push($edges, new Edge("dEdge", $dest->label, $dest->parent->label));
	
				// TODO: check if theres already an dependeny node
				$depNodeId = TreeElement::createAndSaveTreeElement("dependency_" . $dest->parent_id, $dest->parent_id, $dest->level);
				EdgeElement::createAndSaveEdgeElement("dependency", $depNodeId, $dest->id, $dest->parent_id);
				
				$dest = $dest->parent;
			}
		}
	
		// TODO creating of edges here not tested yet
		//compute till both have the same parent
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {

				$depNodeId = TreeElement::createAndSaveTreeElement("dependency_" . $dest->parent_id, $source->parent_id, $source->level);
				EdgeElement::createAndSaveEdgeElement($label, $source->id, $depNodeId, $source->parent_id);
				
				$source = $source->parent;
			} else {
	
				$depNodeId = TreeElement::createAndSaveTreeElement("dependency_" . $dest->parent_id, $dest->parent_id, $dest->level);
				EdgeElement::createAndSaveEdgeElement("dependency", $depNodeId, $dest->id, $dest->parent_id);
				
				$dest = $dest->parent;
			}
		}
		
		EdgeElement::createAndSaveEdgeElement("dependency", $source->id, $dest->id, $dest->parent_id);
		
	}
}
