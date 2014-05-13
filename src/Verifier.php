<?php
namespace ej3dev\Veritas;

/**
 * Veritas is a pragmatic and concise validation library written in PHP
 * Package and docs at {@link https://github.com/ej3dev/Veritas}
 * 
 * @author Emilio José Jiménez <ej3dev@gmail.com>
 * @copyright Copyright (c) 2014 Emilio José Jiménez
 * @license http://opensource.org/licenses/MIT MIT License
 * @version v0.5.0
 */
class Verifier {
    
    //--------------------------------------------------------------------------
    // Properties
    //
    private $test;
    private $data;
    private $dataType;
    
    //--------------------------------------------------------------------------
    // Constructor
    //
    /**
     * Private constructor: new Verifier instances must be created with static is*() methods
     * 
     * @param mixed $data value to validate
     */
    private function __construct($data) {
        $this->test = true;
        $this->data = $data;
        $this->dataType = strtolower(gettype($data));
    }
    
    //--------------------------------------------------------------------------
    // Fluent interface: initializer & finisher
    //
    /**
     * Generic validator that always verifies as true
     * 
     * @param mixed $data value to validate
     * @return \ej3dev\Veritas\Verifier
     */
    public static function is($data) {
        return (new Verifier($data));
    }
    
    /**
     * Validate the data against defined rules. Can be called with or without params:
     * <pre>
     * <code>verify()</code> return <code>true|false</code>
     * <code>verify($onTrue)</code> return <code>$onTrue</code> if data loaded verify the rules or <code>false</code> in other case
     * <code>verify($onTrue,$onFalse)</code> return <code>$onTrue</code> if data loaded verify the rules or <code>$onFalse</code> in other case
     * </pre>
     * 
     * @return mixed Return:
     * <pre>
     * true|false when called without parameters
     * mixed|false when called with one parameter
     * mixed when called with two parameters
     * </pre>
     */
    public function verify() {
        $params = func_get_args();
        switch( count($params) ) {
            case 0: return ($this->test ? true : false);
            case 1: return ($this->test ? $params[0] : false);
            case 2: return ($this->test ? $params[0] : $params[1]);
        }
        return $this->test;
    }
    
    //--------------------------------------------------------------------------
    // Build-in validators
    //
    public static function isEmail($data) {
        return (new Verifier($data))->filter(FILTER_VALIDATE_EMAIL);
    }
    
    public static function isUrl($data) {
        return (new Verifier($data))->filter(FILTER_VALIDATE_URL);
    }
    
    public static function isIp($data) {
        return (new Verifier($data))->filter(FILTER_VALIDATE_IP);
    }
    
    public static function isNull($data) {
        return (new Verifier($data))->eq(null,true);
    }
    
    public static function isNotNull($data) {
        return (new Verifier($data))->notEq(null,true);
    }
    
