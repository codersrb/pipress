// Generate JSON from /api/doc


/*
Plzwork

METHOD: li.operation.get .heading h3 span.http_method i
PATH: li.operation .heading h3 span.path
Documentation: p
Filters: [
	{
		Name: table tbody tr td
		Information: later
	}
]*/

function tableToJson(table) {
    var data = [];
    // first row needs to be headers
    var headers = [];
    var headless = false;
    console.log($(table).children("thead")[0]);
    if ($(table).children("thead").length == 0) {
    	console.log("Headless");
    	headless = true;
    	

    	for (var i=0; i<table.rows.length; i++) {
	        headers[i] = table.rows[i].cells[0].innerHTML.trim();
	    }

    } else {
    	for (var i=0; i<table.rows[0].cells.length; i++) {
	        headers[i] = table.rows[0].cells[i].innerHTML.trim();
	    }

    }

    if (headless) {
    	data = {};
    	// go through cells
	    for (var i=0; i<table.rows.length; i++) {

	        var tableRow = table.rows[i];
	        var rowData = "";

	        for (var j=1; j<tableRow.cells.length; j++) {


	            newEntry = $(tableRow.cells[j]).text().trim();

	            if (newEntry  != undefined) {
		        	console.log(typeof newEntry);
					if (IsJsonString(newEntry.trim())) {
						newEntry  = JSON.parse(newEntry.trim());
					}
				}

				rowData = newEntry;
	        }

			
	        data[headers[i]] = rowData;
	    }   
    }

    else {
    	// go through cells
	    for (var i=1; i<table.rows.length; i++) {

	        var tableRow = table.rows[i];
	        var rowData = {};

	        for (var j=0; j<tableRow.cells.length; j++) {


	            rowData[ headers[j] ] = $(tableRow.cells[j]).text().trim();

	            if (rowData[ headers[j] ]  != undefined) {
		        	console.log(typeof rowData[ headers[j] ] );
					if (IsJsonString(rowData[ headers[j] ].trim())) {
						rowData[ headers[j] ]  = JSON.parse(rowData[ headers[j] ].trim());
					}
				}

	        }

			
	        data.push(rowData);
	    }   
    }
    return data;
}

function IsJsonString(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}

points = [];

function recurseTable(table) {
	
	//var tchild = [];
	/*$(table).children().each(function (idx, tag) {
		if ($(tag).prop("nodeName") == "TABLE") {
			tchild.push(recurseTable(tag));
			//$(tag).html(recurseTable(tag));
		}
	});*/

	obj = tableToJson(table);
	for (var key in obj) {
		if (typeof obj[key] == typeof "string") {
			obj[key] = obj[key].trim();
		}

		if (IsJsonString(obj[key])) {
			obj[key] = JSON.parse(obj[key]);
		}
	}
	//$.each(tchild, function (idx, child) {
		//obj.push(child);
	//});
	
	return obj;
}

function jsonATable (tabl) {
	while ($(tabl).find("table").sort(function(x1, x2) {
		return $(x2).parents().length - $(x1).parents().length;
	}).length > 0) {

		points.push(recurseTable($(tabl).find("table").sort(function(x1, x2) {
		return $(x2).parents().length - $(x1).parents().length;
	})[0]));

		var rent = $($(tabl).find("table").sort(function(x1, x2) {
		return $(x2).parents().length - $(x1).parents().length;
	})[0]).parent();

		$($(tabl).find("table").sort(function(x1, x2) {
		return $(x2).parents().length - $(x1).parents().length;
	})[0]).remove();

		$(rent).html(JSON.stringify(points.pop()).trim().replace(/\\\\/g, "\\\\\\\\"));
		//break;

	}

	console.log(recurseTable($("<table>" + $(tabl).html() + "</table>")[0]));
	return JSON.stringify(recurseTable($("<table>" + $(tabl).html() + "</table>")[0]));
}

endpoints = [];

$("li.operation").each(function (idx, first) {
	endpoint = {};
	endpoint.Method = $(first).find(".heading h3 span.http_method i").text();
	endpoint.Path = $(first).find(".heading h3 span.path").text().trim();

	$(first).find(".pane.content.selected h4").each(function (idx, tag) {
		nextTag = $(tag).next()[0];

			if (IsJsonString($(nextTag).html().trim())) {
				rowData = JSON.parse($(nextTag).html().trim());

			}

			else if ($(nextTag).prop("nodeName") == "TABLE") {
				nextTag = JSON.parse(jsonATable(nextTag));
			} 

			else if ($(nextTag).prop("nodeName") == "UL" || $(nextTag).prop("nodeName") == "LI" || $(nextTag).prop("nodeName") == "A") {
				nextTag = $(nextTag).text().trim();
			}

			else {
				nextTag = $(nextTag).text().trim();
			}

			endpoint[$(tag).text()] = nextTag;
	});

	endpoints.push(endpoint);
});

var newEPs = [];
$.each(endpoints, function (idx, point) {
	var method = point["Method"];
	if (method.indexOf("|") !== -1) {
		parts = method.split("|");
		$.each(parts, function(idx, part) {
			point["Method"] = part;
			newEPs.push(JSON.parse(JSON.stringify(point)));
		});
	}
	else {
		newEPs.push(point);
	}
});

$("body").html("<p>" + JSON.stringify(newEPs).replace(/\\\\\\\\/g, "\\\\").replace(/\&/g, "&amp;").replace(/\</g, "&lt;") + "</p>");

