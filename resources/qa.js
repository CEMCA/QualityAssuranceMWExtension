$ ( document ).ready ( function() {

	$articleId=wgArticleId;
	$("#title").html("The id of the page is : "+$articleId);

	$.get(wgScriptPath+"/api.php?action=qaAssesments&qaPageNo="+$articleId+"&qatype=basic&format=json",function(data) {
		console.log(data);
		qaAssesments=data.qaAssesments;
		$("#roverall").html(qaAssesments.overallScore+"/4.0");
		$("#rT").html(qaAssesments.TScore+"/4.0");
		$("#rI").html(qaAssesments.IScore+"/4.0");
		$("#rP").html(qaAssesments.PScore+"/4.0");
		$("#rS").html(qaAssesments.SScore+"/4.0");
		$("#numResponses").html(qaAssesments.numResponses+"");

		if (qaAssesments.numResponses == 0) {
			$("#ratingInfo").html("No quality assessments yet.<br>");
		}
	});

	$("#assess").click(function(){
		//console.log("Assess fn callses");
		$("#assess").hide();
		$("#assesmentForm").html("");
		$("#assesmentForm").show();
		$("#assesmentForm").append("<br>");
		$("#assesmentForm").append("<br> <h2> Please answer the following questions in order to make the quality assessment. </h2>");

		qnum=1;
		for (i in $questions) {
			$table="<br><table border='1' width='100%'>";
			$table += " \
				<tr> \
				<th rowspan='2'> \
				"+$questionTypes[i]+" \
				</th> \
				<td colspan='4'> Quality Assesment </td> \
				</tr> \
				<tr> \
				<td>not yet</td> \
				<td>a little</td> \
				<td>fairly well</td> \
				<td>very much</td> \
				</tr> \
			";
			for (j in $questions[i]) {
				$table+="<tr>"
				$table+="<td>"+$questions[i][j]+"<span id='error"+qnum+"' style='color:red'></span></td>";
				$table+="\
				<td><label for='"+qnum+"1'><input type='radio' name='"+qnum+"' value='1' id='"+qnum+"1'></label></td> \
				<td><label for='"+qnum+"2'><input type='radio' name='"+qnum+"' value='2' id='"+qnum+"2'></label></td> \
				<td><label for='"+qnum+"3'><input type='radio' name='"+qnum+"' value='3' id='"+qnum+"3'></label></td> \
				<td><label for='"+qnum+"4'><input type='radio' name='"+qnum+"' value='4' id='"+qnum+"4'></label></td> \
				";
				$table+="</tr>"
				qnum+=1;
			}
			$table+="</table><br>";
			$("#assesmentForm").append($table);

		}
		$("#assesmentForm").append("<button id='submitForm'> Submit </button> ");
		$("#assesmentForm").append("<span id='errorSubmit' style='color:red' ></span>");
		$("#submitForm").click(function() {
			$("#errorSubmit").html("");
			var answers={};
			var allAnswered=true;
			for(var i=1;i<=21;i++) {
				$("#error"+i).html("");
				var ans=$("input[name="+i+"]:checked").val();
				if (!ans) {
					allAnswered=false;
					$("#error"+i).html("<br>Please answer this question.");
				}
				answers[i]=ans;
			}
 			console.log(answers);
 			if (!allAnswered) {
 				$("#errorSubmit").html("<br>You need to answer all questions before proceeding</br>");
 				return false;
 			}
			$("#assesmentForm").hide();
			
			$("#assess").show();
		});
		//$("#assess").show();
	});

	var $questionTypes= [
		"T  :  Teaching and learning processes",
		"I  :  Information and material content",
		"P  :  Presentation product and format",
		"S  :  System technical and technology"
	];

	var $questions= [
		[
			"Consider giving a study guide for how to use your OER, with an advance organiser, and navigational aids",
			"Use a learner-centred approach",
			"You should clearly state the reason and purpose of the OER, its relevance and importance",
			"It should be aligned to local wants and needs, and anticipate the current and future needs of the student",
			"Don't use difficult or complex language, and do check the readability to ensure it is appropriate to age/level",
			"Stimulate the intrinsic motivation to learn, eg through arousing curiosity with surprising anecdotes",
			"Monitor the completion rate, student satisfaction and whether the student recommends your OER to others",
			"Provide a way for the student and other teachers to give you feedback and suggestions on how to improve"
		],
		[
			"Make sure that the knowledge and skills you want the student to learn are up-to-date, accurate and reliable. Consider asking a subject-matter expert for advice",
			"Your perspective should support equality and equity, promoting social harmony, and be socially inclusive, law abiding and non-discriminatory",
			"Your content should be authentic, internally consistent and appropriately localised",
			"Encourage student input to create localised content for situated learning : draw on their prior learning and experience, their empirical and indigenous knowledge"
		],
		[
			"Be sure the open licence is clearly visible",
			"Ensure your OER is easy to access and engage",
			"Present your material in a clear, concise, and coherent way, taking care with sound quality",
			"Put yourself in your student's position to design a pleasing attractive design, using white-space and colours effectively, to stimulate learning",
			"Consider whether your OER will be printed out, usable off-line, or is suitable for mobile use",
			"Use open formats for delivery of OER to enable maximum reuse and remix"
		],
		[
			"Consider adding metadata tags about the content to help you and others later on to find your OER",
			"Try to use only free sourceware/software, and this should be easily transmissible across platforms",
			"Try to ensure your OER is easily adaptable, eg separate your computer code from your teaching content"
		]
	];



})