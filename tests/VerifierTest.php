<?php
namespace ej3dev\Veritas;

include_once(__DIR__ . '/../src/Verifier.php');
use ej3dev\Veritas\Verifier as v;

/**
 * PHPUnit tests for <code>ej3dev\Veritas\Verifier.php</code>
 * 
 * @author Emilio José Jiménez <ej3dev@gmail.com>
 * @copyright Copyright (c) 2014 Emilio José Jiménez
 * @license http://opensource.org/licenses/MIT MIT License
 */
class VerifierTest extends \PHPUnit_Framework_TestCase {
    
    //--------------------------------------------------------------------------
    // Build-in validators
    //
    public function testBuildIn() {
        //Email
        $this->assertTrue( v::isEmail('name@domain.com')->verify() );
        $this->assertFalse( v::isEmail('name at domain.com')->verify() );
        
        //URL
        $this->assertTrue( v::isUrl('https://www.domain.com')->verify() );
        $this->assertTrue( v::isUrl('http://www.domain.com')->verify() );
        $this->assertTrue( v::isUrl('ftp://domain.com')->verify() );
        $this->assertFalse( v::isUrl('www.domain.com')->verify() );
        
        //IP
        $this->assertTrue( v::isIp('192.168.0.1')->verify() );
        $this->assertFalse( v::isIp('localhost')->verify() );
        
        //Null
        $this->assertTrue( v::isNull(null)->verify() );
        $this->assertFalse( v::isNull('notNull')->verify() );
        
        //Not null
        $this->assertTrue( v::isNotNull('notNull')->verify() );
        $this->assertFalse( v::isNotNull(null)->verify() );
        
        //Empty
        $this->assertTrue( v::isEmpty("")->verify() );
        $this->assertTrue( v::isEmpty(0)->verify() );
        $this->assertTrue( v::isEmpty(0.0)->verify() );
        $this->assertTrue( v::isEmpty("0")->verify() );
        $this->assertTrue( v::isEmpty(null)->verify() );
        $this->assertTrue( v::isEmpty(false)->verify() );
        $this->assertTrue( v::isEmpty(array())->verify() );
        
        //Not empty
        $this->assertFalse( v::isNotEmpty("")->verify() );
        $this->assertFalse( v::isNotEmpty(0)->verify() );
        $this->assertFalse( v::isNotEmpty(0.0)->verify() );
        $this->assertFalse( v::isNotEmpty("0")->verify() );
        $this->assertFalse( v::isNotEmpty(null)->verify() );
        $this->assertFalse( v::isNotEmpty(false)->verify() );
        $this->assertFalse( v::isNotEmpty(array())->verify() );
    }
    
    //--------------------------------------------------------------------------
    // Types
    //
    public function testBoo() {
        $this->assertTrue( v::is(true)->boo()->verify() );
        $this->assertTrue( v::is(false)->boo()->verify() );
        $this->assertFalse( v::is(1)->boo()->verify() );
        $this->assertFalse( v::is("1")->boo()->verify() );
        $this->assertFalse( v::is("abc")->boo()->verify() );
        $this->assertFalse( v::is(array())->boo()->verify() );
        $this->assertFalse( v::is(0)->boo()->verify() );
        $this->assertFalse( v::is("0")->boo()->verify() );
        $this->assertFalse( v::is("")->boo()->verify() );
        $this->assertFalse( v::is(null)->boo()->verify() );
    }
    
    public function testNotBoo() {
        $this->assertFalse( v::is(true)->notBoo()->verify() );
        $this->assertFalse( v::is(false)->notBoo()->verify() );
        $this->assertTrue( v::is(1)->notBoo()->verify() );
        $this->assertTrue( v::is("1")->notBoo()->verify() );
        $this->assertTrue( v::is("abc")->notBoo()->verify() );
        $this->assertTrue( v::is(array())->notBoo()->verify() );
        $this->assertTrue( v::is(0)->notBoo()->verify() );
        $this->assertTrue( v::is("0")->notBoo()->verify() );
        $this->assertTrue( v::is("")->notBoo()->verify() );
        $this->assertTrue( v::is(null)->notBoo()->verify() );
    }
    
    public function testInt() {
        //Normal
        $this->assertTrue( v::is(-0xff)->int()->verify() );
        $this->assertTrue( v::is(-123)->int()->verify() );
        $this->assertTrue( v::is(1.0)->int()->verify() );
        $this->assertTrue( v::is("-123")->int()->verify() );
        $this->assertFalse( v::is(3.14)->int()->verify() );
        $this->assertFalse( v::is("3.14")->int()->verify() );
        $this->assertFalse( v::is("0xff")->int()->verify() );
        
        //Strict
        $this->assertTrue( v::is(-0xff)->int(true)->verify() );
        $this->assertTrue( v::is(-123)->int(true)->verify() );
        $this->assertFalse( v::is(1.0)->int(true)->verify() );
        $this->assertFalse( v::is("-123")->int(true)->verify() );
        $this->assertFalse( v::is(3.14)->int(true)->verify() );
        $this->assertFalse( v::is("3.14")->int(true)->verify() );
        $this->assertFalse( v::is("0xff")->int(true)->verify() );
    }
    
