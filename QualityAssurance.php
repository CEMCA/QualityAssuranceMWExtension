<?php
/**
 * OER Quality Assurance extension
 *
 * For more info see https://www.mediawiki.org/wiki/Extension:QualityAssurance
 *
 * @file
 * @ingroup Extensions
 * @author Akash Agarwal, 2014
 * @license CC BY-SA 3.0 or later
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'QualityAssurance',
	'author' => array(
		'Akash Agarwal',
	),
	'version'  => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:QualityAssurance',
	'descriptionmsg' => 'The CEMCA OER Quality Assurance mediawiki extension.',
);

/* Setup */

// Register tags
$wgAutoloadClasses['TagQa'] = dirname( __FILE__ ) . '/tags/Qa.php';
$wgHooks['ParserFirstCallInit'][] = 'TagQa::onParserInit';

/* Configuration */

$wgHooks['LoadExtensionSchemaUpdates'][] = 'addqa_noOfResponses';
function addqa_noOfResponses( DatabaseUpdater $updater ) {
	$updater->addExtensionTable( 'qa_noOfResponses',
		dirname( __FILE__ ) . '/table.sql', true );
	return true;
}

$wgHooks['LoadExtensionSchemaUpdates'][] = 'addqa_answers';
function addqa_answers( DatabaseUpdater $updater ) {
	$updater->addExtensionTable( 'qa_answers',
		dirname( __FILE__ ) . '/table.sql', true );
	$updater->addExtensionField( 'qa_answers', 'answer', dirname( __FILE__ ) . '/table.patch.answer.sql', true );
	$updater->addExtensionUpdate( array( 'modifyField', 'qa_answers', 'answer',
		dirname( __FILE__ ) . '/table.patch.answer.sql', true ) );
	return true;
}

// API's
$wgAutoloadClasses['qaAssesments'] = dirname( __FILE__ ) . '/apis/qaAssesments.php';
$wgAPIModules['qaAssesments'] = 'qaAssesments';

$wgAutoloadClasses['qaSubmit'] = dirname( __FILE__ ) . '/apis/qaSubmit.php';
$wgAPIModules['qaSubmit'] = 'qaSubmit';

$wgHomedirPath = "/extensions/QualityAssurance/";