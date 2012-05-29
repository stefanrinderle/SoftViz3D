<?php

class EdgeExpander extends CApplicationComponent
{
	
	private $dependenyNodes = array();
	private $dependencyEdges = array();
	
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
		foreach ($this->dependencyEdges as $edge) {
			$edge->save();
		}
	}
	
	private function expandEdge($source, $dest) {
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {
				$this->handleNewDepEdge($source, "in");
				$source = $source->parent;
			} else {
				$this->handleNewDepEdge($dest, "out");
				$dest = $dest->parent;
			}
		}
	
		//compute till both have the same parent
		while ($source->parent_id != $dest->parent_id) {
			if ($source->level > $dest->level) {
				$this->handleNewDepEdge($source, "in");
				$source = $source->parent;
			} else {
				$this->handleNewDepEdge($dest, "out");
				$dest = $dest->parent;
			}
		}
	
		$this->handleNewFlatDepEdge($source, $dest);
	}
	
	private function handleNewFlatDepEdge($source, $dest) {
		$type = "out";
		$depEdgeLabel = "depEdge_" . $type . "_" . $source->id;
		
		$edge = $this->dependencyEdges[$depEdgeLabel];
		if ($edge) {
			$edge->counter++;
		} else {
			$element = EdgeElement::createEdgeElement($depEdgeLabel, $source->id, $dest->id, $source->parent_id);
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
	}
	
	private function handleNewDepEdge($node, $type) {
		$depEdgeLabel = "depEdge_" . $type . "_" . $node->id;
	
		$edge = $this->dependencyEdges[$depEdgeLabel];
		if ($edge) {
			$edge->counter++;
		} else {
			$depNodeId = $this->getDependencyNode($node->parent_id, $node->level);
	
			if ($type == "out") {
				$element = EdgeElement::createEdgeElement($depEdgeLabel, $depNodeId, $node->id, $node->parent_id);
			} else {
				$element = EdgeElement::createEdgeElement($depEdgeLabel, $node->id, $depNodeId, $node->parent_id);
			}
	
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
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