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
 * @see http://code.iamcal.com/php/rfc822/tests.php
 */
class EmailFieldTest extends CobwebTestCase {
	
	public function testEmailField() {
		$field = new EmailField();
		
		try {
			$field->clean('');
			$this->fail('Empty required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
		
		try {
			$field->clean(NULL);
			$this->fail('Empty required value should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('This field is required.')));
		}
		
	}
	
	/**
     * @dataProvider emailAddresses
     */
	public function testEmailAddress($email, $should_be_valid) {
		$field = new EmailField();
		
		if ($should_be_valid)
			$this->assertEquals($field->clean($email), $email);
		else {
			try {
				$field->clean($email);
				$this->fail('Invalid e-mail should throw validation exception');
			} catch (FormValidationException $e) {
				$this->assertEquals($e->messages(), array(__('Enter a valid e-mail address.')));
			}
		}
	}
	
	public function testEmailMX() {
		$field = new EmailField(array('check_mx' => true));
		$this->assertEquals($field->clean('_@gmail.com'), '_@gmail.com');
		
		try {
			$field->clean('oystein@hercules.local');
			$this->fail('Address with non-existing MX record should throw validation exception');
		} catch (FormValidationException $e) {
			$this->assertEquals($e->messages(), array(__('Unknown mail host. Enter a valid e-mail address.')));
		}
	}
	
	public function emailAddresses() {
		return array(
			array('person@example.com', true),
			array('foo@', false),
			array('example@invalid-.com', false),
			array('example@invalid-.com', false),
			array('example@-invalid.com', false),
			array('example@inv-.alid-.com', false),
			array('example@inv-.-alid.com', false),
			array('example@valid-----hyphens.com', true),
			array('example@valid-with-hyphens.com', true),
			// array('viewx3dtextx26qx3d@yahoo.comx26latlngx3d15854521645943074058', false) 
		);
    }
}