    public function testNotInt() {
        //Normal
        $this->assertFalse( v::is(-0xff)->notInt()->verify() );
        $this->assertFalse( v::is(-123)->notInt()->verify() );
        $this->assertFalse( v::is(1.0)->notInt()->verify() );
        $this->assertFalse( v::is("-123")->notInt()->verify() );
        $this->assertTrue( v::is(3.14)->notInt()->verify() );
        $this->assertTrue( v::is("3.14")->notInt()->verify() );
        $this->assertTrue( v::is("0xff")->notInt()->verify() );
        
        //Strict
        $this->assertFalse( v::is(-0xff)->notInt(true)->verify() );
        $this->assertFalse( v::is(-123)->notInt(true)->verify() );
        $this->assertTrue( v::is(1.0)->notInt(true)->verify() );
        $this->assertTrue( v::is("-123")->notInt(true)->verify() );
        $this->assertTrue( v::is(3.14)->notInt(true)->verify() );
        $this->assertTrue( v::is("3.14")->notInt(true)->verify() );
        $this->assertTrue( v::is("0xff")->notInt(true)->verify() );
    }
    
    public function testDec() {
        //Normal
        $this->assertTrue( v::is(3.14)->dec()->verify() );
        $this->assertTrue( v::is("3.14")->dec()->verify() );
        $this->assertFalse( v::is(1.0)->dec()->verify() );
        $this->assertFalse( v::is(321)->dec()->verify() );
        $this->assertFalse( v::is("654")->dec()->verify() );
        $this->assertFalse( v::is("abc")->dec()->verify() );
        
        //Strict
        $this->assertTrue( v::is(3.14)->dec(true)->verify() );
        $this->assertFalse( v::is("3.14")->dec(true)->verify() );
        $this->assertFalse( v::is(1.0)->dec(true)->verify() );
        $this->assertFalse( v::is(321)->dec(true)->verify() );
        $this->assertFalse( v::is("654")->dec(true)->verify() );
        $this->assertFalse( v::is("abc")->dec(true)->verify() );
    }
    
    public function testNotDec() {
        //Normal
        $this->assertFalse( v::is(3.14)->notDec()->verify() );
        $this->assertFalse( v::is("3.14")->notDec()->verify() );
        $this->assertTrue( v::is(1.0)->notDec()->verify() );
        $this->assertTrue( v::is(321)->notDec()->verify() );
        $this->assertTrue( v::is("654")->notDec()->verify() );
        $this->assertTrue( v::is("abc")->notDec()->verify() );
        
        //Strict
        $this->assertFalse( v::is(3.14)->notDec(true)->verify() );
        $this->assertTrue( v::is("3.14")->notDec(true)->verify() );
        $this->assertTrue( v::is(1.0)->notDec(true)->verify() );
        $this->assertTrue( v::is(321)->notDec(true)->verify() );
        $this->assertTrue( v::is("654")->notDec(true)->verify() );
        $this->assertTrue( v::is("abc")->notDec(true)->verify() );
    }
    
    public function testNum() {
        //Normal
        $this->assertTrue( v::is(0xff)->num()->verify() );
        $this->assertTrue( v::is(1234)->num()->verify() );
        $this->assertTrue( v::is(3.14)->num()->verify() );
        $this->assertTrue( v::is("-1234")->num()->verify() );
        $this->assertTrue( v::is("-3.14")->num()->verify() );
        $this->assertFalse( v::is("abc")->num()->verify() );
        $this->assertFalse( v::is(array())->num()->verify() );
        
        //Strict
        $this->assertTrue( v::is(0xff)->num(true)->verify() );
        $this->assertTrue( v::is(1234)->num(true)->verify() );
        $this->assertTrue( v::is(3.14)->num(true)->verify() );
        $this->assertFalse( v::is("-1234")->num(true)->verify() );
        $this->assertFalse( v::is("-3.14")->num(true)->verify() );
        $this->assertFalse( v::is("abc")->num(true)->verify() );
        $this->assertFalse( v::is(array())->num(true)->verify() );
    }
    
    public function testNotNum() {
        //Normal
        $this->assertFalse( v::is(0xff)->notNum()->verify() );
        $this->assertFalse( v::is(1234)->notNum()->verify() );
        $this->assertFalse( v::is(3.14)->notNum()->verify() );
        $this->assertFalse( v::is("-1234")->notNum()->verify() );
        $this->assertFalse( v::is("-3.14")->notNum()->verify() );
        $this->assertTrue( v::is("abc")->notNum()->verify() );
        $this->assertTrue( v::is(array())->notNum()->verify() );
        
        //Strict
        $this->assertFalse( v::is(0xff)->notNum(true)->verify() );
        $this->assertFalse( v::is(1234)->notNum(true)->verify() );
        $this->assertFalse( v::is(3.14)->notNum(true)->verify() );
        $this->assertTrue( v::is("-1234")->notNum(true)->verify() );
        $this->assertTrue( v::is("-3.14")->notNum(true)->verify() );
        $this->assertTrue( v::is("abc")->notNum(true)->verify() );
        $this->assertTrue( v::is(array())->notNum(true)->verify() );
    }
    
