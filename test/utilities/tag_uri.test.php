<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Tests
 * @version $Revision$
 */
class TagURITest extends CobwebTestCase {
	
	public function testHTTPURLs() {
		$url = 'http://diveintomark.org/archives/2004/05/27/howto-atom-linkblog';
		$expected = 'tag:diveintomark.org,2004-05-27:/archives/2004/05/27/howto-atom-linkblog';
		$date = new DateTime('2004-05-27');
		
		$this->assertEquals(tag_uri($url, $date), $expected);
		
		$expected = 'tag:diveintomark.org,2004-05:/archives/2004/05/27/howto-atom-linkblog';
		$this->assertEquals(tag_uri($url, $date, 'Y-m'), $expected);
		
		$expected = 'tag:diveintomark.org,2004:/archives/2004/05/27/howto-atom-linkblog';
		$this->assertEquals(tag_uri($url, $date, 'Y'), $expected);
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testInvalidDateFormat() {
		$url = 'http://diveintomark.org/archives/2004/05/27/howto-atom-linkblog';
		$expected = 'tag:diveintomark.org,2004-05-27:/archives/2004/05/27/howto-atom-linkblog';
		$date = new DateTime('2004-05-27');
		
		tag_uri($url, $date, 'm');
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testInvalidURL() {
		tag_uri('/archives/2004/05/27/howto-atom-linkblog', new DateTime());
	}
	
	public function testURLWithoutScheme() {
		$url = 'diveintomark.org/archives/2004/05/27/howto-atom-linkblog';
		$expected = 'tag:diveintomark.org,2004-05-27:/archives/2004/05/27/howto-atom-linkblog';
		$date = new DateTime('2004-05-27');
		
		$this->assertEquals(tag_uri($url, $date), $expected);
	}
}