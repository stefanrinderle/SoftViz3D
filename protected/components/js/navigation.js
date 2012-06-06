$(document).ready(function() {
	$("#sidebar #navigation").buttonset().click(function(event) {
		var target = $(event.target).attr('id');

		if (target) {
			setRuntime(target, 'the_x3delement');
		}
	});

	$("#sidebar #navigation #reset").button().click(function() {
		var configure = document.getElementById('the_x3delement');
		configure.runtime.resetView();
		}
	);

	$("#sidebar #navigation #decrease").button().click(function() {
		var configure = document.getElementById('the_x3delement');
		$('#speedValue').text(configure.runtime.speed(configure.runtime.speed() - 1));
		}
	);

	$("#sidebar #navigation #increase").button().click(function() {
		var configure = document.getElementById('the_x3delement');
		$('#speedValue').text(configure.runtime.speed(configure.runtime.speed() + 1));
		}
	);
	
});

function setRuntime(typename, id) {
	var configure = document.getElementById(id);
	
	switch (typename)
	{
		case "walk": configure.runtime.walk(); break;
		case "fly": configure.runtime.fly(); break;
		case "examine": configure.runtime.examine(); break;
		case "lookAround": configure.runtime.lookAround(); break;
		case "lookAt": configure.runtime.lookAt(); break;
		case "game": configure.runtime.game(); break;
		
		case "resetView": configure.runtime.resetView(); break;
		case "uprightView": configure.runtime.uprightView(); break;
		case "showAll": configure.runtime.showAll(); break;
		case "nextView": configure.runtime.nextView(); break;
		case "prevView": configure.runtime.prevView(); break;
		case "upSpeed": setSpeed("up", id); break;
		case "downSpeed": setSpeed("down", id); break
		default: configure.runtime.examine();
	}
}