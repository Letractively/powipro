function reloadCourseTypeSelect (select_id, json_data) {
	$(select_id).empty();
	
	if (json_data.length == 0) {
		$(select_id).hide();
	} else {
		$(select_id).show();
	}
	
	$.each(json_data, function(index, value) {
		$(select_id).append('<option value="' + value.CourseType.id + '">' + value.CourseType.description + '</option>');
	});
}

function setSectionInfo (select_id, json_data) {
	if (json_data["Section"] != null) {
		$(select_id).show();
		
	} else {
		$(select_id).hide();
	}
	
	$(select_id + " > .title").empty();
	$(select_id + " > .description").empty();
	$(select_id + " > .comment").empty();
		
	$(select_id + " > .title").append(json_data["Section"]["abbreviation"] + " - " + json_data["Section"]["name"]);
	$(select_id + " > .description").append(json_data["Section"]["description"]);
	$(select_id + " > .comment").append(json_data["Section"]["comment"]);
}