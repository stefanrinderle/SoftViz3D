<?php 

class BoxElement extends CActiveRecord {
	
	public $id;
	
	public $layoutId;
	
	// format: [x],[y],[z]
	public $translation;
	
	// format: [r],[g],[b]
	// float between 0 and 1
	public $color;
	
	// float between 0 and 1
	public $transparency;
	
	/**
	 * 2D rectangle only width and length
	 * format: [width],[length]
	 * others width, length, height
	 * format: [width],[length],[height]
	 */
	public $size;
	
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'LayoutBoxElement';
	}

	public function relations() {
		return array(
		);
	}
	
	/**
	 * @param integer $layoutId
	 * @param array $translation
	 * @param array $color
	 * @param float $transprency
	 * @return BoxElement
	 */
	public static function createAndSaveBoxElement($layoutId, $translation, $size, $color, $transparency) {
		$element = new self('insert');
		$element->layoutId = $layoutId;
	
		$element->translation = implode(',', $translation);
		$element->size = implode(',', $size);
		$element->color = implode(',', $color);
		$element->transparency = $transparency;
	
		$element->save();
		
		return $element;
	}
	
}

?>