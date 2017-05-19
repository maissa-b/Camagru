function	switch_button(id_button) {
	var button = document.getElementById(id_button);

	var frame1 = document.getElementById('frame1');
	var frame2 = document.getElementById('frame2');
	var frame3 = document.getElementById('frame3');

	function	check_radio() {
		if (frame1.checked || frame2.checked || frame3.checked) {
			button.style.pointerEvents = 'auto';
		} else {		
			button.style.pointerEvents = 'none';
		}
	}

	frame1.addEventListener('click', check_radio());
	frame2.addEventListener('click', check_radio());
	frame3.addEventListener('click', check_radio());
}