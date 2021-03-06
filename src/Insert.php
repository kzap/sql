<?php //-->
/*
 * This file is part of the Sql package of the Eden PHP Library.
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
 * @package sql
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Insert extends Query
{
    protected $setKey = array();
    protected $setVal = array();
    
    /**
     * Construct: Set the table, if any
     *
     * @param string|null
     */
    public function __construct($table = null)
    {
        //Argument 1 must be a string or null
        Argument::i()->test(1, 'string', 'null');
        
        if (is_string($table)) {
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
        foreach ($this->setVal as $val) {
            $multiValList[] = '('.implode(', ', $val).')';
        }
        
        return 'INSERT INTO '
            . $this->table . ' ('.implode(', ', $this->setKey)
            . ') VALUES ' . implode(", \n", $multiValList).';';
    }
    
    /**
     * Set clause that assigns a given field name to a given value.
     * You can also use this to add multiple rows in one call
     *
     * @param string
     * @param string
     * @return this
     */
    public function set($key, $value, $index = 0)
    {
        //argument test
        Argument::i()
            //Argument 1 must be a string
            ->test(1, 'string')
            //Argument 2 must be scalar or null
            ->test(2, 'scalar', 'null');
        
        if (!in_array($key, $this->setKey)) {
            $this->setKey[] = $key;
        }
        
        if (is_null($value)) {
            $value = 'null';
        } else if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        
        $this->setVal[$index][] = $value;
        return $this;
    }
    
    /**
     * Set the table name in which you want to delete from
     *
     * @param string name
     * @return this
     */
    public function setTable($table)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->table = $table;
        return $this;
    }
}