    public function testStr() {
        $this->assertTrue( v::is("abc")->str()->verify() );
        $this->assertTrue( v::is("true")->str()->verify() );
        $this->assertTrue( v::is("null")->str()->verify() );
        $this->assertTrue( v::is("0xff")->str()->verify() );
        $this->assertTrue( v::is("123")->str()->verify() );
        $this->assertFalse( v::is(123)->str()->verify() );
        $this->assertFalse( v::is(true)->str()->verify() );
        $this->assertFalse( v::is(array())->str()->verify() );
        $this->assertFalse( v::is(array(1,'two'))->str()->verify() );
        $this->assertFalse( v::is(null)->str()->verify() );
    }
    
    public function testNotStr() {
        $this->assertFalse( v::is("abc")->notStr()->verify() );
        $this->assertFalse( v::is("true")->notStr()->verify() );
        $this->assertFalse( v::is("null")->notStr()->verify() );
        $this->assertFalse( v::is("0xff")->notStr()->verify() );
        $this->assertFalse( v::is("123")->notStr()->verify() );
        $this->assertTrue( v::is(123)->notStr()->verify() );
        $this->assertTrue( v::is(true)->notStr()->verify() );
        $this->assertTrue( v::is(array())->notStr()->verify() );
        $this->assertTrue( v::is(array(1,'two'))->notStr()->verify() );
        $this->assertTrue( v::is(null)->notStr()->verify() );
    }
    
    public function testArr() {
        $this->assertTrue( v::is(array())->arr()->verify() );
        $this->assertTrue( v::is(array(1,2,3))->arr()->verify() );
        $this->assertTrue( v::is(array('uno' => 'one','dos' => 'two'))->arr()->verify() );
        $this->assertTrue( v::is(array('uno' => 'one',2,3 => 'three'))->arr()->verify() );
        $this->assertFalse( v::is("123")->arr()->verify() );
        $this->assertFalse( v::is(123)->arr()->verify() );
        $this->assertFalse( v::is(true)->arr()->verify() );
        $this->assertFalse( v::is(null)->arr()->verify() );
    }
    
    public function testNotArr() {
        $this->assertFalse( v::is(array())->notArr()->verify() );
        $this->assertFalse( v::is(array(1,2,3))->notArr()->verify() );
        $this->assertFalse( v::is(array('uno' => 'one','dos' => 'two'))->notArr()->verify() );
        $this->assertFalse( v::is(array('uno' => 'one',2,3 => 'three'))->notArr()->verify() );
        $this->assertTrue( v::is("123")->notArr()->verify() );
        $this->assertTrue( v::is(123)->notArr()->verify() );
        $this->assertTrue( v::is(true)->notArr()->verify() );
        $this->assertTrue( v::is(null)->notArr()->verify() );
    }
    
    public function testObj() {
        $object = \DateTime::createFromFormat('U',  time());
        
        $this->assertTrue( v::is($object)->obj()->verify() );
        $this->assertTrue( v::is($object)->obj('DateTime')->verify() );
        $this->assertFalse( v::is($object)->obj('stdClass')->verify() );
        $this->assertFalse( v::is("123")->obj()->verify() );
        $this->assertFalse( v::is(123)->obj()->verify() );
        $this->assertFalse( v::is(true)->obj()->verify() );
        $this->assertFalse( v::is(array())->obj()->verify() );
        $this->assertFalse( v::is(array(1,'two'))->obj()->verify() );        
        $this->assertFalse( v::is(null)->obj()->verify() );
    }
    
    public function testNotObj() {
        $object = \DateTime::createFromFormat('U',  time());
        
        $this->assertFalse( v::is($object)->notObj()->verify() );
        $this->assertFalse( v::is($object)->notObj('DateTime')->verify() );
        $this->assertTrue( v::is($object)->notObj('stdClass')->verify() );
        $this->assertTrue( v::is("123")->notObj()->verify() );
        $this->assertTrue( v::is(123)->notObj()->verify() );
        $this->assertTrue( v::is(true)->notObj()->verify() );
        $this->assertTrue( v::is(array())->notObj()->verify() );
        $this->assertTrue( v::is(array(1,'two'))->notObj()->verify() );        
        $this->assertTrue( v::is(null)->notObj()->verify() );
    }
    
    //--------------------------------------------------------------------------
    // Rules
    //
    public function testLenException() {
        $this->setExpectedException('ErrorException');
        v::is('abc')->len('<=',3.14)->verify();
    }
    
    public function testLen() {
        //Integer & float
        $this->assertTrue( v::is(-8)->len('==',2)->verify() );
        $this->assertTrue( v::is(3.1416)->len('<=',6)->len('>=',6)->verify() );
        $this->assertFalse( v::is(-8)->len('==',1)->verify() );
        $this->assertFalse( v::is(3.1416)->len('!=',6)->verify() );
        
        //String
        $this->assertTrue( v::is('abcd')->len('=',4)->verify() );
        $this->assertTrue( v::is('abcd')->len('>',2)->len('<',5)->verify() );
        $this->assertFalse( v::is('abcd')->len('<=',3)->verify() );
        $this->assertFalse( v::is('abcd')->len('!=',4)->verify() );
        
        //Array
        $this->assertTrue( v::is(array(1,2,3))->len('>',0)->len('<=',3)->verify() );
        $this->assertFalse( v::is(array())->len('==',1)->verify() );
    }
    
    public function testNotLenException() {
        $this->setExpectedException('ErrorException');
        v::is('abc')->notLen('<=',3.14)->verify();
    }
    
