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


		$dbr = $this->getDB();
		if ( $qatype == 'basic' ) {

			
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
				$entries['TScore']=round($row->TScore,2);
				$entries['IScore']=round($row->IScore,2);
				$entries['PScore']=round($row->PScore,2);
				$entries['SScore']=round($row->SScore,2);
				$entries['overallScore']=round($row->overallScore,2);
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

				$res = $dbr->insert(
					'qa_noOfResponses',
					array('pageId' => $qaPageNo, 'numResponses' => 0),
					$fname = __METHOD__,
					$options = array( '' )
				);

			}

			$result->addValue( null, $this->getModuleName(), $entries );
		}

		if ( $qatype == 'detailed' ) {

			$scores = $dbr->select(
				'qa_answers',
				array( '*' ),
				array( 'pageId' => $qaPageNo,),
				$fname = __METHOD__,
				$options = array( '' )
			);

			$entries = array();

			foreach ($scores as $row) {

				$entry = array();

				$localAnswer =  json_decode($row->answer);

				$entry['answers'] = $localAnswer;

				$user = User::newFromId($row->userId);
				$entry['username'] = $user->getName();

				$tLocal = 0.0;
				$iLocal = 0.0;
				$pLocal = 0.0;
				$sLocal = 0.0;

				foreach ($localAnswer as $key => $value) {
					if ( $key <= 8 ) {
						$tLocal += intval($value);
					}
					else if ( $key <= 12) {
						$iLocal += intval($value);
					}
					else if ( $key <= 18 ) {
						$pLocal += intval($value);
					}
					else {
						$sLocal += intval($value);
					}
				}

				$tTotal = ($tLocal/8) ;
				$iTotal = ($iLocal/4) ;
				$pTotal = ($pLocal/6) ;
				$sTotal = ($sLocal/3) ;

				$averageTotel = (($tLocal+$iLocal+$pLocal+$sLocal)/21) ;

				$entry['t'] = round($tTotal,2);
				$entry['i'] = round($iTotal,2);
				$entry['p'] = round($pTotal,2);
				$entry['s'] = round($sTotal,2);
				$entry['avg'] = round($averageTotel,2);

				array_push($entries, $entry);
			}

			
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