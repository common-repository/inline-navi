jQuery(function(){
	//inline_naviの切替
	jQuery('.top a.btn_inline_navi').click(function(){
		var pid = jQuery(this).attr('ID');
		var pindex = jQuery('div.inline_navi ul li a').index(this);
		var str_class = jQuery(this).attr('class');
		if(str_class.indexOf('btn_ajax') != -1){
			sendAjaxRequests(pid, pindex);
			jQuery(this).removeClass('btn_ajax');
		}
		jQuery('div.inline_navi ul li').css({'background-color' : '#EEEEEE', 'border-bottom' : '2px solid #CCCCCC'});
		jQuery(this).parent('li').css({'background-color' : '#FFFFFF', 'border-bottom' : '2px solid #FFFFFF'});
		jQuery('div.inline_navi_win').hide();
		jQuery('div.inline_navi_win:eq(' + pindex +')').fadeIn();
        return false;
    });
	jQuery('.bottom a.btn_inline_navi').click(function(){
		var pid = jQuery(this).attr('ID');
		var pindex = jQuery('div.inline_navi ul li a').index(this);
		var str_class = jQuery(this).attr('class');
		if(str_class.indexOf('btn_ajax') != -1){
			sendAjaxRequests(pid, pindex);
			jQuery(this).removeClass('btn_ajax');
		}
		jQuery('div.inline_navi ul li').css({'background-color' : '#EEEEEE', 'border-top' : '2px solid #CCCCCC'});
		jQuery(this).parent('li').css({'background-color' : '#FFFFFF', 'border-top' : '2px solid #FFFFFF'});
		jQuery('div.inline_navi_win').hide();
		jQuery('div.inline_navi_win:eq(' + pindex +')').fadeIn();
        return false;
    });
});