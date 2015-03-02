<?php

class qaAssesments extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, '' );
	}

	public function execute() {

		global  $wgServer;
		global $wgDefaultUserOptions;

		$result = $this->getResult();
		$params = $this->extractRequestParams();

		$data = '';

		$qaPageNo = filter_var( $params['qaPageNo'], FILTER_SANITIZE_STRING );
		$qatype = filter_var( $params['qatype'], FILTER_SANITIZE_STRING );

		if ( !$qaPageNo ) {
			$this->dieUsage( 'noqaPageNo' , 'page no cannot be null' );
		}
		//$result->addValue( null, $this->getModuleName(),$qatype);



		if ( $qatype == 'basic' ) {

			$dbr = $this->getDB();

				$res = $dbr->select(
				'qa_noOfResponses',
				array( '*' ),
				$conds = 'pageId="' . $qaPageNo . '"',
				$fname = __METHOD__,
				$options = array( '' )
			);

			$entries = array();
			$entries['qaPageNo']=$qaPageNo;
			//print $res;

			foreach ( $res as $row ) {
				$entries['numResponses']=$row->numResponses;
				$entries['TScore']=$row->TScore;
				$entries['IScore']=$row->IScore;
				$entries['PScore']=$row->PScore;
				$entries['SScore']=$row->SScore;
				$entries['overallScore']=$row->overallScore;				
			}

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		if ( $qatype == 'detailed' ) {

			$dbr = $this->getDB();

				$res = $dbr->select(
				'pe_evaluations',
				array( '*' ),
				$conds = 'Activity="' . $activityPage . '"',
				$fname = __METHOD__,
				$options = array( '' )
			);

			$num = 1;
			$entries = array();

			foreach ( $res as $row ) {
				$entry = array();
				$evaluation = json_decode( $row->evaluation, true );
				$entry['id'] = $row->activityId;
				$entry['evaluaterUName'] = $row->evaluaterUName;
				$entry['evaluation'] = $evaluation;
				$entries['entry' . $num] = $entry;
				$num++;
			}

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		return true;
	}

	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}


	public function getAllowedParams() {
		return array (
			'qatype' => null,
			'qaPageNo' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'qatype' => 'weather to get basic information or details',
			'qaPageNo' => 'Page No of the wiki page',
			);
	}

	public function getDescription() {
		return 'API to get assesment details';
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
