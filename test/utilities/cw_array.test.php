<?php


class CWArrayTest extends CobwebTestCase {
	
	public function testEmptyArray() {
		
		$a = new CWArray();
		$this->assertTrue(count($a) === 0);
		$this->assertFalse(isset($a['key']));
		
		try {
			$b = $a['key'];
			$this->fail('Non-existant key should throw error exception');
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
		
		$this->assertTrue(is_null($a->get('key')));
		$this->assertTrue($a->get('key', false) === false);
		
		$this->assertSame($a->keys(), array());
		$this->assertSame($a->values(), array());
		$this->assertSame($a->toArray(), array());
		
		$this->assertSame($a->map(function($v) { return $v; }), array());
		$this->assertSame($a->detect(function($v) { return $v; }), false);
		
		$this->assertTrue($a->all());
		
	}
	
	public function testZip() {
		$first_names = new CWArray(array('Jane', 'Nitin', 'Guy'));
		$last_names  = new CWArray(array('Doe',  'Patel', 'Forcier'));
		$ages = new CWArray(array(23, 41, 17));
		
		$this->assertSame($first_names->zip($last_names), array(
			array('Jane', 'Doe'),
			array('Nitin', 'Patel'),
			array('Guy', 'Forcier'),
		));
		
		$this->assertSame($first_names->zip($last_names, $ages), array(
			array('Jane', 'Doe', 23),
			array('Nitin', 'Patel', 41),
			array('Guy', 'Forcier', 17),
		));
		
		$this->assertSame($first_names->zip($last_names, $ages, function($t) {
				return "{$t[0]} {$t[1]} is {$t[2]}";
			}), 
			array('Jane Doe is 23', 'Nitin Patel is 41', 'Guy Forcier is 17')
		);
		
		$this->assertSame($first_names->zip(array()), array(
			array('Jane', NULL),
			array('Nitin', NULL),
			array('Guy', NULL),
		));
	}
	
	public function testInject() {
		$a = new CWArray(range(1, 10));
		$this->assertSame(
			$a->inject(0, function($accumulator, $i) {
				return $accumulator + $i;
			}),
			55
		);
		
		$a = new CWArray(array('a', 'b', 'c', 'd', 'e'));
		$this->assertSame(
			$a->inject('', function($string, $value, $index) {
  				if ($index % 2 == 0)
    				$string .= $value;
				return $string;
  			}),
			'ace'
		);
		
		$a = new CWArray();
		$this->assertSame($a->inject(0, 'identity_function'), 0);
		
	}
	
	public function testAny() {
		$this->assertFalse(CWArray::create()->any());
		$this->assertTrue(CWArray::create(range(1, 5))->any());
		
		$this->assertTrue(CWArray::create(array(2, 4, 6, 8, 10))->any(function($n) { 
			return $n > 5; 
		}));
		$this->assertFalse(CWArray::create(array(0, NULL, false))->any());
	}
	
	public function testAll() {
		$this->assertTrue(CWArray::create()->all());
		$this->assertTrue(CWArray::create(range(1, 5))->all());
		$this->assertFalse(CWArray::create(array(0, 1, 2))->all());
		$this->assertFalse(CWArray::create(array(9, 10, 15))->all(function($n) { 
			return $n >= 10; 
		}));
	}
	
	public function testContains() {
		$this->assertFalse(CWArray::create()->contains(NULL));
		$this->assertTrue(CWArray::create(range(1, 5))->contains(5));
		$this->assertFalse(CWArray::create(array(0, 1, 2))->contains(10));
	}
	
	public function testSentinels() {
		try {
			$this->assertFalse(CWArray::create()->first());
			$this->fail('Empty array should cause exception');
		} catch (OutOfBoundsException $e) {
			$this->assertTrue(true, 'Empty array causes exception');
		}
		
		try {
			$this->assertFalse(CWArray::create()->last());
			$this->fail('Empty array should cause exception');
		} catch (OutOfBoundsException $e) {
			$this->assertTrue(true, 'Empty array causes exception');
		}
		
		$this->assertSame(CWArray::create(range(1, 5))->first(), 1);
		$this->assertSame(CWArray::create(range(1, 5))->last(), 5);
	}
	
	public function testConcatenate() {
		$this->assertSame(
			CWArray::create(range(1, 5))->concatenate(array(6, 7))->toArray(), 
			array(1, 2, 3, 4, 5, 6, 7)
		);
		
		$this->assertSame(
			CWArray::create(array('a' => 0, 2))->concatenate(array(6, 7))->toArray(), 
			array('a' => 0, 2, 6, 7)
		);
	}
	
	public function testMerge() {
		$this->assertSame(
			CWArray::create(array('a' => 0, 2))->merge(array('a' => 1, 3))->toArray(), 
			array('a' => 1, 2, 3)
		);
	}
}