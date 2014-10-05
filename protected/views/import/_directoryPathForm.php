<?php
return array(
    'title' => 'Enter directory path (on server)',
 
    'elements' => array(
        'path' => array(
            'type' => 'text',
        	'size' => 80
        ),
    	'includeDot' => array(
    		'type' => 'checkbox'
    	),
    ),
 
    'buttons' => array(
        'reset' => array(
            'type' => 'reset',
            'label' => 'Reset',
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => 'Submit',
        ),
    ),
);
?>