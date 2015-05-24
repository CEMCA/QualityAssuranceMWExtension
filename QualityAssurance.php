<?php
/**
 * OER Quality Assurance extension - the thing that needs you.
 *
 * For more info see https://www.mediawiki.org/wiki/Extension:QualityAssurance
 *
 * @file
 * @ingroup Extensions
 * @author Akash Agarwal, 2014
 * @license GNU General Public Licence 2.0 or later
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Quality Assurance',
	'author' => array(
		'Akash Agarwal',
	),
	'version'  => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:QualityAssurance',
	'descriptionmsg' => 'The CEMCA OER Quality Assurance mediawiki extension.',
);

/* Setup */

// Register files
//$wgAutoloadClasses['BoilerPlateHooks'] = __DIR__ . '/BoilerPlate.hooks.php';
$wgAutoloadClasses['SpecialHelloWorld'] = __DIR__ . '/specials/SpecialHelloWorld.php';
//$wgMessagesDirs['BoilerPlate'] = __DIR__ . '/i18n';
//$wgExtensionMessagesFiles['BoilerPlateAlias'] = __DIR__ . '/BoilerPlate.i18n.alias.php';

// Register hooks
#$wgHooks['NameOfHook'][] = 'BoilerPlateHooks::onNameOfHook';

// Register special pages
//$wgSpecialPages['HelloWorld'] = 'SpecialHelloWorld';
//$wgSpecialPageGroups['HelloWorld'] = 'other';


// Register tags
$wgAutoloadClasses['TagQa'] = dirname( __FILE__ ) . '/tags/Qa.php';
$wgHooks['ParserFirstCallInit'][] = 'TagQa::onParserInit';

/*
// Register modules
$wgResourceModules['ext.BoilerPlate.foo'] = array(
	'scripts' => array(
		'modules/ext.BoilerPlate.foo.js',
	),
	'styles' => array(
		'modules/ext.BoilerPlate.foo.css',
	),
	'messages' => array(
	),
	'dependencies' => array(
	),

	'localBasePath' => __DIR__,
	'remoteExtPath' => 'examples/BoilerPlate',
);
*/

$wgHomedirPath = "/mnineteen/extensions/QualityAssurance/";

/* Configuration */

// Enable Foo
#$wgBoilerPlateEnableFoo = true;

# Schema updates for update.php

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
	return true;
}

// API's
$wgAutoloadClasses['qaAssesments'] = dirname( __FILE__ ) . '/apis/qaAssesments.php';
$wgAPIModules['qaAssesments'] = 'qaAssesments';

$wgAutoloadClasses['qaSubmit'] = dirname( __FILE__ ) . '/apis/qaSubmit.php';
$wgAPIModules['qaSubmit'] = 'qaSubmit';
