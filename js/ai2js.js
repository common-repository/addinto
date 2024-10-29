var addIntoServices = ["More", "Separator", "Email", "Email client", "Bookmark", "Print", "AIM Share", "Amazon", "Aol Mail", "Bebo", "Bit.ly", "BibSonomy", "BlinkList", "Blogasty", "Blogger", "Blogmarks", "Bookmarks", "Box.net", "Care2", "CiteULike", "Connotea", "Current", "Delicious", "Digg", "Diigo", "DZone", "Evernote", "Fark", "Facebook", "Faves", "Favoriten", "Folkd", "Fresqui", "FriendFeed", "funP", "Fuzz", "Gmail", "Google Bookmarks", "Google Buzz", "Google Plus", "Google Reader", "Google Translate", "HelloTxt", "HEMiDEMi", "Hotmail", "Hyves", "Identica", "Instapaper", "Jamespot", "Jumptags", "Kirtsy", "linkaGoGo", "LinkedIn", "Live Favorites", "Live Spaces", "Livefavoris", "LiveJournal", "meneame", "Messenger", "Mister Wong", "Mixx", "Multiply", "MySpace", "N4G", "Netlog", "Netvibes Share", "Netvouz", "Newsvine", "NowPublic", "OKNOtizie", "Orkut", "oneview", "Ping", "Plaxo", "Plurk", "Posterous", "PrintFriendly", "Propeller", "Protopage", "Pusha", "Read It Later", "reddit", "Renren", "Scoopeo", "Segnalo", "Skyrock", "Slashdot", "Sphere", "Sphinn", "Squidoo", "StumbleUpon", "symbaloo", "TapeMoi", "Technorati", "Tumblr", "TweetMeme", "Twitter", "TypePad", "Viadeo", "Vkontakte", "Web2PDF", "Webnews", "Wikio Vote", "WordPress", "Wykop", "Xanga", "Xerpi", "Yahoo", "Yahoo Buzz", "Yahoo Mail", "YiGG", "Yoolink"];

function addinto_add_service(s1,id1,id2,id3,id4,id5) {
	var s2 = s1.toLowerCase().replace(/\./g, '_').replace(/\s+/g, '_');
	var v1 = jQuery('#'+id2).val();
	var v2 = v1.toLowerCase().replace(/\./g, '_').replace(/\s+/g, '_');
	if(v2.search(s2+',') != -1) {
		v = v1.replace(s1+',', '');
		jQuery('#'+id2).val(v);
		jQuery('#'+id5+' .'+id1).css({'background-color':'#fff'});
		jQuery('#'+id3+' a.addinto_button_' + s2).remove();
	} else {
		jQuery('#'+id2).val(v1 + s1 + ',');
		jQuery('#'+id5+' .'+id1).css({'background-color':'#ccc'});
		if(s2 == 'separator')
			jQuery('#'+id3).append('<a title="Click to remove : ' + s1 + '" onclick="jQuery(\'#'+id5+' a.addinto_srv_n_' + s2 + '\').click();" href="javascript:void(0);" class="addinto_button_' + s2 + '"><span class="ai2_icon_sep ai2_' + s2 + '"></span><a>\n');
		else
			jQuery('#'+id3).append('<a title="Click to remove : ' + s1 + '" onclick="jQuery(\'#'+id5+' a.addinto_srv_n_' + s2 + '\').click();" href="javascript:void(0);" class="addinto_button_' + s2 + '"><span class="ai2_icon_bar ai2_' + s2 + '"></span><a>\n');
	}
	var v3 = jQuery('#'+id2).val();
	jQuery('#'+id4).val(v3.substr(0,v3.length-1));
}

function addinto_sort_services(id2,id3,id4,id5) {
	var v3 = jQuery('#'+id2).val();
	if(/Separator,/.test(v3)) {
		jQuery('#'+id2).val(v3.replace('Separator,', '') + 'Separator,');
		jQuery('#'+id3+' a.addinto_button_separator').appendTo(jQuery('#'+id3));
		jQuery(jQuery('#'+id3)).append('\n');
	}
	var v4 = jQuery('#'+id2).val();
	if(/More,/.test(v4)) {
		jQuery('#'+id2).val(v4.replace('More,', '') + 'More,');
		jQuery('#'+id3+' a.addinto_button_more').appendTo(jQuery('#'+id3));
		jQuery(jQuery('#'+id3)).append('\n');
	} else if(/Separator,/.test(v4)) {
		jQuery('#'+id2).val(v4.replace('Separator,', ''));
		jQuery('#'+id3+' a.addinto_button_separator').remove();
		jQuery('#'+id5+' .addinto_srv_n_separator').css({'background-color':'#fff'});
	}
	var v5 = jQuery('#'+id2).val();
	if(v5 == 'Separator,More,') {
		jQuery('#'+id2).val(v5.replace('Separator,', ''));
		jQuery('#'+id3+' a.addinto_button_separator').remove();
		jQuery('#'+id5+' .addinto_srv_n_separator').css({'background-color':'#fff'});
	}
	var v6 = jQuery('#'+id2).val();
	jQuery('#'+id4).val(v6.substr(0,v6.length-1));
}

