<?php
session_start();
require_once("query_answers_functions.php");

$answerArray = array(); //Array returned for each questionID
$jsonArray = array(); //Final array returned for AJAX

$patientID=$_REQUEST["patientID"];

//if (isset($_SESSION["userName"]))
//$clinicianID=$_SESSION["userName"];

if(!isset($clinicianID))
$clinicianID="axstest";
//if (isset($_REQUEST["clinicianID"]))
//$clinicianID=$_REQUEST["clinicianID"];

$defaultQuestionArray = array("MC5_0","MC2_0","VAS_0","VAS_1"); //Array containing questionIDs to be queried. Arbitrary for now.

//QuestionIDs minus default set.
$fullQuestionArray = array(	"MC5_1","MC5_2","MC5_3","MC5_4","MC5_5","MC5_6","MC5_7","MC5_8","MC5_9","MC5_10","MC5_11","MC5_12","MC5_13","MC5_14",							
							"VAS_2","VAS_3","VAS_4","VAS_5","VAS_6","VAS_7","VAS_8","VAS_9","VAS_10",
							"VAS_11","VAS_12","VAS_13","VAS_14","VAS_15","VAS_16","VAS_17","VAS_18","VAS_19","VAS_20",
							"VAS_21","VAS_22","VAS_23");
											
//-------------------------------------------------
//Grab initial load of data
unset($answerArray);

	$questionArray = $fullQuestionArray;

for ($i = 0; $i < count($questionArray); $i++){
	$questionID = $questionArray[$i];
	
	$answerArray['id'] = $questionID;
	
	$answerArray['name'] = getDescription($questionID);
	
	if ($questionID == "SCORE_0")
	$answerArray['name'] = "SCORE_Depression";
	
	//Get question type
	$arr = explode("_", $answerArray['id'], 2);
	$questionType = $arr[0];
	
	//Push in the answers and get length of array
	$answerArray["results"] = getAnswers($patientID, $questionID, $questionType);
	$answerArray["length"] = count($answerArray["results"]);
	array_push($jsonArray, $answerArray);
}
//-------------------------------------------------
//Grab questions that aren't null
unset($answerArray);
for ($i = 0; $i < count($fullQuestionArray2); $i++){
	
	if (!in_array($fullQuestionArray2[$i], $questionArray)){
		$answerArray["id"] = $fullQuestionArray2[$i];
		$answerArray["length"] = answerLength($fullQuestionArray2[$i], $patientID);
		array_push($jsonArray, $answerArray);
	}
}

//-------------------------------------------------
//Grab comments
unset($answerArray);
$answerArray["id"] = "comment";
$answerArray["results"] = getCommentsTags($patientID);
$answerArray["length"] = count($answerArray["results"]);
array_push($jsonArray, $answerArray);

//-------------------------------------------------
//Grab unique tags
unset($answerArray);
$answerArray["id"] = "uniqueTags";
$answerArray["results"] = getUniqueTags($patientID);
//$answerArray["length"] = count($answerArray["results"]);
array_push($jsonArray, $answerArray);

echo json_encode($jsonArray);
exit();
?>