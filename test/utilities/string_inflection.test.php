<?php

class StringInflectionTest extends CobwebTestCase {
	
	
	public function testClassify() {
		$this->assertEquals(str_classify('cobweb_class'), 'CobwebClass');
	}
	
	public function testSlugify() {
		$this->assertEquals(str_slugify('this-is-a-slug'), 'this-is-a-slug');
		$this->assertEquals(str_slugify('this is a slug'), 'this-is-a-slug');
		$this->assertEquals(str_slugify('THIS-is-a-slug'), 'this-is-a-slug');
		$this->assertEquals(str_slugify('THIS.is.a.slug'), 'this-is-a-slug');
		$this->assertEquals(str_slugify('THIS    .is-a.     slug'), 'this-is-a-slug');
	}
	
	public function testSlugifyForeignCharacters() {
		$this->assertEquals(str_slugify('øystein rocks'), 'oystein-rocks');
		$this->assertEquals(str_slugify('ålesund is a City'), 'alesund-is-a-city');
		$this->assertEquals(str_slugify('Øystein\'s Macbook Pro'), 'oystein-s-macbook-pro');
		$this->assertEquals(str_slugify('iñtërnâtiônàlizætiøn'), 'internationalizaetion');
	}
}