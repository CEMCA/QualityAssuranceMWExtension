<?php
/**
 * This file is part of the PeerEvaluation extension.
 * For more info see http://wikieducator.org/Extension:PeerEvaluation
 * @license GNU General Public Licence 2.0 or later
 */
class TagQa {
	static function onParserInit( Parser $parser ) {
		$parser->setHook( 'qa', array( __CLASS__, 'qaRender' ) );
		return true;
	}
	static function qaRender( $input, array $args, Parser $parser ) {
		global $wgUser;
		global $wgHomedirPath;
		$ret = '<script src="' . $wgHomedirPath . '/resources/qa.js"></script>';
		$ret .= '
			<p id="title">Quality Assurance text</p>
			<br>';
		return $ret;
	}
}
?>
