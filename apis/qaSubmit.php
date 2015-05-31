<?php

/**
 * This file is part of the OER Quality Assurance extension.
 * For more info see https://www.mediawiki.org/wiki/Extension:QualityAssurance
 * @license CC BY-SA 3.0 or later
 */

class qaSubmit extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}

	public function execute() {

		global  $wgUser, $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();

		$data = '';

		$qaPageNo = filter_var( $params['qaPageNo'], FILTER_SANITIZE_STRING );
		$qatype = filter_var( $params['qatype'], FILTER_SANITIZE_STRING );


		if ( !$qaPageNo ) {
			$this->dieUsage( 'noqaPageNo' , 'page no cannot be null' );
		}

		if ( !$wgUser->isLoggedIn() ) {
            $this->dieUsage( 'must be logged in',
                'notloggedin' );
        } ;
		// $result->addValue( null, $this->getModuleName(),$qatype);

		$dbr = $this->getDB();
		$userId = $wgUser->getId();

		if ( $qatype == 'check' ) {


			$res = $dbr->select(
				'qa_answers',
				array( '*' ),
				array( 'pageId' => $qaPageNo, 'userId' => $userId, ),
				$fname = __METHOD__,
				$options = array( '' )
			);

			$entryExists = false;
			foreach ( $res as $row ) {
				$entryExists = true;
			}

			$entries = array( 'alreadySubmitted' => $entryExists );

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		if ( $qatype == 'submit' ) {

			$answers = filter_var( $params['qaAnswer'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );


			$res = $dbr->select(
				'qa_answers',
				array( '*' ),
				array( 'pageId' => $qaPageNo, 'userId' => $userId, ),
				$fname = __METHOD__,
				$options = array( '' )
			);


			$entryExists = false;
			foreach ( $res as $row ) {
				$entryExists = true;
			}

			if ( !$entryExists ) {
				$res = $dbr->insert(
					'qa_answers',
					array( 'pageId' => $qaPageNo, 'userId' => $userId, 'answer' => $answers ),
					$fname = __METHOD__,
					$options = array( '' )
				);
			}

			else {
				$res = $dbr->update(
					'qa_answers',
					array( 'pageId' => $qaPageNo, 'userId' => $userId, 'answer' => $answers ),
					array( 'pageId' => $qaPageNo, 'userId' => $userId, ),
					$fname = __METHOD__,
					$options = array( '' )
				);
			}

			$num = 0;

			$tTotal = 0.0;
			$iTotal = 0.0;
			$pTotal = 0.0;
			$sTotal = 0.0;

			$averageTotel = 0.0;

			$scores = $dbr->select(
				'qa_answers',
				array( '*' ),
				array( 'pageId' => $qaPageNo, ),
				$fname = __METHOD__,
				$options = array( '' )
			);


			foreach ( $scores as $row ) {
				$num += 1;

				$localAnswer =  json_decode( $row->answer );

				$tLocal = 0.0;
				$iLocal = 0.0;
				$pLocal = 0.0;
				$sLocal = 0.0;

				foreach ( $localAnswer as $key => $value ) {
					if ( $key <= 8 ) {
						$tLocal += intval( $value );
					}
					else if ( $key <= 12 ) {
						$iLocal += intval( $value );
					}
					else if ( $key <= 18 ) {
						$pLocal += intval( $value );
					}
					else {
						$sLocal += intval( $value );
					}
				}

				$tTotal += ( $tLocal / 8 ) ;
				$iTotal += ( $iLocal / 4 ) ;
				$pTotal += ( $pLocal / 6 ) ;
				$sTotal += ( $sLocal / 3 ) ;

				$averageTotel += ( ( $tLocal + $iLocal + $pLocal + $sLocal ) / 21 ) ;
			}

			$res = $dbr->update(
				'qa_noOfResponses',
				array( 'pageId' => $qaPageNo,
					'numResponses' => $num,
					'TScore' => $tTotal / $num,
					'IScore' => $iTotal / $num,
					'PScore' => $pTotal / $num,
					'SScore' => $sTotal / $num,
					'overallScore' => $averageTotel / $num,
				),
				array( 'pageId' => $qaPageNo, ),
				$fname = __METHOD__,
				$options = array( '' )
			);

			$entries = array( 'success' => true, 'answers' => $answers, 'average' => $averageTotel );

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		return true;
	}

	protected function getDB() {
		return wfGetDB( DB_MASTER );
	}


	public function getAllowedParams() {
		return array (
			'qatype' => null,
			'qaPageNo' => null,
			'qaAnswer' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'qatype' => 'query type',
			'qaPageNo' => 'Page No of the wiki page',
			'qaAnswer' => 'JSON containing the answers'
			);
	}

	public function getDescription() {
		return 'API to submit an assessment';
	}

	protected function getExamples() {
		return array (
		);
	}
	public function getVersion() {
		return __CLASS__ . ': 0';
	}
}

?>