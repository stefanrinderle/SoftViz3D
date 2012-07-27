<?php
 
class DotFileUpload extends CFormModel {
 
    public $inputFile;
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            //note you wont need a safe rule here
            array('inputFile', 'file', 'allowEmpty' => false, 'types' => 'dot, adot'),
        );
    }
 
}