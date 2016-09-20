/* for active currant page */
$(function(){
	var url = window.location.pathname;  
	var activePage = url.substring(url.lastIndexOf('/'));
	$('.sidebar-menu a').each(function(){  
		var currentPage = this.href;
		currentPage =currentPage.substring(this.href.lastIndexOf('/'));
		if (activePage == currentPage) {
			var parent=$(this).parent().parent();
			if($(parent).hasClass('sidebar-menu')) {
				$(this).parent().addClass('active'); 	
			}
			else {
				$(parent).parent().addClass('active');
				$(parent).css("display","block");		
			}
		} 
	});
});


