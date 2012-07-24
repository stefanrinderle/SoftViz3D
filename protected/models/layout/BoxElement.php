<?php 

class BoxElement extends CActiveRecord {
	
	public static $TYPE_PLATFORM = 0;
	public static $TYPE_FOOTPRINT = 1;
	public static $TYPE_NODE = 2;
	
	public $id;
	public $layoutId;
	
	public $inputTreeElementId;
	
	// 0: platform
	// 1: footprint
	// 2: node
	public $type;
	
	// format: [x] [y] [z]
	public $translation;
	
	// format: [r] [g] [b]
	// float between 0 and 1
	public $color;
	
	// float between 0 and 1
	public $transparency;
	
	/**
	 * 2D rectangle only width and length
	 * format: [width] [length]
	 * others width, length, height
	 * format: [width] [length] [height]
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
				'inputTreeElement'=>array(self::BELONGS_TO, 'InputTreeElement', 'inputTreeElementId')
		);
	}
	
	public function saveTranslation($translation) {
		$this->translation = implode(' ', $translation);
		$this->save();
	}

	/**
	 * @param integer $layoutId
	 * @param array $translation
	 * @param array $color
	 * @param float $transprency
	 * @return BoxElement
	 */
	public static function createAndSaveBoxElement($layoutId, $inputTreeElementId, $type, $translation, $size, $color, $transparency) {
		$element = new self('insert');
		$element->layoutId = $layoutId;
		$element->inputTreeElementId = $inputTreeElementId;
	
		$element->type = $type;
		
		$element->translation = implode(' ', $translation);
		$element->size = implode(' ', $size);
		$element->color = implode(' ', $color);
		$element->transparency = $transparency;
	
		$element->save();
		
		return $element;
	}
	
}

?>