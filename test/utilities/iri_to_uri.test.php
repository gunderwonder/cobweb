<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Tests
 * @version $Revision$
 * @see http://code.djangoproject.com/browser/django/trunk/tests/regressiontests/text/tests.py?rev=5609
 */
class IRIToURITest extends CobwebTestCase {
	
	public function test() {
		$this->assertEquals(iri_to_uri('red%09rosé#red'), 'red%09ros%C3%A9#red');
		$this->assertEquals(
			iri_to_uri('/blog/for/Jürgen Münster/'), 
			'/blog/for/J%C3%BCrgen%20M%C3%BCnster/'
		);
		
		$this->assertEquals(
			iri_to_uri('locations/'. urlencode('Paris & Orléans')), 
			'locations/Paris+%26+Orl%C3%A9ans'
		);
		
		$this->assertEquals(
			iri_to_uri('/?iñtërnâtiônàlizætiøn=1'),
			'/?i%C3%B1t%C3%ABrn%C3%A2ti%C3%B4n%C3%A0liz%C3%A6ti%C3%B8n=1'
		);
	}
}