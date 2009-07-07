<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
class TextInput extends FormWidget {
	
	public function render($data = NULL) {
		$data = is_null($data) ? $this->initialData() : $data;
		return "<input type=\"text\" value=\"{$data}\" />";
	}
}