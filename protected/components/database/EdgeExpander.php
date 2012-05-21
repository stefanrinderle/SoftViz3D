<?php

class EdgeExpander extends CApplicationComponent
{
	
	public function execute() {
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
	}
	
	private function expandEdge($source, $dest) {
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
	
	private function getDependencyNode($parentId, $level) {
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
}