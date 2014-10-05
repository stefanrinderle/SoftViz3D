<?php 

class EdgeElement extends CActiveRecord {
	
	public $id;
	public $layoutId;
	public $inputDependencyId;
	
	public $translation;
	
	public $color;
	public $lineWidth;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'LayoutEdgeElement';
	}

	public function relations() {
		return array(
				'sections'=>array(self::HAS_MANY, 'EdgeSectionElement', 'edgeId'),
				'inputDependency'=>array(self::BELONGS_TO, 'InputDependency', 'inputDependencyId')
		);
	}
	
	public function getTranslation() {
		return explode(" ", $this->translation);
	}
	
	public function saveTranslation($translation) {
		$this->translation = implode(' ', $translation);
		$this->save();
	}
	
	public static function createAndSaveEdgeElement($layoutId, $inputDependencyId, $translation, $color, $lineWidth) {
		$element = new self('insert');
		$element->layoutId = $layoutId;
		$element->inputDependencyId = $inputDependencyId;
		
		$element->translation = implode(' ', $translation);
		$element->color = implode(' ', $color);
		$element->lineWidth = $lineWidth;
	
		$element->save();
	
		return $element;
	}
}

?>