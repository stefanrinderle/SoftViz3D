<?php

class EdgeExpander extends CApplicationComponent
{
	
	private $flatEdges = array();
	private $dependenyNodes = array();
	private $dependencyEdges = array();
	
	private $nodesCounter = array(); 
	
	public function execute($edges) {
		foreach ($edges as $edge) {
			if ($edge->out_parent_id == $edge->in_parent_id) {
				$edge->parent_id = $edge->out_parent_id;
				array_push($this->flatEdges, $edge);
				
				$this->incrementNodesCounter($edge->out_id);
				$this->incrementNodesCounter($edge->in_id);
			} else {
				$out = $edge->outElement;
				$in = $edge->inElement;
				// search for the common ancestor
				$this->expandEdge($out, $in);
			}
		}
		
		//OPTIMIZE WITH UPDATE
		foreach($this->nodesCounter as $key => $value) {
			$leaf = TreeElement::model()->findByPk($key);
			$leaf->counter = $value;
			$leaf->save();
		}
		
		print_r("count: " . count($this->flatEdges) . " <br />");
		// dont show flat edges
// 		foreach ($this->flatEdges as $edge) {
// 			$edge->save();
// 		}
		foreach ($this->dependenyNodes as $node) {
			$node->save();
		}
		foreach ($this->dependencyEdges as $edge) {
			$edge->save();
		}
	}
	
	private function incrementNodesCounter($nodeId) {
		if ($this->nodesCounter[$nodeId]) {
			$this->nodesCounter[$nodeId] = $this->nodesCounter[$nodeId] + 1;
		} else {
			$this->nodesCounter[$nodeId] = 1;
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
			$node = LeafElement::create($depNodeLabel, $depNodeLabel, $parentId, $level);
			$node->save();
			$this->dependenyNodes[$depNodeLabel] = $node;
			
			$depNodeId = $node->id;
		}
		
		return $depNodeId;
	}
}