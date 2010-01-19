<?php

class validation
{
	function validation($data, $validationRules)
	{
        $this->valid = true;
        $this->msgs = array();

        // for each for field
        foreach($validationRules as $fieldname => $rules)
        {
            // for each rule
            foreach($rules as $rule)
            {
            	// if the rule is an array of data
            	if(is_array($rule))
            	{
 					// format data
 					$newRule = $rule[0];
            		$ruleData = $rule;
 					$rule = $newRule;
            	}
            	
                if($rule == "not_empty")
                {
                    if($data[$fieldname] == '')
                    {
                        $this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide a value.' );
                    }
                }

                if($rule == "choose_item_not_zero")
                {
                    if($data[$fieldname] == '0')
                    {
                        $this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please select an option.' );
                    }
                }

                if($rule == "date")
				{
			        if(!ereg ("([0-9]{2}).([0-9]{2}).([0-9]{4})", $data[$fieldname]))
			        {
		            	$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide a valid date in the syntax of DD.MM.YYYY' );
			        }
				}
								
				if($rule == 'domain_syntax')
				{
					if(!$this->validDomain($data[$fieldname]))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide a valid domain name in the form of "domain.com". If your domain name contains norwegian characters, please convert your domain name to ACE.' );
					}
					
					if($this->startsWithWWW($data[$fieldname]))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide just the domain name, without the "www".' );
					}
				}
				
				if($rule == 'username_syntax')
				{
					if(!$this->validUsername($data[$fieldname]))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide a valid username. The username must have between 2 and 8 characters, and only contain lowercase letters from the english alphabet. The username could not start with "test"' );
					}
				}
				
				if($rule == 'email_syntax')
				{
					if(!$this->validEmail($data[$fieldname]))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'Please provide a valid email address.' );
					}
				}
				
				if($rule == 'numbers_only')
				{
					$noSpaces = str_replace(" ", "", $data[$fieldname]);
					if($noSpaces != '' AND !is_numeric($noSpaces))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'This field can only contain numbers.' );
					}
				}
				
				if($rule == 'must_equal')
				{
					$secondFieldName = $ruleData[1];
					if($data[$fieldname] != $data[$secondFieldName])
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'This field must match the field ' ) . $ruleData[2];
					}
				}
				
				if($rule == 'email_not_taken_by_ez_user')
				{
					if(eZUser::fetchByEmail($data[$fieldname]))
					{
						$this->valid = false;
                        $this->msgs[$fieldname][] = ezi18n( 'validate', 'There is already a user account registered with this email address.' );
					}
				}
            }
        }
	}
	
	function validUsername( $username )
	{
	  if(preg_match('/^[a-z0-9_]{2,8}$/', $username)) {
	  	
	  	if ( $this->startsWithTest( $username ) )
	  	{
	  		return false;
	  	}
	    return true;
	  }
	  else
	  {
		  return false;
	  }
	}
	
	function validEmail($email)
	{
	  $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	  $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	  $atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
	                  '\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	  $quoted_pair = '\\x5c[\\x00-\\x7f]';
	  $domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
	  $quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
	  $domain_ref = $atom;
	  $sub_domain = "($domain_ref|$domain_literal)";
	  $word = "($atom|$quoted_string)";
	  $domain = "$sub_domain(\\x2e$sub_domain)*";
	  $local_part = "$word(\\x2e$word)*";
	  $addr_spec = "$local_part\\x40$domain";
	
	  return preg_match("!^$addr_spec$!", $email) ? true : false;
	}

	function startsWithWWW($domain)
	{
		if(!ereg("^www\.", $domain))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function startsWithTest($username)
	{
		
		$check = substr( $username, 0, 4);

		if( $check == 'test' )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function validDomain($domain)
	{
		// if(!ereg("^[^ ]+\.[^ ]+$", $domain)) // reg ex v. 1 - allowed norwegian characters in domain name
		if(!ereg("^[a-zA-Z0-9_-]+\.[^ ]+$", $domain)) // reg ex. v. 2 - allows sub domains
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

?>
