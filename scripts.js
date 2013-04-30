/*
This is the object oriented code for listing the urban concentration by javascript and jQuery.
*/

function urbanConcentrationList() {

	var obj = JSON.parse(str) ; /*get the JSON string from API*/
	var click = false;
	var mouseRelative = 0;
	var order = true;
	
	/*make the urban concentration list reversed in table*/
	var reverseRank = function() {
		$('#rankSort') . click(function() {
			obj["data"].reverse() ;
			listDataSet() ;
			if(order) {
				$('#rankSort') . val("Rank(DESC)") ;
				order = false;
			}
			else {
				$('#rankSort') . val("Rank(ASC)") ;
				order = true;
			}
		}) ;
	}
	
	/*use jquery to write list in table and option selection form in html*/
	var listDataSet = function() {
		$('#UrbanConTable') . stop() ;
		$('.dataset') . remove() ;
		for(var key	in obj["data"]) {
			$('#UrbanConTable') . append("<tr class = \"dataset\"><td style = \"width: 60px; text-align: center;\">" + obj["data"][key][0] + "</td><td style = \"width: 260px; text-align: center;\">" + obj["data"][key][1] + "</td><td style = \"width: 120px;\">" + obj["data"][key][2] + "</td><td style = \"width: 120px;\">" + obj["data"][key][3] + "</td><td style = \"width: 120px;\">" + obj["data"][key][4] + "</td></tr>") ;
		}
		for(var i = 0; i < 19; i++) {
			$('#UrbanConTable') . append("<tr class = \"dataset\"><td style = \"width: 60px; text-align: center;\"></td><td style = \"width: 260px; text-align: center;\"></td><td style = \"width: 120px;\"></td><td style = \"width: 120px;\"></td><td style = \"width: 120px;\"></td></tr>") ;
		}
		$('#UrbanConTable') . css('top', '-1px') ;
	}
	
	/*enable mouse to drag and browse the table*/
	var mouseDrag = function() {
		$('#UrbanConTable') . mousedown(function(e) {
			click = true;
			mouseRelative = e . pageY - parseInt($(this) . css('top') , 10) ;
		}) ;
		$(document) . mouseup(function() {
			click = false;
		}) ;
		$('#UrbanConTable') . mousemove(function(e) {
			if(click) {
				var tableTop = e . pageY - mouseRelative;
				if(tableTop >= -1) {
					$(this) . css('top', '-1px') ;
				}
				else if(tableTop <= -6007) {
					$(this) . css('top', '-6007px') ;
				}
				else{
					$(this) . css('top', e . pageY - mouseRelative + 'px') ;
				}
			}
		}) ;
	}
	
	/*move to the sepecific rank in the table*/
	var searchRank = function(data) {
		var rank = parseInt(JSON.parse(data) , 10) ;
		var newPosition = -1;
		if(order) {
			newPosition = rank * (-26) + 25;
		}else{
			newPosition = (233 - rank) * (-26) + 25;
		}
		$('#UrbanConTable') . animate({top: + newPosition + 'px'}, 500, function() {
		}) ;
	}
	
	/*use AJAX combine with jQuery to search data from option selection form*/
	var listAjax = function() {
		$("#countries") . change(function() {
			$.post("UrbanConAPI.php", {
				Name: $(this) . val()
			},
			function(data,status) {
				if(status == "success") {
					searchRank(data) ;
				}
			}) ;
		}) ;
	}
	
	/*run this object*/
	this . Execute = function() {
		$('#UrbanConTable') . attr('unselectable', 'on') . css('UserSelect', 'none') . css('MozUserSelect', 'none') ; /*unable selection*/
		for(var key	in obj["country"]) {
			$('#countries') . append("<option value=\"" + obj["country"][key] +"\">" + obj["country"][key] + "</option>") ;
		}
		listDataSet() ;
		reverseRank() ;
		mouseDrag() ;
		listAjax() ;
	}
}

/*new the urban concentration list object once the page is load by jQuery*/
$(document) . ready(function() {
	var go = new urbanConcentrationList() ;
	go . Execute() ;
}) ;

