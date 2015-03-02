$ ( document ).ready ( function() {

	$articleId=wgArticleId;
	$("#title").html("The id of the page is : "+$articleId);

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