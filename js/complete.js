$(document).ready(function() {
	sumCols();
	$('INPUT').change(sumCols);

	uniqueSelect("WonderID");
	$('SELECT[name^="WonderID"]').change(function() {
		uniqueSelect("WonderID");
	});

});

// TESTING FUNCTION - fill the boxes with random numbers
function fillBoxes()
{
	$('INPUT[type="text"]').each(function() {
		$(this).val(Math.floor(Math.random() * 20));
	});
	sumCols();
}

function sumCols(){

	var totals=[0,0,0,0,0,0,0];

    $("TABLE tr.rowSum").each(function() {
        $(this).find('TD INPUT').each(function(i){
            totals[i]+=parseInt( $(this).val() || 0 );
        });
    });

	console.log(totals);

    $("TD.totalCell").each(function(i){
        $(this).html(totals[i]);
    });

}