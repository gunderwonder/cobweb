<?php

class StringInflectionTest extends CobwebCoreTestCase {
	
	public function setUp() {
		require_once dirname(__FILE__) . '/../../utilities/string_inflection.inc.php';
	}
	
	public function testClassify() {
		$this->assertEqual(str_classify('cobweb_class'), 'CobwebClass');
	}
	
	public function testSlugify() {
		$this->assertEqual(str_slugify('this-is-a-slug'), 'this-is-a-slug');
		$this->assertEqual(str_slugify('this is a slug'), 'this-is-a-slug');
		$this->assertEqual(str_slugify('THIS-is-a-slug'), 'this-is-a-slug');
		$this->assertEqual(str_slugify('THIS.is.a.slug'), 'this-is-a-slug');
		$this->assertEqual(str_slugify('THIS    .is-a.     slug'), 'this-is-a-slug');
	}
	
	public function testSlugifyForeignCharacters() {
		$this->assertEqual(str_slugify('øystein rocks'), 'oystein-rocks');
		$this->assertEqual(str_slugify('ålesund is a City'), 'alesund-is-a-city');
		$this->assertEqual(str_slugify('Øystein\'s Macbook Pro'), 'oystein-s-macbook-pro');
	}
}