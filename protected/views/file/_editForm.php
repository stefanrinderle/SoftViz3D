<?php
return array(
    'title' => 'Edit the current dot file',
 
    'elements' => array(
        'content' => array(
            'type' => 'textarea',
        	'rows'=>30, 
        	'cols'=>80
        ),
    ),
 
    'buttons' => array(
        'reset' => array(
            'type' => 'reset',
            'label' => 'Clear',
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => 'Save',
        ),
    ),
);
?>