function addinto_build_services(id1,id2,id3,id4) {
	var addinto_services_current = jQuery('#'+id1).val().split(',');
	var addIntoServices_ = [];
	switch(id1) {
		case 'ai2_sharebox_srvs':
			addIntoServices_ = addIntoServices;
		break;
		case 'ai2_srv':
			for(i=0;i<addIntoServices.length;i++) {
				if('Separator' != addIntoServices[i]) addIntoServices_.push(addIntoServices[i]);
			}
		break;
		case 'ai2_srvs':
			for(i=0;i<addIntoServices.length;i++) {
				if('Separator' != addIntoServices[i] && 'More' != addIntoServices[i]) addIntoServices_.push(addIntoServices[i]);
			}
	}
	
	jQuery.each(addIntoServices_, function(key, val) {
		var s = val.toLowerCase().replace(/\./g, '_').replace(/\s+/g, '_');
		var a = jQuery('<a class="addinto_button_links addinto_button_links_border addinto_srv_n_' + s +'"><span class="ai2_icon ai2_' + s + '"></span>' + addIntoServices_[key] + '</a>');
		jQuery('#'+id4).append(a);
		jQuery('#'+id4+' a.addinto_srv_n_' + s).bind('click', function() {
			addinto_add_service(addIntoServices_[key], 'addinto_srv_n_' + s, id2, id3, id1, id4);
		});
		if(jQuery.inArray(val, addinto_services_current) != -1) jQuery('#'+id4+' a.addinto_srv_n_' + s).click();
		jQuery('#'+id4+' a.addinto_srv_n_' + s).bind('click', function() {
			addinto_sort_services(id2,id3,id1,id4);
		});
	});
	
	addinto_sort_services(id2,id3,id1,id4);
	
	jQuery('#'+id2).val('');
	jQuery.each(addinto_services_current, function(key, val) {
		var s = val.toLowerCase().replace(/\./g, '_').replace(/\s+/g, '_');
		if(jQuery('#'+id3+' a').hasClass('addinto_button_' + s)) {
			jQuery('#'+id3+' a.addinto_button_' + s).appendTo(jQuery('#'+id3));
			jQuery(jQuery('#'+id3)).append('\n');
			jQuery('#'+id2).val(jQuery('#'+id2).val() + val + ',');
		}
	});
	jQuery('#'+id1).val(jQuery('#'+id2).val().substr(0,jQuery('#'+id2).val().length-1));
}

function addinto_services_width(d) {
	var addinto_divWidth = d.outerWidth();
	var addinto_divRowNb = Math.floor(addinto_divWidth/121);
	var addinto_divNewWi = (Math.floor((addinto_divWidth-(addinto_divRowNb*121))/addinto_divRowNb))+117;
	jQuery('#addinto_form a.addinto_button_links').css({'width':addinto_divNewWi+'px'});
}

jQuery(document).ready(function(){
	addinto_build_services('ai2_sharebox_srvs', 'addinto_sharebox_services', 'addinto_sharebox_services_sel', 'addinto_sharebox_services_list');
	addinto_build_services('ai2_srv', 'addinto_service', 'addinto_service_sel', 'addinto_service_list');
	addinto_build_services('ai2_srvs', 'addinto_services', 'addinto_services_sel', 'addinto_services_list');
	
	jQuery('#addinto_sharebox_services_list_show a, #addinto_service_list_show a, #addinto_services_list_show a').click(function() {
		if(jQuery(this).parent().next().is(':visible')) {
			jQuery('#'+jQuery(this).parent().next().attr('id')).animate({'height':'0'}, 500, function(){jQuery(this).css({'display':'none', 'height':''})});
		} else {
			jQuery(this).parent().next().slideDown(500);
		}
		var addinto_this_div = jQuery(this).parent().next();
		jQuery(window).resize(function(){
			addinto_services_width(addinto_this_div);
		});
		addinto_services_width(addinto_this_div);
	});
	
	jQuery('select#ai2_button_type').change(function() {
		switch(jQuery(this).val()) {
			case 'dropdown':
				jQuery('#js_options').slideDown(600);
				jQuery('#sharebox_servs').slideUp(600);
			break;
			case 'static':
				jQuery('#js_options').slideUp(600, function() {
					jQuery('#sharebox_servs').slideUp(600);
				});
			break;
			case 'sharebox':
				jQuery('#js_options').slideDown(600);
				jQuery('#sharebox_servs').slideDown(600);
		}
	});
});