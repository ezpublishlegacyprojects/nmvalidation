INSTALLATION

1. Put in extension folder and activate.


USAGE

In your PHP code, do something like:

include_once( "extension/nmvalidation/classes/validation.php" );

$data = array(	'first_name' 	=> 'Eirik', 
				'last_name'		=> '', 
				'username'		=> 'eirik');

// set up validation
// for available validation rules, see validation.php
$validationRules = array(	'first_name' 	=> array('not_empty'), 
							'last_name' 	=> array('not_empty'), 
							'username' 		=> array('not_empty', 'username')
							);
	
// perform general validation
$validation = new validation($data, $validationRules);

$validationSucceeded 	= $validation->valid;
$validationMessages 	= $validation->msgs;