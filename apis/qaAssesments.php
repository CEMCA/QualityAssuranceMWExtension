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

			$emptyFlag=true;

			foreach ( $res as $row ) {
				$emptyFlag=false;
				
				$entries['numResponses']=$row->numResponses;
				$entries['TScore']=$row->TScore;
				$entries['IScore']=$row->IScore;
				$entries['PScore']=$row->PScore;
				$entries['SScore']=$row->SScore;
				$entries['overallScore']=$row->overallScore;
			}
			//the below if needs to be removed
			//$entries['tese']=$emptyFlag;
			if ($emptyFlag) {
				$entries['numResponses']=0;
				$entries['TScore']=0;
				$entries['IScore']=0;
				$entries['PScore']=0;
				$entries['SScore']=0;
				$entries['overallScore']=0;				
			}

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		if ( $qatype == 'detailed' ) {

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