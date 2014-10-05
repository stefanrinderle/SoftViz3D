<?php
class ProjectLayoutCell extends CWidget {
 
    public $layoutType;
    public $layoutArray;
 	public $project;
    
    public function run() {
        $this->render('projectLayoutCell', array(
        			'layoutType' => $this->layoutType, 
        			'layoutArray' => $this->layoutArray,
	        		'project' => $this->project));
    }
 
}
?>