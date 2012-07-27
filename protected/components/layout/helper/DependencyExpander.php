<?php

class DependencyExpander extends CApplicationComponent {
	
	private $flatEdges = array();
	private $dependenyNodes = array();
	private $dependencyEdges = array();
	
	private $nodesCounter = array(); 
	
	private $projectId;
	
	public function execute($projectId) {
		$this->projectId = $projectId;
		
		// remove old dependency path edges
		// no more needed but secure is secure :-)
		InputDependency::model()->deleteAllByAttributes(array('projectId' => $projectId, 'type' => InputDependency::$TYPE_PATH));
		
		$edges = InputDependency::model()->findAllByAttributes(array('projectId' => $projectId));
		
		foreach ($edges as $edge) {
			if ($edge->type == InputDependency::$TYPE_INPUT_FLAT) {
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
			$leaf = InputTreeElement::model()->findByPk($key);
			$leaf->counter = $value;
			$leaf->save();
		}
		
		foreach ($edges as $edge) {
			$edge->save();
		}
		
		//print_r("count_flat: " . count($this->flatEdges) . " <br />");
		//print_r("count_dep: " . count($this->dependencyEdges) . " <br />");
		
		//foreach ($this->flatEdges as $edge) {
		//	$edge->save();
		//}
		foreach ($this->dependenyNodes as $node) {
			$node->save();
		}
		foreach ($this->dependencyEdges as $edge) {
			$edge->save();
		}
	}
	
	private function incrementNodesCounter($nodeId) {
		if (array_key_exists($nodeId, $this->nodesCounter)) {
			$this->nodesCounter[$nodeId] = $this->nodesCounter[$nodeId] + 1;
		} else {
			$this->nodesCounter[$nodeId] = 1;
		}
	}
	
	private function expandEdge($source, $dest) {
		while ($source->parentId != $dest->parentId) {
			if ($source->level > $dest->level) {
				$this->handleNewDepEdge($source, "in");
				$source = $source->parent;
			} else {
				$this->handleNewDepEdge($dest, "out");
				$dest = $dest->parent;
			}
		}
	
		//compute till both have the same parent
		while ($source->parentId != $dest->parentId) {
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
		
		if (array_key_exists($depEdgeLabel, $this->dependencyEdges)) {
			$edge = $this->dependencyEdges[$depEdgeLabel];
			$edge->counter++;
		} else {
			$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $source->id, $dest->id, $source->parentId);
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
	}
	
	private function handleNewDepEdge($node, $type) {
		$depEdgeLabel = "depEdge_" . $type . "_" . $node->id;
	
		
		if (array_key_exists($depEdgeLabel, $this->dependencyEdges)) {
			$edge = $this->dependencyEdges[$depEdgeLabel];
			$edge->counter++;
		} else {
			$depNodeId = $this->getDependencyNode($node->parentId, $node->level);
	
			if ($type == "out") {
				$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $depNodeId, $node->id, $node->parentId);
			} else {
				$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $node->id, $depNodeId, $node->parentId);
			}
	
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
	}
	
	private function getDependencyNode($parentId, $level) {
		$depPrefix = "dep_";
		$depNodeLabel = $depPrefix . $parentId;
	
		if (array_key_exists($depNodeLabel, $this->dependenyNodes)) {
			$savedNode = $this->dependenyNodes[$depNodeLabel];
			$savedNode->counter++;
			$depNodeId = $savedNode->id;
		} else {
			$node = InputLeaf::create($this->projectId, $depNodeLabel, $depNodeLabel, $parentId, $level);
			$node->save();
			$this->dependenyNodes[$depNodeLabel] = $node;
			
			$depNodeId = $node->id;
		}
		
		return $depNodeId;
	}
}