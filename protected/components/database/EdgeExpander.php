<?php

class EdgeExpander extends CApplicationComponent
{
	
	private $dependenyNodes = array();
	
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
		
		foreach ($this->dependenyNodes as $node) {
			$node->save();
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
	
		$savedNode = $this->dependenyNodes[$depNodeLabel];
		if ($savedNode) {
			$savedNode->counter++;
			$depNodeId = $savedNode->id;
		} else {
			$node = TreeElement::createLeafTreeElement($depNodeLabel, $parentId, $level);
			$node->save();
			$this->dependenyNodes[$depNodeLabel] = $node;
			
			$depNodeId = $node->id;
		}
		
		return $depNodeId;
	}
}