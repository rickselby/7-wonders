$(document).ready(function() {

	$("#random").click(function()
	{
		$("#wonders OPTION:selected").each(function() {
			$(this).prop('selected', false);
		});
		var maxNum = parseInt($("#randAmount").val());
		shuffle($("#wonders OPTION")).slice(0,maxNum).each(function() {
			console.log($(this).html());
			$(this).prop('selected', true);
		});
/*		$(".selectedNum").html(randomElements.length);
		if(randomElements.length==maxNum) {
			$(".buttonToProceed").removeClass("notShown");
		}
		*/
	});
});

function shuffle(array) {
  var m = array.length, t, i;

  // While there remain elements to shuffle…
  while (m) {

    // Pick a remaining element…
    i = Math.floor(Math.random() * m--);

    // And swap it with the current element.
    t = array[m];
    array[m] = array[i];
    array[i] = t;
  }

  return array;
}