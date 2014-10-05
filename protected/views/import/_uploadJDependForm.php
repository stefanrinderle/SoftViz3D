<?php
return array(
    'title' => 'Upload your JDepend XML file',
 
    'attributes' => array(
        'enctype' => 'multipart/form-data',
    ),
 
    'elements' => array(
        'inputFile' => array(
            'type' => 'file',
        	'size' => 65
        ),
    ),
 
    'buttons' => array(
        'reset' => array(
            'type' => 'reset',
            'label' => 'Reset',
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => 'Upload',
        ),
    ),
);
?>