    public function testNotLen() {
        //Integer & float
        $this->assertFalse( v::is(-8)->notLen('==',2)->verify() );
        $this->assertFalse( v::is(3.1416)->notLen('<=',6)->notLen('>=',6)->verify() );
        $this->assertTrue( v::is(-8)->notLen('==',1)->verify() );
        $this->assertTrue( v::is(3.1416)->notLen('!=',6)->verify() );
        
        //String
        $this->assertFalse( v::is('abcd')->notLen('=',4)->verify() );
        $this->assertFalse( v::is('abcd')->notLen('>',2)->len('<',5)->verify() );
        $this->assertTrue( v::is('abcd')->notLen('<=',3)->verify() );
        $this->assertTrue( v::is('abcd')->notLen('!=',4)->verify() );
        
        //Array
        $this->assertFalse( v::is(array(1,2,3))->notLen('>',0)->len('<=',3)->verify() );
        $this->assertTrue( v::is(array())->notLen('==',1)->verify() );
    }
    
    public function testEq() {
        //Boolean
        $this->assertTrue( v::is(true)->eq(true)->verify() );
        $this->assertTrue( v::is(true)->eq('abc')->verify() );
        $this->assertTrue( v::is(true)->eq(42)->verify() );
        $this->assertTrue( v::is(false)->eq(0)->verify() );
        $this->assertFalse( v::is(true)->eq('abc',true)->verify() );
        $this->assertFalse( v::is(true)->eq(42,true)->verify() );
        $this->assertFalse( v::is(false)->eq(0,true)->verify() );
        
        //Integer
        $this->assertTrue( v::is(32)->eq(32)->verify() );
        $this->assertFalse( v::is(32)->eq(33)->verify() );
        $this->assertTrue( v::is(32)->eq('32')->verify() );
        $this->assertFalse( v::is(32)->eq('32',true)->verify() );
        $this->assertTrue( v::is(32)->eq(true)->verify() );
        $this->assertFalse( v::is(32)->eq(true,true)->verify() );
        $this->assertTrue( v::is(0)->eq(false)->verify() );
        $this->assertFalse( v::is(0)->eq(false,true)->verify() );
        
        //Float
        $this->assertTrue( v::is(3.14)->eq(3.14)->verify() );
        $this->assertFalse( v::is(3.14)->eq(3.15)->verify() );
        $this->assertTrue( v::is(3.14)->eq('3.14')->verify() );
        $this->assertFalse( v::is(3.14)->eq('3.14',true)->verify() );
        $this->assertTrue( v::is(3.14)->eq(true)->verify() );
        $this->assertFalse( v::is(3.14)->eq(true,true)->verify() );
        $this->assertTrue( v::is(0.0)->eq(false)->verify() );
        $this->assertFalse( v::is(0.0)->eq(false,true)->verify() );
        
        //String
        $this->assertTrue( v::is('3.14')->eq(3.14)->verify() );
        $this->assertFalse( v::is('3.14')->eq(3.14,true)->verify() );
        $this->assertTrue( v::is('abc')->eq('abc')->verify() );
        $this->assertFalse( v::is('abc')->eq('aBc')->verify() );
        $this->assertTrue( v::is('abc')->eq(true)->verify() );
        $this->assertFalse( v::is('abc')->eq(true,true)->verify() );
        $this->assertTrue( v::is('0')->eq(false)->verify() );
        $this->assertFalse( v::is('0')->eq(false,true)->verify() );
        $this->assertTrue( v::is('')->eq(false)->verify() );
        $this->assertFalse( v::is('')->eq(false,true)->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertTrue( v::is($arr)->eq($arr)->verify() );
        $this->assertFalse( v::is($arr)->eq(array('one'=>'uno'))->verify() );
        $this->assertFalse( v::is(array('uno'))->eq(array('Uno'))->verify() );
        $this->assertTrue( v::is($arr)->eq(true)->verify() );
        $this->assertFalse( v::is($arr)->eq(true,true)->verify() );
        $this->assertTrue( v::is(array())->eq(false)->verify() );
        $this->assertFalse( v::is(array())->eq(false,true)->verify() );
        
        //Object
        $obj = (object)array('one'=>'uno','two'=>2);
        $this->assertTrue( v::is($obj)->eq($obj)->verify() );
        $this->assertFalse( v::is($obj)->eq(new \stdClass)->verify() );
        $this->assertTrue( v::is($obj)->eq(true)->verify() );
        $this->assertFalse( v::is($obj)->eq(true,true)->verify() );
    }
    
    public function testNotEq() {
        //Boolean
        $this->assertFalse( v::is(true)->NotEq(true)->verify() );
        $this->assertFalse( v::is(true)->NotEq('abc')->verify() );
        $this->assertFalse( v::is(true)->NotEq(42)->verify() );
        $this->assertFalse( v::is(false)->NotEq(0)->verify() );
        $this->assertTrue( v::is(true)->NotEq('abc',true)->verify() );
        $this->assertTrue( v::is(true)->NotEq(42,true)->verify() );
        $this->assertTrue( v::is(false)->NotEq(0,true)->verify() );
        
        //Integer
        $this->assertFalse( v::is(32)->NotEq(32)->verify() );
        $this->assertTrue( v::is(32)->NotEq(33)->verify() );
        $this->assertFalse( v::is(32)->NotEq('32')->verify() );
        $this->assertTrue( v::is(32)->NotEq('32',true)->verify() );
        $this->assertFalse( v::is(32)->NotEq(true)->verify() );
        $this->assertTrue( v::is(32)->NotEq(true,true)->verify() );
        $this->assertFalse( v::is(0)->NotEq(false)->verify() );
        $this->assertTrue( v::is(0)->NotEq(false,true)->verify() );
        
        //Float
        $this->assertFalse( v::is(3.14)->NotEq(3.14)->verify() );
        $this->assertTrue( v::is(3.14)->NotEq(3.15)->verify() );
        $this->assertFalse( v::is(3.14)->NotEq('3.14')->verify() );
        $this->assertTrue( v::is(3.14)->NotEq('3.14',true)->verify() );
        $this->assertFalse( v::is(3.14)->NotEq(true)->verify() );
        $this->assertTrue( v::is(3.14)->NotEq(true,true)->verify() );
        $this->assertFalse( v::is(0.0)->NotEq(false)->verify() );
        $this->assertTrue( v::is(0.0)->NotEq(false,true)->verify() );
        
        //String
        $this->assertFalse( v::is('3.14')->NotEq(3.14)->verify() );
        $this->assertTrue( v::is('3.14')->NotEq(3.14,true)->verify() );
        $this->assertFalse( v::is('abc')->NotEq('abc')->verify() );
        $this->assertTrue( v::is('abc')->NotEq('aBc')->verify() );
        $this->assertFalse( v::is('abc')->NotEq(true)->verify() );
        $this->assertTrue( v::is('abc')->NotEq(true,true)->verify() );
        $this->assertFalse( v::is('0')->NotEq(false)->verify() );
        $this->assertTrue( v::is('0')->NotEq(false,true)->verify() );
        $this->assertFalse( v::is('')->NotEq(false)->verify() );
        $this->assertTrue( v::is('')->NotEq(false,true)->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertFalse( v::is($arr)->NotEq($arr)->verify() );
        $this->assertTrue( v::is($arr)->NotEq(array('one'=>'uno'))->verify() );
        $this->assertTrue( v::is(array('uno'))->NotEq(array('Uno'))->verify() );
        $this->assertFalse( v::is($arr)->NotEq(true)->verify() );
        $this->assertTrue( v::is($arr)->NotEq(true,true)->verify() );
        $this->assertFalse( v::is(array())->NotEq(false)->verify() );
        $this->assertTrue( v::is(array())->NotEq(false,true)->verify() );
        
        //Object
        $obj = (object)array('one'=>'uno','two'=>2);
        $this->assertFalse( v::is($obj)->NotEq($obj)->verify() );
        $this->assertTrue( v::is($obj)->NotEq(new \stdClass)->verify() );
        $this->assertFalse( v::is($obj)->NotEq(true)->verify() );
        $this->assertTrue( v::is($obj)->NotEq(true,true)->verify() );        
    }
    
    public function testIneqException() {
        $this->setExpectedException('ErrorException');
        v::is(8)->ineq('<','nine')->verify();
    }
    
    public function testIneq() {
        $this->assertTrue( v::is(1)->ineq('<',2)->verify() );
        $this->assertFalse( v::is(1)->ineq('>',2)->verify() );
        $this->assertTrue( v::is(3.14)->ineq('>=',3.0)->verify() );
        $this->assertFalse( v::is(3.14)->ineq('<=',3.0)->verify() );
        $this->assertFalse( v::is(33)->ineq('equal',33)->verify() );
    }
    
    public function testNotIneqException() {
        $this->setExpectedException('ErrorException');
        v::is(8)->notIneq('<','nine')->verify();
    }
    
    public function testNotIneq() {
        $this->assertFalse( v::is(1)->notIneq('<',2)->verify() );
        $this->assertTrue( v::is(1)->notIneq('>',2)->verify() );
        $this->assertFalse( v::is(3.14)->notIneq('>=',3.0)->verify() );
        $this->assertTrue( v::is(3.14)->notIneq('<=',3.0)->verify() );
        $this->assertTrue( v::is(33)->notIneq('equal',33)->verify() );
    }
    
    public function testIn() {
        //Integer
        $this->assertTrue( v::is(8)->in(2,4,6,8)->verify() );
        $this->assertFalse( v::is(5)->in(2,4,6,8)->verify() );
        $this->assertTrue( v::is(3)->in('(-2,3]')->verify() );
        $this->assertFalse( v::is(4)->in('[1,4)')->verify() );
        
        //Float
        $this->assertTrue( v::is(3.14)->in(1,3.14,2.618)->verify() );
        $this->assertFalse( v::is(2.618)->in(0,1.1,2,3.3)->verify() );
        $this->assertTrue( v::is(3.14)->in('[0,3.15)')->verify() );
        $this->assertFalse( v::is(2.618)->in('[2.62,3.14)')->verify() );
        
        //String
        $this->assertTrue( v::is('wor')->in('Hello world!')->verify() );
        $this->assertFalse( v::is('bye')->in('Hello world!')->verify() );
        $this->assertTrue( v::is('two')->in('one','two','three')->verify() );
        $this->assertFalse( v::is('four')->in('one','two','three')->verify() );
    }
    
    public function testNotIn() {
        //Integer
        $this->assertFalse( v::is(8)->notIn(2,4,6,8)->verify() );
        $this->assertTrue( v::is(5)->notIn(2,4,6,8)->verify() );
        $this->assertFalse( v::is(3)->notIn('(-2,3]')->verify() );
        $this->assertTrue( v::is(4)->notIn('[1,4)')->verify() );
        
        //Float
        $this->assertFalse( v::is(3.14)->notIn(1,3.14,2.618)->verify() );
        $this->assertTrue( v::is(2.618)->notIn(0,1.1,2,3.3)->verify() );
        $this->assertFalse( v::is(3.14)->notIn('[0,3.15)')->verify() );
        $this->assertTrue( v::is(2.618)->notIn('[2.62,3.14)')->verify() );
        
        //String
        $this->assertFalse( v::is('wor')->notIn('Hello world!')->verify() );
        $this->assertTrue( v::is('bye')->notIn('Hello world!')->verify() );
        $this->assertFalse( v::is('two')->notIn('one','two','three')->verify() );
        $this->assertTrue( v::is('four')->notIn('one','two','three')->verify() );
    }
    
    public function testOut() {
        //Integer
        $this->assertTrue( v::is(5)->out(2,4,6,8)->verify() );
        $this->assertFalse( v::is(8)->out(2,4,6,8)->verify() );
        $this->assertTrue( v::is(4)->out('(-2.2,4)')->verify() );
        $this->assertFalse( v::is(3)->out('[1,3.0]')->verify() );
        
        //Float
        $this->assertTrue( v::is(3.14)->out(0,1.1,2,3.3)->verify() );
        $this->assertFalse( v::is(2.618)->out(1,3.14,2.618)->verify() );
        $this->assertTrue( v::is(3.14)->out('[-1,1]')->verify() );
        $this->assertFalse( v::is(2.618)->out('[0,3.14)')->verify() );
        
        //String
        $this->assertTrue( v::is('bye')->out('Hello world!')->verify() );
        $this->assertFalse( v::is('Hell')->out('Hello world!')->verify() );
        $this->assertTrue( v::is('zero')->out('one','two','three')->verify() );
        $this->assertFalse( v::is('one')->out('one','two','three')->verify() );
    }
    
    public function testNotOut() {
        //Integer
        $this->assertFalse( v::is(5)->notOut(2,4,6,8)->verify() );
        $this->assertTrue( v::is(8)->notOut(2,4,6,8)->verify() );
        $this->assertFalse( v::is(4)->notOut('(-2.2,4)')->verify() );
        $this->assertTrue( v::is(3)->notOut('[1,3.0]')->verify() );
        
        //Float
        $this->assertFalse( v::is(3.14)->notOut(0,1.1,2,3.3)->verify() );
        $this->assertTrue( v::is(2.618)->notOut(1,3.14,2.618)->verify() );
        $this->assertFalse( v::is(3.14)->notOut('[-1,1]')->verify() );
        $this->assertTrue( v::is(2.618)->notOut('[0,3.14)')->verify() );
        
        //String
        $this->assertFalse( v::is('bye')->notOut('Hello world!')->verify() );
        $this->assertTrue( v::is('Hell')->notOut('Hello world!')->verify() );
        $this->assertFalse( v::is('zero')->notOut('one','two','three')->verify() );
        $this->assertTrue( v::is('one')->notOut('one','two','three')->verify() );
    }
    
    public function testContain() {
        //String
        $str = 'My name is Emi';
        $this->assertTrue( v::is($str)->contain('Emi')->verify() );
        $this->assertTrue( v::is($str)->contain('name','Emi')->verify() );
        $this->assertFalse( v::is($str)->contain('emi')->verify() );
        $this->assertFalse( v::is($str)->contain('surname','Emi')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertTrue( v::is($arr)->contain('uno')->verify() );
        $this->assertTrue( v::is($arr)->contain(2,'tres')->verify() );
        $this->assertFalse( v::is($arr)->contain('TRES')->verify() );
        $this->assertFalse( v::is($arr)->contain(3)->verify() );
        $this->assertFalse( v::is($arr)->contain('uno','dos')->verify() );
    }
    
    public function testNotContain() {
        //String
        $str = 'My name is Emi';
        $this->assertFalse( v::is($str)->notContain('Emi')->verify() );
        $this->assertFalse( v::is($str)->notContain('name','Emi')->verify() );
        $this->assertTrue( v::is($str)->notContain('emi')->verify() );
        $this->assertTrue( v::is($str)->notContain('surname','Emi')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertFalse( v::is($arr)->notContain('uno')->verify() );
        $this->assertFalse( v::is($arr)->notContain(2,'tres')->verify() );
        $this->assertTrue( v::is($arr)->notContain('TRES')->verify() );
        $this->assertTrue( v::is($arr)->notContain(3)->verify() );
        $this->assertTrue( v::is($arr)->notContain('uno','dos')->verify() );
    }
    
    public function testContainAny() {
        //String
        $str = 'My name is Emi';
        $this->assertTrue( v::is($str)->containAny('Emi')->verify() );
        $this->assertTrue( v::is($str)->containAny('name','Emi')->verify() );
        $this->assertTrue( v::is($str)->containAny('name','surname')->verify() );
        $this->assertFalse( v::is($str)->containAny('emi')->verify() );
        $this->assertFalse( v::is($str)->containAny('surname','Jiménez')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertTrue( v::is($arr)->containAny('uno')->verify() );
        $this->assertTrue( v::is($arr)->containAny(2,'tres')->verify() );
        $this->assertTrue( v::is($arr)->containAny('tres',4)->verify() );
        $this->assertFalse( v::is($arr)->containAny('TRES')->verify() );
        $this->assertFalse( v::is($arr)->containAny(3)->verify() );
        $this->assertFalse( v::is($arr)->containAny(1,'dos')->verify() );
    }
    
    public function testNotContainAny() {
        //String
        $str = 'My name is Emi';
        $this->assertFalse( v::is($str)->notContainAny('Emi')->verify() );
        $this->assertFalse( v::is($str)->notContainAny('name','Emi')->verify() );
        $this->assertFalse( v::is($str)->notContainAny('name','surname')->verify() );
        $this->assertTrue( v::is($str)->notContainAny('emi')->verify() );
        $this->assertTrue( v::is($str)->notContainAny('surname','Jiménez')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertFalse( v::is($arr)->notContainAny('uno')->verify() );
        $this->assertFalse( v::is($arr)->notContainAny(2,'tres')->verify() );
        $this->assertFalse( v::is($arr)->notContainAny('tres',4)->verify() );
        $this->assertTrue( v::is($arr)->notContainAny('TRES')->verify() );
        $this->assertTrue( v::is($arr)->notContainAny(3)->verify() );
        $this->assertTrue( v::is($arr)->notContainAny(1,'dos')->verify() );
    }
    
    public function testWithout() {
        //String
        $str = 'My name is Emi';
        $this->assertTrue( v::is($str)->without('emi')->verify() );
        $this->assertTrue( v::is($str)->without('name','Emilio')->verify() );
        $this->assertFalse( v::is($str)->without('Emi')->verify() );
        $this->assertFalse( v::is($str)->without('name','Emi')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertTrue( v::is($arr)->without('TRES')->verify() );
        $this->assertTrue( v::is($arr)->without(3)->verify() );
        $this->assertTrue( v::is($arr)->without('uno','dos')->verify() );
        $this->assertFalse( v::is($arr)->without('uno')->verify() );
        $this->assertFalse( v::is($arr)->without(2,'tres')->verify() );
    }
    
    public function testNotWithout() {
        //String
        $str = 'My name is Emi';
        $this->assertFalse( v::is($str)->notWithout('emi')->verify() );
        $this->assertFalse( v::is($str)->notWithout('name','Emilio')->verify() );
        $this->assertTrue( v::is($str)->notWithout('Emi')->verify() );
        $this->assertTrue( v::is($str)->notWithout('name','Emi')->verify() );
        
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertFalse( v::is($arr)->notWithout('TRES')->verify() );
        $this->assertFalse( v::is($arr)->notWithout(3)->verify() );
        $this->assertFalse( v::is($arr)->notWithout('uno','dos')->verify() );
        $this->assertTrue( v::is($arr)->notWithout('uno')->verify() );
        $this->assertTrue( v::is($arr)->notWithout(2,'tres')->verify() );
    }
    
    public function testDate() {
        //Date
        $this->assertTrue( v::is('19771106')->date('Ymd')->verify() );
        $this->assertTrue( v::is('197711')->date('Ym')->verify() );
        $this->assertTrue( v::is('1977')->date('Y')->verify() );
        $this->assertFalse( v::is('19771100')->date('Ymd')->verify() );
        $this->assertFalse( v::is(19771100)->date('Ymd')->verify() );
        
        //Time
        $this->assertTrue( v::is('23:58:00')->date('H:i:s')->verify() );
        $this->assertTrue( v::is('23:58')->date('H:i')->verify() );
        $this->assertFalse( v::is('24:00')->date('H:i')->verify() );
        $this->assertFalse( v::is('23:60')->date('H:i')->verify() );
        
        //Date & Time
        $this->assertTrue( v::is('1977-11-06 13:01:02')->date('Y-m-d H:i:s')->verify() );
        $this->assertTrue( v::is('23:58 06-11-1977')->date('H:i d-m-Y')->verify() );
        $this->assertTrue( v::is(\DateTime::createFromFormat('Y-m-d','1977-11-06'))->date()->verify() );
    }
    
    public function testNotDate() {
        //Date
        $this->assertFalse( v::is('19771106')->notDate('Ymd')->verify() );
        $this->assertFalse( v::is('197711')->notDate('Ym')->verify() );
        $this->assertFalse( v::is('1977')->notDate('Y')->verify() );
        $this->assertTrue( v::is('19771100')->notDate('Ymd')->verify() );
        $this->assertTrue( v::is(19771100)->notDate('Ymd')->verify() );
        
        //Time
        $this->assertFalse( v::is('23:58:00')->notDate('H:i:s')->verify() );
        $this->assertFalse( v::is('23:58')->notDate('H:i')->verify() );
        $this->assertTrue( v::is('24:00')->notDate('H:i')->verify() );
        $this->assertTrue( v::is('23:60')->notDate('H:i')->verify() );
        
        //Date & Time
        $this->assertFalse( v::is('1977-11-06 13:01:02')->notDate('Y-m-d H:i:s')->verify() );
        $this->assertFalse( v::is('23:58 06-11-1977')->notDate('H:i d-m-Y')->verify() );
        $this->assertFalse( v::is(\DateTime::createFromFormat('Y-m-d','1977-11-06'))->notDate()->verify() );
    }
    
    public function testValue() {
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertTrue( v::is($arr)->value('uno')->verify() );
        $this->assertTrue( v::is($arr)->value(2)->verify() );
        $this->assertFalse( v::is($arr)->value('zero')->verify() );
        $this->assertFalse( v::is($arr)->value('one')->verify() );
        $this->assertFalse( v::is($arr)->value(3)->verify() );
        
        //Object
        $obj = new \stdClass();
        $obj->one = 'uno';
        $obj->two = 2;
        $obj->three = 'tres';
        $this->assertTrue( v::is($obj)->value('uno')->verify() );
        $this->assertTrue( v::is($obj)->value(2)->verify() );
        $this->assertFalse( v::is($obj)->value('zero')->verify() );
        $this->assertFalse( v::is($obj)->value('one')->verify() );
        $this->assertFalse( v::is($obj)->value(3)->verify() );
    }
    
    public function testNotValue() {
        //Array
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        $this->assertFalse( v::is($arr)->notValue('uno')->verify() );
        $this->assertFalse( v::is($arr)->notValue(2)->verify() );
        $this->assertTrue( v::is($arr)->notValue('zero')->verify() );
        $this->assertTrue( v::is($arr)->notValue('one')->verify() );
        $this->assertTrue( v::is($arr)->notValue(3)->verify() );
        
        //Object
        $obj = new \stdClass();
        $obj->one = 'uno';
        $obj->two = 2;
        $obj->three = 'tres';
        $this->assertFalse( v::is($obj)->notValue('uno')->verify() );
        $this->assertFalse( v::is($obj)->notValue(2)->verify() );
        $this->assertTrue( v::is($obj)->notValue('zero')->verify() );
        $this->assertTrue( v::is($obj)->notValue('one')->verify() );
        $this->assertTrue( v::is($obj)->notValue(3)->verify() );
    }
    
    public function testKeyException() {
        $this->setExpectedException('ErrorException');
        v::is(array(1,2,3))->key()->verify();
    }
    
    public function testKey() {
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->assertTrue( v::is($arr)->key('one')->verify() );
        $this->assertTrue( v::is($arr)->key('two',2)->verify() );
        $this->assertTrue( v::is($arr)->key(3,'three','tres')->verify() );
        $this->assertFalse( v::is($arr)->key(1)->verify() );
        $this->assertFalse( v::is($arr)->key('four')->verify() );
        $this->assertFalse( v::is($arr)->key('one',1)->verify() );
        $this->assertFalse( v::is($arr)->key('3',1,2,3)->verify() );
    }
    
    public function testNotKeyException() {
        $this->setExpectedException('ErrorException');
        v::is(array(1,2,3))->notKey()->verify();
    }
    
    public function testNotKey() {
        $arr = array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->assertFalse( v::is($arr)->notKey('one')->verify() );
        $this->assertFalse( v::is($arr)->notKey('two',2)->verify() );
        $this->assertFalse( v::is($arr)->notKey(3,'three','tres')->verify() );
        $this->assertTrue( v::is($arr)->notKey(1)->verify() );
        $this->assertTrue( v::is($arr)->notKey('four')->verify() );
        $this->assertTrue( v::is($arr)->notKey('one',1)->verify() );
        $this->assertTrue( v::is($arr)->notKey('3',1,2,3)->verify() );
    }
    
    public function testAttrException() {
        $obj = (object)array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->setExpectedException('ErrorException');
        v::is($obj)->attr()->verify();
    }
    
    public function testAttr() {
        $obj = (object)array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->assertTrue( v::is($obj)->attr('two')->verify() );
        $this->assertTrue( v::is($obj)->attr('one','uno')->verify() );
        $this->assertFalse( v::is($obj)->attr(2)->verify() );
        $this->assertFalse( v::is($obj)->attr('zero')->verify() );
        $this->assertFalse( v::is($obj)->attr('two','dos')->verify() );
        $this->assertFalse( v::is($obj)->attr(3,'three','tres')->verify() );
        $this->assertFalse( v::is($obj)->attr('3',1,2,3)->verify() );
    }
    
    public function testNotAttrException() {
        $obj = (object)array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->setExpectedException('ErrorException');
        v::is($obj)->notAttr()->verify();
    }
    
    public function testNotAttr() {
        $obj = (object)array('one'=>'uno','two'=>2,3=>'tres');
        
        $this->assertFalse( v::is($obj)->notAttr('two')->verify() );
        $this->assertFalse( v::is($obj)->notAttr('one','uno')->verify() );
        $this->assertTrue( v::is($obj)->notAttr(2)->verify() );
        $this->assertTrue( v::is($obj)->notAttr('zero')->verify() );
        $this->assertTrue( v::is($obj)->notAttr('two','dos')->verify() );
        $this->assertTrue( v::is($obj)->notAttr(3,'three','tres')->verify() );
        $this->assertTrue( v::is($obj)->notAttr('3',1,2,3)->verify() );
    }
    
    public function testFilter() {
        //TODO
        
    }
    
    public function testRegex() {
        //TODO
    }
    
}
