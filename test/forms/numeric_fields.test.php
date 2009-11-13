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
 */
class NumericFieldsTest extends CobwebTestCase {
	
	public function testNumericField() {
		$field = new NumericField();
		$this->assertSame($field->clean('11'), 11);
		$this->assertSame($field->clean('11.1'), 11.1);
		$this->assertSame($field->clean('11e1'), 110.0);
	}
	
}