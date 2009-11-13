<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class PersonForm extends Form {
	protected function configure() {
		$this->first_name = new TextField();
		$this->last_name = new TextField();
		$this->birthday = new DateField();
		$this->address = new TextField(array(
			'widget' => new TextareaInput(array('class' => array('not-required'))), 
			'required' => false,
		));
	}
}


/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Tests
 * @version $Revision$
 */
class FormTest extends CobwebTestCase {
	
	/**
	 * @dataProvider forms
	 **/
	public function testSimpleForm($form) {

		$this->assertEquals($form->errors(), array());
		$this->assertTrue($form->isValid());
		
		$this->assertEquals($form->cleanData(), array(
			'first_name' => 'John',
			'last_name' => 'Lennon',
			'birthday' => CWDateTime::create(1940, 10, 9),
			'address' => 'Liverpool & New York'
		));
		
		$labels = array();
		foreach ($form as $name => $field)
			$labels[] = $field->label();
		$this->assertEquals($labels, array(
			'First name',
			'Last name',
			'Birthday',
			'Address'
		));
		
		$html = array();
		foreach ($form as $name => $field)
			$html[] = $field->render();

		$this->assertEquals($html, array(
			'<input type="text" name="first_name" value="John" id="id_first_name" />',
			'<input type="text" name="last_name" value="Lennon" id="id_last_name" />',
			'<input type="text" name="birthday" value="1940-10-9" id="id_birthday" />',
			'<textarea class="not-required" cols="40" rows="10" name="address" id="id_address">' .
				'Liverpool &amp; New York' .
			'</textarea>'
		));
	}
	
	public function testSimpleEmptyForm() {
		$form = new PersonForm(array());
		$this->assertFalse($form->isValid());
		$errors = $form->errors();
		$this->assertEquals($errors, array(
			'first_name' => array(__('This field is required.')),
			'last_name' => array(__('This field is required.')),
			'birthday' => array(__('This field is required.'))
		));
	}
	
	public function forms() {
		$data = array(
			'first_name' => 'John', 
			'last_name' => 'Lennon', 
			'birthday' => '1940-10-9',
			'address' => 'Liverpool & New York'
		);
		
		// equivalent forms
		return array(
			array(new PersonForm($data)),
			array(Form::create(array(
					'first_name' => new TextField(),
					'last_name' => new TextField(),
					'birthday' => new DateField(),
					'address' => new TextField(array(
						'widget' => new TextareaInput(array('class' => array('not-required'))), 
						'required' => false,
					))
				),
				$data
			))
		);
	}
	
}