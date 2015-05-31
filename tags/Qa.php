<?php

/**
 * This file is part of the OER Quality Assurance extension.
 * For more info see https://www.mediawiki.org/wiki/Extension:QualityAssurance
 * @license CC BY-SA 3.0 or later
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
		$ret .= '<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
		$ret .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
		$ret .= '
			<br>
			<span id = "ratingInfo">
			<b> Quality Assesment Rating (Overall) - <span id="roverall">getting...</span> <br><br> </b>
			<b>T</b> - <span id="rT">getting...</span> <br>
			<b>I</b> - <span id="rI">getting...</span> <br>
			<b>P</b> - <span id="rP">getting...</span> <br>
			<b>S</b> - <span id="rS">getting...</span> <br><br>
			<b>Number of responses</b> - <span id="numResponses">getting...</span> <br><br>
			</span>
			<span id = "detailedInfo">
			<button id="showDetailedInfo">Show detailed assesments</button>
			</span>
			<button id="assess"> Make a quality assessment of this article </button>
			<span id="assesmentForm"></span>
			<br><br><hr><br>';
		return $ret;
	}
}
?>