    //--------------------------------------------------------------------------
    // Types
    //
    /**
     * Checks a boolean value
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function boo() {
        if( $this->test == false ) return $this;
        
        $test = is_bool($this->data);
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks a integer value
     * 
     * @param boolean $strict Enable/disable strict mode to validate also variable type
     * @return \ej3dev\Veritas\Verifier
     */
    public function int($strict=false) {
        if( $this->test == false ) return $this;
        
        $test = (is_numeric($this->data) && (int)$this->data == $this->data);
        if( $strict ) $test &= ($this->dataType == 'integer');
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks a numeric value. Integers and floats are numeric values
     * 
     * @param boolean $strict enable/disable strict mode to validate also variable type
     * @return \ej3dev\Veritas\Verifier
     */
    public function num($strict=false) {
        if( $this->test == false ) return $this;
        
        $test = is_float(filter_var($this->data,FILTER_VALIDATE_FLOAT));
        if( $strict ) $test = ($this->dataType == 'integer' || $this->dataType == 'double');
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks a string value
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function str() {
        if( $this->test == false ) return $this;
        
        $test = is_string($this->data);
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is an array
     * 
     * @return \ej3dev\Veritas\Verifier 
     */
    public function arr() {
        if( $this->test == false ) return $this;
        
        $test = (is_array($this->data) || (
            $this->data instanceof \ArrayAccess
            && $this->data instanceof \Traversable
            && $this->data instanceof \Countable
        ));
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is an object and if it's instance of the class <code>$instance</code>
     * 
     * @param string $instance class name of the object
     * @return \ej3dev\Veritas\Verifier 
     */
    public function obj($instance=null) {
        if( $this->test == false ) return $this;
        
        $test = is_object($this->data);
        if( $test && !is_null($instance) ) $test &= ($this->data instanceof $instance);
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is a resource and if it's of resource type <code>$type</code>
     * 
     * @param string $type string representing of the resource type
     * @return \ej3dev\Veritas\Verifier 
     */
    public function res($type=null) {
        if( $this->test == false ) return $this;
        
        $test = is_resource($this->data);
        if( $test && !is_null($type) ) $test &= (strtolower($type) == strtolower(get_resource_type($this->data)) );
        
        $this->test &= $test;
        return $this;
    }
    
    //--------------------------------------------------------------------------
    // Rules
    //
    /**
     * Checks conditions about variable length. If the variable is an array, length 
     * is the numbers of elements that contains.
     * <br>Applicable only for <code>integer|double|string|array</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * 
     * @param string $operator a comparison operator between: <code>=</code>,<code>==</code>,<code>==</code>,<code>!=</code>,<code>&lt;</code>,<code>&lt;=</code>,<code>&gt;</code>,<code>&gt;=</code>
     * @param int    $value    value to compare 
     * @return \ej3dev\Veritas\Verifier
     * @throws \ErrorException when parameter <code>$value</code> isn't an integer
     */
    public function len($operator,$value) {
        if( !is_int($value) ) throw new \ErrorException('Verifier->len() invalid parameter: $value must be an integer');
        if( $this->test == false ) return $this;
        if( stripos('integer|double|string|array',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $len = ($this->dataType == 'array' ? count($this->data) : strlen(strval($this->data)));
        switch( trim($operator) ) {
            case '=':
            case '==':
                $test = ($len == $value);
                break;
            case '===': $test = ($len === $value); break;
            case '!=': $test = ($len != $value); break;
            case '<': $test = ($len < $value); break;
            case '<=': $test = ($len <= $value); break;
            case '>': $test = ($len > $value); break;
            case '>=': $test = ($len >= $value); break;
        }
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is equal to <code>$value</code>
     * 
     * @param mixed   $value  value to compare with
     * @param boolean $strict enable/disable strict mode to validate also variable type
     * @return \ej3dev\Veritas\Verifier
     */
    public function eq($value,$strict=false) {
        if( $this->test == false ) return $this;
        
        $test = ($strict ? $this->data === $value : $this->data == $value);
        
        $this->test &= $test;
        return $this;
    }
    
    public function notEq($value,$strict=false) {
        if( $this->test == false ) return $this;
        
        $test = ($strict ? $this->data !== $value : $this->data != $value);
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable verifies the inequation defined by the operator <code>$operator</code> 
     * and the right side value <code>$value</code>
     * <br>Applicable only for <code>integer|double|numeric-string</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * 
     * @param string $operator inequation operator between: <code>&lt;</code>,<code>&lt;=</code>,<code>&gt;</code>,<code>&gt;=</code>
     * @param int    $value    value to compare 
     * @return \ej3dev\Veritas\Verifier
     * @throws \ErrorException when parameter <code>$value</code> isn't a numeric value
     */
    public function ineq($operator,$value) {
        if( !is_numeric($value) ) throw new \ErrorException('Verifier->ineq() invalid parameter: $value must be a numeric value');
        if( $this->test == false ) return $this;
        if( stripos('integer|double|string',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        if( $this->dataType == 'string' && !is_numeric($this->data) ) {
            $this->test = false;
            return $this;
        }
        
        $test = is_numeric($value);
        $value = floatval($value);
        switch( trim($operator) ) {
            case '<': $test &= ($this->data < $value); break;
            case '<=': $test &= ($this->data <= $value); break;
            case '>': $test &= ($this->data > $value); break;
            case '>=': $test &= ($this->data >= $value); break;
            default:
                $test = false;
        }
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is inside a numeric interval or in a list of values. 
     * String variables can only be checked against a list of values.
     * <br>Applicable only for <code>integer|double|string</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * <br>This method can be called with a variable number of parameters:
     * <pre>
     * <code>in($interval)</code> where <code>$interval</code> is a string that defines a interval. For example: <code>[-1,1]</code>, <code>(0,10)</code> or <code>[2.618,3.142)</code>
     * <code>in($p1,$p2...)</code> where <code>$p1,$p2...</code> is a list with a variable number of values
     * <code>in($list)</code> where <code>$list</code> is an array with a list of values
     * </pre>
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function in() {
        if( $this->test == false ) return $this;
        if( stripos('integer|double|string',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        switch( $this->dataType ) {
            case 'integer':
            case 'double':
                if( count($params) == 1 && is_string($params[0]) ) {
                    $opeLeft = $params[0][0];
                    $opeRight = $params[0][strlen($params[0])-1];
                    $values = explode(',',substr($params[0],1,-1));
                    sort($values);
                    switch( $opeLeft ) {
                        case '[': $test = ($values[0] <= $this->data); break;
                        case '(': $test = ($values[0] < $this->data); break;
                    }
                    switch( $opeRight ) {
                        case ']': $test &= ($this->data <= $values[1]); break;
                        case ')': $test &= ($this->data < $values[1]); break;
                    }
                    
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    $test = (array_search($this->data,$params[0]) !== false);
                } else {
                    $test = (array_search($this->data,$params) !== false);
                }
                break;
                
            case 'string':
                if( count($params) == 1 && is_string($params[0]) ) {
                    $test = (stripos($params[0],$this->data) !== false);
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    $test = (array_search($this->data,$params[0]) !== false);
                } else {
                    $test = (array_search($this->data,$params) !== false);
                }
                break;
        }
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable is outside a numeric interval or outside a list of values. 
     * String variables can only be checked against a list of values.
     * <br>Applicable only for <code>integer|double|string</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * <br>This method can be called with a variable number of parameters:
     * <pre>
     * <code>out($interval)</code> where <code>$interval</code> is a string that defines a interval. For example: <code>[-1,1]</code>, <code>(0,10)</code> or <code>[2.618,3.142)</code>
     * <code>out($p1,$p2...)</code> where <code>$p1,$p2...</code> is a list with a variable number of values
     * <code>out($list)</code> where <code>$list</code> is an array with a list of values
     * </pre>
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function out() {
        if( $this->test == false ) return $this;
        if( stripos('integer|double|string',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        switch( $this->dataType ) {
            case 'integer':
            case 'double':
                if( count($params) == 1 && is_string($params[0]) ) {
                    $opeLeft = $params[0][0];
                    $opeRight = $params[0][strlen($params[0])-1];
                    $values = explode(',',substr($params[0],1,-1));
                    sort($values);
                    switch( $opeLeft ) {
                        case '[': $test = ($this->data < $values[0]); break;
                        case '(': $test = ($this->data <= $values[0]); break;
                    }
                    switch( $opeRight ) {
                        case ']': $test |= ($values[1] < $this->data); break;
                        case ')': $test |= ($values[1] <= $this->data); break;
                    }
                    
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    $test = (array_search($this->data,$params[0]) === false);
                } else {
                    $test = (array_search($this->data,$params) === false);
                }
                break;
                
            case 'string':
                if( count($params) == 1 && is_string($params[0]) ) {
                    $test = (stripos($params[0],$this->data) === false);
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    $test = (array_search($this->data,$params[0]) === false);
                } else {
                    $test = (array_search($this->data,$params) === false);
                }
                break;
        }
        
        $this->test &= $test;
        return $this;
    }
    
    /**
     * Checks if a variable contains one value or all values of a list. 
     * For arrays this method checks the elements of the array against the given values
     * <br>Applicable only for <code>string|array</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * <br>This method can be called with a variable number of parameters:
     * <pre>
     * <code>contain($p)</code> where <code>$p</code> is an integer or a string
     * <code>contain($p1,$p2...)</code> where <code>$p1,$p2...</code> is a list with a variable number of values
     * <code>contain($list)</code> where <code>$list</code> is an array with a list of values
     * </pre>
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function contain() {
        if( $this->test == false ) return $this;
        if( stripos('string|array',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        switch( $this->dataType ) {
            case 'string':
                $test = true;
                if( count($params) == 1 && is_string($params[0]) ) {
                    $test = (strpos($this->data,$params[0]) !== false);
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    foreach($params[0] as $val) $test &= (strpos($this->data,$val) !== false);
                } else {
                    foreach($params as $val) $test &= (strpos($this->data,$val) !== false);
                }
                break;
                
            case 'array':
                $test = true;
                if( count($params) == 1 && is_array($params[0]) ) {
                    foreach($params[0] as $val) $test &= (array_search($val,$this->data) !== false);
                } elseif( count($params) == 1 ) {
                    $test = (array_search($params[0],$this->data) !== false);
                } else {
                    foreach($params as $val) $test &= (array_search($val,$this->data) !== false);
                }
                break;
        }
        
        $this->test &= $test;
        return $this;        
    }
    
    public function containAny() {
        //TODO
        //Igual que contain pero no es necesario que contenga TODOS, con uno vale
        //EL parámetro pasado siempre debe ser una lista o un array
    }
    
    /**
     * Checks if a variable doesn't contain one value or at least one of the values of a list. 
     * For arrays this method checks the elements of the array against the given values
     * <br>Applicable only for <code>string|array</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * <br>This method can be called with a variable number of parameters:
     * <pre>
     * <code>without($p)</code> where <code>$p</code> is an integer or a string
     * <code>without($p1,$p2...)</code> where <code>$p1,$p2...</code> is a list with a variable number of values
     * <code>without($list)</code> where <code>$list</code> is an array with a list of values
     * </pre>
     * 
     * @return \ej3dev\Veritas\Verifier
     */
    public function without() {
        if( $this->test == false ) return $this;
        if( stripos('string|array',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        switch( $this->dataType ) {
            case 'string':
                $test = false;
                if( count($params) == 1 && is_string($params[0]) ) {
                    $test = (strpos($this->data,$params[0]) === false);
                } elseif( count($params) == 1 && is_array($params[0]) ) {
                    foreach($params[0] as $val) $test |= (strpos($this->data,$val) === false);
                } else {
                    foreach($params as $val) $test |= (strpos($this->data,$val) === false);
                }
                break;
                
            case 'array':
                $test = false;
                if( count($params) == 1 && is_array($params[0]) ) {
                    foreach($params[0] as $val) $test |= (array_search($val,$this->data) === false);
                } elseif( count($params) == 1 ) {
                    $test = (array_search($params[0],$this->data) === false);
                } else {
                    foreach($params as $val) $test |= (array_search($val,$this->data) === false);
                }
                break;
        }
        
        $this->test &= $test;
        return $this;        
    }
    
    /**
     * Checks if a variable is an object of type <code>DataTime</code> or is a 
     * string that represent a date/time formatted as <code>$format</code>
     * <br>See available tokens to define format at {@link http://php.net/manual/en/function.date.php}
     * <br>Applicable only for <code>string|object</code> variables. 
     * For other variable types this rule verifies as <code>false</code>
     * 
     * @param string $format date/time format. For example: "Y-m-d H:i:s" or "dmY"
     * @return \ej3dev\Veritas\Verifier
     * @throws \ErrorException when parameter <code>$format</code> isn't a string
     */
    public function date($format=null) {
        if( $this->test == false ) return $this;
        if( stripos('string|object',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        switch( $this->dataType ) {
            case 'string':
                if( is_null($format) ) {
                    $test = (strtotime($this->data) !== false);
                } elseif( !is_string($format) ) {
                    throw new \ErrorException('Verifier->date() invalid parameter: $format must be a string');
                } else {
                    $dateFromFormat = \DateTime::createFromFormat($format,$this->data);
                    $test = ($dateFromFormat && $this->data === date($format,$dateFromFormat->getTimestamp()) );
                }
                break;
            
            case 'object':
                $test = ($this->data instanceof \DateTime);
                break;
        }
        
        $this->test &= $test;
        return $this;
    }
    
    public function value($value,$strict=false) {
        if( $this->test == false ) return $this;
        if( stripos('array|object',$this->dataType) === false ) {
            $this->test = false;
            return $this;
        }
        
        $test = (array_search($value,(array)$this->data,$strict) !== false);
        
        $this->test &= $test;
        return $this;
    }
    
    public function key() {
        if( func_num_args() == 0 ) throw new \ErrorException('Verifier->key() invalid number of parameters: key() must have at least 1 parameter');
        if( $this->test == false ) return $this;
        if( $this->dataType != 'array' ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        if( count($params) == 1 ) {
            $test = array_key_exists($params[0],$this->data);
        } else {
            $key = array_shift($params);
            $test = $this->key($key)->verify();
            if( $test && count($params) > 0 ) $test &= (array_search($this->data[$key],$params) !== false);
        }
        
        $this->test &= $test;
        return $this;
    }
    
    public function attr() {
        if( func_num_args() == 0 ) throw new \ErrorException('Verifier->attr() invalid number of parameters: attr() must have at least 1 parameter');
        if( $this->test == false ) return $this;
        if( $this->dataType != 'object' ) {
            $this->test = false;
            return $this;
        }
        
        $test = false;
        $params = func_get_args();
        if( count($params) == 1 ) {
            $test = is_string($params[0]);
            $test &= (property_exists(get_class($this->data),$params[0]) || !empty($this->data->{$params[0]}) );
        } else {
            $key = array_shift($params);
            $test = self::is($this->data)->attr($key)->verify();
            if( $test && count($params) > 0 ) $test &= (array_search($this->data->{$key},$params) !== false);
        }
        
        $this->test &= $test;
        return $this;
    }
    
    public function filter($filter) {
        if( !is_int($filter) ) throw new \ErrorException('Verifier->filter() invalid parameter: $filter must be a integer');
        
        $test = (filter_var($this->data,$filter) !== false);
        
        $this->test &= $test;
        return $this;
    }
    
    public function regex($pattern) {
        if( !is_string($pattern) ) throw new \ErrorException('Verifier->regex() invalid parameter: $pattern must be a string');
        if( $this->test == false ) return $this;
        if( $this->dataType != 'string' ) {
            $this->test = false;
            return $this;
        }
        
        $test = preg_match($pattern,$this->data);
        
        $this->test &= $test;
        return $this;
    }
    
    //--------------------------------------------------------------------------
    // Helper methods
    //
    
}