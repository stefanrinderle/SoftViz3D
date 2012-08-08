<?php

class DependencyExpander extends CApplicationComponent {
	
	private static $INTERACE_PREFIX = "interface_";
	private static $DEP_PATH_EDGE_PREFIX = "depPath_";
	
	private $flatEdges = array();
	private $interfaceLeaves = array();
	private $dependencyEdges = array();
	
	private $nodesCounter = array(); 
	
	private $projectId;
	
	//TEST:
	public function getTime() {
		$a = explode (' ',microtime());
		return(double) $a[0] + $a[1];
	}
	
	public function getTimeDifference($start) {
		return $this->getTime() - $start;
	}
	//!TEST
	
	public function execute($projectId) {
		$this->projectId = $projectId;
		
		// remove old dependency path edges - not needed but secure is secure :-)
		InputDependency::model()->deleteAllByAttributes(array('projectId' => $projectId, 'type' => InputDependency::$TYPE_PATH));
		
		$dependencies = InputDependency::model()->findAllByAttributes(array('projectId' => $projectId));
		
		foreach ($dependencies as $dep) {
			if ($dep->type == InputDependency::$TYPE_INPUT_FLAT) {
				$this->incrementNodesCounter($dep->out_id);
				$this->incrementNodesCounter($dep->in_id);
			} else {
				$out = $dep->outElement;
				$in = $dep->inElement;
				// search for the common ancestor
				$this->createDependencyPath($out, $in);
			}
		}
		
		foreach($this->nodesCounter as $key => $value) {
			$leaf = InputTreeElement::model()->findByPk($key);
			$leaf->counter = $value;
			$leaf->save();
		}
		
		foreach ($this->interfaceLeaves as $node) {
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
	
	private function createDependencyPath($source, $dest) {
		while ($source->parentId != $dest->parentId) {
			if ($source->level > $dest->level) {
				$this->handleNewDepEdge($source, false);
				$source = $source->parent;
			} else {
				$this->handleNewDepEdge($dest, true);
				$dest = $dest->parent;
			}
		}
	
		//compute till both have the same parent
		while ($source->parentId != $dest->parentId) {
			if ($source->level > $dest->level) {
				$this->handleNewDepEdge($source, false);
				$source = $source->parent;
			} else {
				$this->handleNewDepEdge($dest, true);
				$dest = $dest->parent;
			}
		}
	
		$this->handleNewFlatDepEdge($source, $dest);
	}
	
	private function handleNewFlatDepEdge($source, $dest) {
		$depEdgeLabel = DependencyExpander::$DEP_PATH_EDGE_PREFIX . "_" . $source->id;
		
		if (array_key_exists($depEdgeLabel, $this->dependencyEdges)) {
			$edge = $this->dependencyEdges[$depEdgeLabel];
			$edge->counter++;
		} else {
			$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $source->id, $dest->id, $source->parentId);
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
	}
	
	private function handleNewDepEdge($node, $isOut) {
		$depEdgeLabel = DependencyExpander::$DEP_PATH_EDGE_PREFIX . "_" . $node->id;
	
		if (array_key_exists($depEdgeLabel, $this->dependencyEdges)) {
			$edge = $this->dependencyEdges[$depEdgeLabel];
			$edge->counter++;
		} else {
			$depNodeId = $this->getInterfaceNode($node->parentId, $node->level);
	
			if ($isOut) {
				$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $depNodeId, $node->id, $node->parentId);
			} else {
				$element = InputDependency::createInputDependency($this->projectId, $depEdgeLabel, $node->id, $depNodeId, $node->parentId);
			}
	
			$this->dependencyEdges[$depEdgeLabel] = $element;
		}
	}
	
	private function getInterfaceNode($parentId, $level) {
		$intLeafLabel = DependencyExpander::$INTERACE_PREFIX . $parentId;
	
		if (array_key_exists($intLeafLabel, $this->interfaceLeaves)) {
			$savedLeaf = $this->interfaceLeaves[$intLeafLabel];
			$savedLeaf->counter++;
			$intNodeId = $savedLeaf->id;
		} else {
			$node = InputLeaf::createAndSave(
							$this->projectId, 
							InputTreeElement::$TYPE_LEAF_INTERFACE, 
							$intLeafLabel, $intLeafLabel, 
							$parentId, $level);
							
			$this->interfaceLeaves[$intLeafLabel] = $node;
			
			$intNodeId = $node->id;
		}
		
		return $intNodeId;
	}
}