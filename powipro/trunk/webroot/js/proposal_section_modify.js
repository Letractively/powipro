$(document).ready(function () {
	$("#add_section").bind("click", function (event) {
		addProposalSection();
	});
	
	$(".proposal_section").filter(":first").bind("change", function (event) {
		$.ajax({
			url: '/spl/sections/info/' + $('.proposal_section option:selected').filter(":first").val(),
			success: function (data) { $("#sectionInfo").replaceWith(data); }
		});
	});
	
	$.ajax({
		url: '/spl/sections/info/' + $('.proposal_section option:selected').filter(":first").val(),
		success: function (data) { $(".proposal_section").filter(":first").after(data); }
	});
});

function addProposalSection() {
	if (typeof addProposalSection.counter == 'undefined') {
		addProposalSection.counter = 0;
	}
	
	addProposalSection.counter++;
	var elem = $(".proposal_section").filter(":first").clone();
	elem.attr("id", "newSecondarySection" + addProposalSection.counter);
	elem.attr("name", "data[newSecondarySection][" + addProposalSection.counter + "][section_id]");

       	if (addProposalSection.counter == 1) {
       		$("#sectionInfo").after(elem);
       	} else {
       		$(".deleteNewSecondarySection").filter(":last").after(elem);
       	}
       	$("#newSecondarySection" + addProposalSection.counter)
       		.after('<a href="#" class="deleteNewSecondarySection" '
       			+ 'id="deleteNewSecondarySection' + addProposalSection.counter + '" ' +
		       'onClick="removeNewSection(' + addProposalSection.counter + ');">L&ouml;schen</a>');

}

function removeNewSection (section_id) {
		$("#newSecondarySection" + section_id).remove();
		$("#deleteNewSecondarySection" + section_id).remove();
		addProposalSection.counter--;
}

function removeSection (section_id) {
	$("#SecondarySection" + section_id + "SectionId").remove();
	$("#deleteSecondarySection" + section_id).remove();
	$.ajax("/spl/proposal_sections/delete/" + section_id);
}
