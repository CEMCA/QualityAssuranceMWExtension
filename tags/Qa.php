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
			<br>
			<span id = "ratingInfo">
			Quality Assesment Rating (Overall) - <span id="roverall">getting...</span> <br>
			Quality Assesment Rating (T) - <span id="rT">getting...</span> <br>
			Quality Assesment Rating (I) - <span id="rI">getting...</span> <br>
			Quality Assesment Rating (P) - <span id="rP">getting...</span> <br>
			Quality Assesment Rating (S) - <span id="rS">getting...</span> <br>
			Number of responses - <span id="numResponses">getting...</span> <br>
			</span>
			<span id = "detailedInfo">
			<button id="showDetailedInfo">Show detailed assesments</button>
			</span>			
			<button id="assess"> Make a quality assessment of this article </button>
			<span id="assesmentForm"></span>
			<br>';
		return $ret;
	}
}
?>
