jQuery(document).ready(function($){
	
	$('#years-range-submit').hide();

	$("#min_years,#max_years").on('change', function () {

	  $('#years-range-submit').show();

	  var min_price_range = parseInt($("#min_years").val());

	  var max_price_range = parseInt($("#max_years").val());

	  if (min_price_range > max_price_range) {
		$('#max_years').val(min_price_range);
	  }

	  $("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });
	  
	});


	$("#min_years,#max_years").on("paste keyup", function () {                                        

	  $('#price-range-submit').show();

	  var min_price_range = parseInt($("#min_years").val());

	  var max_price_range = parseInt($("#max_years").val());
	  
	  if(min_price_range == max_price_range){

			max_price_range = min_price_range + 100;
			
			$("#min_years").val(min_price_range);		
			$("#max_years").val(max_price_range);
	  }

	  $("#slider-range").slider({
		values: [min_price_range, max_price_range]
	  });

	});


	$(function () {
	  $("#slider-range").slider({
		range: true,
		orientation: "horizontal",
		min: 2001,
		max: 2024,
		values: [2001, 2024],
		step: 1,

		slide: function (event, ui) {
		  if (ui.values[0] == ui.values[1]) {
			  return false;
		  }
		  
		  $("#min_years").val(ui.values[0]);
		  $("#max_years").val(ui.values[1]);

		}
	  });

	  $("#min_years").val($("#slider-range").slider("values", 0));
	  $("#max_years").val($("#slider-range").slider("values", 1));

	});

	// $("#slider-range,#price-range-submit").click(function () {

	//   var min_price = $('#min_price').val();
	//   var max_price = $('#max_price').val();

	//   $("#searchResults").text("Here List of products will be shown which are cost between " + min_price  +" "+ "and" + " "+ max_price + ".");
	// });

});