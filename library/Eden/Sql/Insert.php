<?php //-->
/*
 * This file is part of the Utility package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Sql;

/**
 * Generates insert query string syntax
 *
 * @vendor Eden
 * @package Sql
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Insert extends Query 
{
	protected $_setKey = array();
    protected $_setVal = array();
	
	/**
	 * Construct: Set the table, if any
	 *
	 * @param string|null
	 */
	public function __construct($table = null) 
	{
		//Argument 1 must be a string or null
		Argument::i()->test(1, 'string', 'null');
		
		if(is_string($table)) {
			$this->setTable($table);
		}
	}
	
	
	/**
	 * Returns the string version of the query 
	 *
	 * @param bool
	 * @return string
	 */
	public function getQuery() 
	{
		$multiValList = array();
		foreach($this->_setVal as $val) {
			$multiValList[] = '('.implode(', ', $val).')';
		}
		
		return 'INSERT INTO '
			. $this->_table . ' ('.implode(', ', $this->_setKey)
			. ') VALUES ' . implode(", \n", $multiValList).';';
	}
	
	/**
	 * Set clause that assigns a given field name to a given value.
	 * You can also use this to add multiple rows in one call
	 *
	 * @param string
	 * @param string
	 * @return Eden\Sql\Insert
	 */
	public function set($key, $value, $index = 0) 
	{
		//argument test
		Argument::i()
			//Argument 1 must be a string
			->test(1, 'string')				
			//Argument 2 must be scalar or null
			->test(2, 'scalar', 'null');	
		
		if(!in_array($key, $this->_setKey)) {
			$this->_setKey[] = $key;
		}
		
		if(is_null($value)) {
			$value = 'null';
		} else if(is_bool($value)) {
			$value = $value ? 1 : 0;
		}
		
		$this->_setVal[$index][] = $value;
		return $this;
	}
	
	/**
	 * Set the table name in which you want to delete from
	 *
	 * @param string name
	 * @return Eden\Sql\Insert
	 */
	public function setTable($table) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->_table = $table;
		return $this;
	}	
}