//Variables 

//Hooks 
//Attach of events related to objects in the interface
//The interface, has not any event into the html view page
$(function () {
	defineClicks();
});

//Functions 
function defineClicks() {
	$('.multiselect').selectpicker();
	$("body").off("click", ".btn-execute-crm").on("click", ".btn-execute-crm", function () {
		_FUNCTIONS.onReportsCRM($(this));
	});
}
