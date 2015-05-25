$ ( document ).ready ( function() {

	$articleId=wgArticleId;
	console.log("The id of the page is : "+$articleId);

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
			$("#showDetailedInfo").hide();
		}
	});

	$("#showDetailedInfo").click(function(){


		$("#assesmentForm").html("");
		$("#assesmentForm").show();

		$("#assesmentForm").append("<br> <h2>Below is the list of QA assessments. </h2><br>");

		$("#showDetailedInfo").hide();

		$.get(wgScriptPath+"/api.php?action=qaAssesments&qaPageNo="+$articleId+"&qatype=detailed&format=json",function(data) {

			table = "<br><table border='1' class='prettytable' width='100%'>";
			table += "\
				<tr>\
					<th> User Name </th>\
					<th> QA Score </th>\
					<th> T </th>\
					<th> I </th>\
					<th> P </th>\
					<th> S </th>\
					<th> Get Detailed Assessments </th>\
				</tr>\
			";

			for (i in data.qaAssesments) {

				table += "<tr>";

				userQA = data.qaAssesments[i];

				table += "<td>"+userQA.username+"</td>";
				table += "<td>"+userQA.avg+"</td>";
				table += "<td>"+userQA.t+"</td>";
				table += "<td>"+userQA.i+"</td>";
				table += "<td>"+userQA.p+"</td>";
				table += "<td>"+userQA.s+"</td>";
				table += "<td align='center'>"+"<button class='details' key='"+i+"' username='"+userQA.username+"' > Detailed assessments </button></td>";

				table += "</tr>";
			};

			table += "</table>";

			$("#assesmentForm").append(table);
			$("#assesmentForm").append("<span id='detailsHolder'></span>")

			$(".details").click( function() {

				evalDetails = "";
				numTemp = 1;
				key = $(this).attr("key");
				answersTemp = data.qaAssesments[key].answers;
				console.log(answersTemp);
				for (i in  $questionTypes ) {
					evalDetails += "<h1>" + $questionTypes[i] + "</h1>";

					evalDetails += "<ul>";
					for (j in $questions[i]) {
						evalDetails += "<li>";
						evalDetails += $questions[i][j] + " : ";
						evalDetails += "<b>"+$answerTypes[answersTemp[numTemp]]+"</b>";
						evalDetails += "</li>";
						numTemp += 1;
					}
					evalDetails += "</ul>";

				}


				text = "\
					<div id='dialog' title='QA evaluation answers for user "+$(this).attr("username")+"'> \
					  "+evalDetails+"\
					</div>	\
				";
				$("#detailsHolder").html(text);
				$( "#dialog" ).dialog({ width: "75%"});
				return false;
			});

			console.log(data);

		});


	});

	$("#assess").click(function(){


		$.get(wgScriptPath+"/api.php?action=query&meta=userinfo&format=json",function(data){ 
			if (data.query.userinfo.id === 0) {
				alert("You need to be logged in to make an assessment");
				return false;
			}

			$("#showDetailedInfo").hide();

			var submitData={};

	 		submitData.qatype = "check";
			submitData.qaPageNo = $articleId;

			$.post(wgScriptPath+"/api.php?action=qaSubmit&format=json",submitData,function(data){
				console.log(data);
				if (data.qaSubmit.alreadySubmitted ) {
					alert("You have already submitted an assesment for this article. Your submission will be modified.")
				}
			});

			activateCloseProtection();

			//console.log("Assess fn callses");
			$("#assess").hide();
			$("#assesmentForm").html("");
			$("#assesmentForm").show();
			$("#assesmentForm").append("<br>");
			$("#assesmentForm").append("<br> <h2> Please answer the following questions in order to make the quality assessment. </h2>");

			qnum=1;
			for (i in $questions) {
				$table="<span id='qaform"+i+"'>";
				$table+="<br><table border='1' class='prettytable' width='100%'>";
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
				if (i!=3)
					$table+="<button id='submitForm"+i+"'>Next</button> ";
				else
					$table+="<button id='submitForm"+i+"'>Submit</button> ";

				$table+="<span id='errorSubmit"+i+"' style='color:red' ></span>";
				$table+="</span>";
				$("#assesmentForm").append($table);
			}

			$("#qaform1").hide();
			$("#qaform2").hide();
			$("#qaform3").hide();
			
			var answers={};		

			$("#submitForm0").click(function() {
				$("#errorSubmit0").html("");
				
				var allAnswered=true;
				for(var i=1;i<=8;i++) {
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
	 				$("#errorSubmit0").html("<br>You need to answer all questions before proceeding</br>");
	 				return false;
	 			}
	 			$("#qaform0").hide();
	 			$("#qaform1").show();
			});

			$("#submitForm1").click(function() {
				$("#errorSubmit1").html("");
				
				var allAnswered=true;
				for(var i=9;i<=12;i++) {
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
	 				$("#errorSubmit1").html("<br>You need to answer all questions before proceeding</br>");
	 				return false;
	 			}
	 			$("#qaform1").hide();
	 			$("#qaform2").show();
			});

			$("#submitForm2").click(function() {
				$("#errorSubmit2").html("");
				
				var allAnswered=true;
				for(var i=13;i<=18;i++) {
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
	 				$("#errorSubmit2").html("<br>You need to answer all questions before proceeding</br>");
	 				return false;
	 			}
	 			$("#qaform2").hide();
	 			$("#qaform3").show();
			});

			$("#submitForm3").click(function() {
				$("#errorSubmit3").html("");
				
				var allAnswered=true;
				for(var i=19;i<=21;i++) {
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
	 				$("#errorSubmit3").html("<br>You need to answer all questions before proceeding</br>");
	 				return false;
	 			}

				var submitDataFinal={};

		 		submitDataFinal.qatype = "submit";
				submitDataFinal.qaPageNo = $articleId;
				submitDataFinal.qaAnswer = JSON.stringify(answers);

				$.post(wgScriptPath+"/api.php?action=qaSubmit&format=json",submitDataFinal,function(data){
					console.log(data);
					if (data.qaSubmit.success) {
						alert("Successfully submitted the QA evaluation.Please refresh this page to see your changes.")
						$("#showDetailedInfo").show();
						$("#assesmentForm").html("");
						deactivateCloseProtection();
						$("#assess").show();
					}
				});

	 		
			});
			//$("#assess").show();
		});

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

	var $answerTypes = [
		"",
		"not yet",
		"a little",
		"fairly well",
		"very much"
	];

})

function activateCloseProtection() {
	window.onbeforeunload = function() {
    	return "You have not completed the form. The data will not be saved if you do not complete and submit. Are you sure you want to quit?";
  	}
};

function deactivateCloseProtection() {
	window.onbeforeunload = null;
};
