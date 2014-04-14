$(document).ready(function() {   

	$("#pollAjaxLoader").hide(); //hide the ajax loader
	$("#pollMessage").hide(); //hide the ajax loader
	$("#pollSubmit").click(function() {
		var pollAnswerVal = $('input:radio[name=pollAnswerID]:checked').val();//Getting the value of a selected radio element.
		if ($('input:radio[name=pollAnswerID]:checked').length) {
			$("#pollSubmit").attr("disabled", "disabled"); //disable the submit button
			$("#pollAjaxLoader").show(); //show the ajax loader
			$.ajax({  
				type: "POST",  
				url: "inc/functions.php",  
				data: { pollAnswerID: pollAnswerVal, action: "vote" },
				success: function(theResponse) { 
					//the functions.php returns a response like "1|13|#ffcc00-2|32|#00ff00-3|18|#cc0000-63" which the first number is the answerID, second is the points it has and third is the color for that answer's graph. The last number is the sum of all points for easilt calculating percentages.
					if (theResponse == "voted") { 
						$("#pollAjaxLoader").hide(); //hide the ajax loader
						$("#pollMessage").html("sorry, you already voted.").fadeTo("slow", 1);
					} else {
						$("#showResults").attr("disabled", "disabled"); //disable the submit button
						var numberOfAnswers 		= (theResponse).split("-").length-2;//calculate the number of answers
						var splittedResponse 		= (theResponse).split("-");
						var pollAnswerTotalPoints 	= splittedResponse[numberOfAnswers+1];
	
						for (i=0;i<=numberOfAnswers;i++)
						{
							var splittedAnswer 		= (splittedResponse[i]).split("|");
							var pollAnswerID 		= (splittedAnswer[0]);
							var pollAnswerPoints 	= (splittedAnswer[1]);
							var pollAnswerColor 	= (splittedAnswer[2]);
							var pollPercentage		= (100 * pollAnswerPoints / pollAnswerTotalPoints);
							$(".pollChart" + pollAnswerID).css("background-color",pollAnswerColor);
							$(".pollChart" + pollAnswerID).animate({width:pollPercentage + "%"});
							$("#pollAnswer" + pollAnswerID).html(" (" + Math.round(pollPercentage) + "% - " + pollAnswerPoints + " votes)");
							$("#pollRadioButton" + pollAnswerID).attr("disabled", "disabled"); //disable the radio buttons
						}
						$("#pollAjaxLoader").hide(); //hide the ajax loader again
					}
				}  
			});  
			return false; 
	
	
		
		} else {
			$("#pollMessage").html("please select an answer.").fadeTo("slow", 1, function(){
				setTimeout(function() {
					$("#pollMessage").fadeOut("slow");
				}, 3000);																		 
			});
			return false;
		}
	
	});

	$("#showResults").click(function() {
		$("#pollAjaxLoader").show(); //show the ajax loader
		$("#showResults").attr("disabled", "disabled"); //disable the submit button
		var pollAnswerID = $('input[name=pollIDResult]').val();//Getting the value of a selected radio element.
		$.ajax({
		type: "POST",
		url: "inc/functions.php",
		data: { pollAnswerID: pollAnswerID, action: "getResults" },
		success: function(theResponse) {

		var numberOfAnswers = (theResponse).split("-").length-2;//calculate the number of answers
		var splittedResponse = (theResponse).split("-");
		var pollAnswerTotalPoints = splittedResponse[numberOfAnswers+1];

		for (i=0;i<=numberOfAnswers;i++)
		{
		var splittedAnswer = (splittedResponse[i]).split("|");
		var pollAnswerID = (splittedAnswer[0]);
		var pollAnswerPoints = (splittedAnswer[1]);
		var pollAnswerColor = (splittedAnswer[2]);
		var pollPercentage = (100 * pollAnswerPoints / pollAnswerTotalPoints);
		$(".pollChart" + pollAnswerID).css("background-color",pollAnswerColor);
		$(".pollChart" + pollAnswerID).animate({width:pollPercentage + "%"});
		$("#pollAnswer" + pollAnswerID).html(" (" + Math.round(pollPercentage) + "% – " + pollAnswerPoints + " votes)");
		}
		$("#pollAjaxLoader").hide(); //hide the ajax loader again
		$("#pollSubmit").attr("disabled", "disabled"); //disable the submit button
		}
		});
		return false;
	});
	
});