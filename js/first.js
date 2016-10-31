

function firstInit() {
	smooth_show($('#first-modal'));

}

function firstNext() {
	$('.first1').hide();
	$('.first2').show();
	$('.first2 button').click(function () {
		smooth_show($('#scanner-modal'));
		scan();
		$('.first2').hide();
		$('.first3').show();
		$('.first3 button').click(function () {
			$('#first').hide();
			loadall();
		});
	});
}