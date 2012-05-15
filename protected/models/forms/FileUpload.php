<?php
 
class FileUpload extends CFormModel {
 
    public $dotFile;
 
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            //note you wont need a safe rule here
            array('dotFile', 'file', 'allowEmpty' => false, 'types' => 'dot, adot'),
        );
    }
 
}