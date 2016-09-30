jQuery(document).ready(function(){
	jQuery("#email-template-form-template").next().html('<p class="text-primary" id="params"> You can use any of the following parameters in your email template <br>(customer), (manager), (feedback_link), (business), {customer}, {manager}, {business}, {feedback_link}</p>');

	var count = parseInt(jQuery("#no_of_locations").val()) - 1;

	if(jQuery(".use_info").length){
		var bname = jQuery("#profile-business_name").val();
		var address = jQuery("#profile-address").val();
		var city = jQuery("#profile-city").val();
		var state = jQuery("#profile-state").val();
		var zip = jQuery("#profile-zip").val();
		var phone = jQuery("#profile-phone").val();
		jQuery("#first_location #business-location-business_name-0").val(bname);
		jQuery("#first_location #business-location-address-0").val(address);
		jQuery("#first_location #business-location-city-0").val(city);
		jQuery("#first_location #business-location-state-0").val(state);
		jQuery("#first_location #business-location-zip-0").val(zip);
		jQuery("#first_location #business-location-phone-0").val(phone);

		jQuery(".use_info").click(function(){
			var data_count = parseInt(jQuery(this).data("count"));
			if(jQuery(this).is(":checked")){
				jQuery("#business-location-business_name-"+data_count).val(bname);
				jQuery("#business-location-address-"+data_count).val(address);
				jQuery("#business-location-city-"+data_count).val(city);
				jQuery("#business-location-state-"+data_count).val(state);
				jQuery("#business-location-zip-"+data_count).val(zip);
				jQuery("#business-location-phone-"+data_count).val(phone);
			} else {
				jQuery("#business-location-business_name-"+data_count).val("");
				jQuery("#business-location-address-"+data_count).val("");
				jQuery("#business-location-city-"+data_count).val("");
				jQuery("#business-location-state-"+data_count).val("");
				jQuery("#business-location-zip-"+data_count).val("");
				jQuery("#business-location-phone-"+data_count).val("");
			}
		});
	}
	if(jQuery(".add_location").length){
		jQuery("body").on("click", ".add_location", function(){
			if(jQuery(this).is(":checked")){
				var c = parseInt(jQuery(this).data("count")) + 1;
				var html = jQuery("#dummy_location").html();
				html = html.replace(/new_count/g, c);
				jQuery("#locations").append(html);
				jQuery(this).attr("disabled", "disabled");
			}
		});
	}

	jQuery(".dashboard.client.add_staff ul.nav.nav-tabs li.add_staff").click(function(){
		jQuery("ul.breadcrumb li.active").text(jQuery("#add_staff_title").val());
	});
	jQuery(".dashboard.client.add_staff ul.nav.nav-tabs li.import").click(function(){
		jQuery("ul.breadcrumb li.active").text(jQuery("#import_title").val());
	});


	// FOR EMAIL TEMPLATES IN ACCOUNT SETUP SCREEN-3...
	jQuery("ul.nav.nav-tabs.col-centered li a").click(function(){
		jQuery("ul.nav.nav-tabs.col-centered li").each(function(){
			var img = jQuery(this).find("img");
			var src = img.attr("src");
			var parts = src.split("/");
			var old_name = parts[parts.length - 1];
			if(old_name.substring(0,6) == "active"){
				var new_name = old_name.substring(6);
				src = src.replace(old_name, new_name);
				img.attr("src", src);
			}
		});
		var img1 = jQuery(this).find("img");
		var src = img1.attr("src");
		var parts = src.split("/");
		var old_name = parts[parts.length - 1];
		var new_name = "active"+old_name;
		src = src.replace(old_name, new_name);
		img1.attr("src", src);
	});



	// jQuery(".image").each(function(){
	// 	jQuery(this).find(".radio input[type=hidden]").remove();
	// });
	// jQuery(".title_radio").click(function(){
	// 	console.log(jQuery(this).val());
	// 	jQuery("div.image").removeClass("active");
	// 	jQuery("div.image img").each(function(){
	// 		var s = jQuery(this).attr("src");
	// 		var s_parts = s.split("/");
	// 		var s_part1 = s_parts[1];
	// 		var s_part2 = s_parts[2];
	// 		if(s_part2.substring(0,6) == "active"){
	// 			s_part2 = s_part2.substring(6);
	// 			jQuery(this).attr("src", "/"+s_part1+"/"+s_part2);
	// 		}
	// 	});
	// 	jQuery(this).parents("div.image").addClass("active");
	// 	jQuery(this).parents("div.image").find("img").addClass("active");
	// 	var old_src = jQuery(this).parents("div.image").find("img").attr("src");
	// 	var parts = old_src.split("/");
	// 	var part1 = parts[1];
	// 	var part2 = parts[2];
	// 	jQuery(this).parents("div.image").find("img").attr("src", "/"+part1+"/active"+part2);

	// 	var id = jQuery(this).parents("div.image").attr("id").substring(5);
	// 	jQuery("#hid_template_id").val(id.substring(1));
	// 	jQuery("#email-template-form-template").val(jQuery("div#templates.hide").find("input#template"+id).val());
	// 	if(jQuery(this).val() == "Default Email"){
	// 		jQuery("#email-template-form-template").next().html('<p class="text-primary" id="params"> You can use any of the following parameters in your email template <br>(customer), (manager), (feedback_link), (business), {customer}, {manager}, {business}, {feedback_link}</p>');
	// 	} else {
	// 		jQuery("#email-template-form-template").next().html('');
	// 	}
	// });


	if(jQuery(".emailtemplates #emailtemplatemap-template_id").length && jQuery(".emailtemplates #templates.hide").length){
		var curr_id = jQuery(".emailtemplates #emailtemplatemap-template_id").val();
		jQuery("#emailtemplatemap-title").val(jQuery(".hide #title_"+curr_id).val());
		jQuery("#emailtemplatemap-template").val(jQuery(".hide #template_"+curr_id).val());
		if(curr_id == 2){
			jQuery("#params").removeClass("hide");
		}
		jQuery(".emailtemplates #emailtemplatemap-template_id").change(function(){
			jQuery("#emailtemplatemap-title").val(jQuery(".hide #title_"+jQuery(this).val()).val());
			jQuery("#emailtemplatemap-template").val(jQuery(".hide #template_"+jQuery(this).val()).val());
			if(jQuery(this).val() == 2){
				jQuery("#params").removeClass("hide");
			} else {
				jQuery("#params").addClass("hide");
			}
		});
	}
	if(jQuery("#emailtemplatemap-template_id").val() == 2){
		jQuery("#params").removeClass("hide");
	}
	if(jQuery(".emailtemplates.edit #emailtemplatemap-active").val() == 1){
		jQuery(".emailtemplates.edit #emailtemplatemap-active").attr("checked", "checked");
	}
	jQuery(".emailtemplates.edit #emailtemplatemap-active").click(function(){
		if(jQuery(this).is(":checked")){
			jQuery(this).val("1");
		} else {
			jQuery(this).val("0");
		}
	});
});