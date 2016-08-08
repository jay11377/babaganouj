(function($){
	var initLayout = function() {
		var hash = window.location.hash.replace('#', '');
		var currentTab = $('ul.navigationTabs a')
							.bind('click', showTab)
							.filter('a[rel=' + hash + ']');
		if (currentTab.size() == 0) {
			currentTab = $('ul.navigationTabs a:first');
		}
		showTab.apply(currentTab.get(0));
		$('#colorpickerHolder').ColorPicker({
				flat: true,
				onChange: function (hsb, hex, rgb) {
					$("#" + lastClicked).css('color', '#' + hex);
					var num_content = lastClicked.substr(4,1);
					$("#content" + num_content + "color").val('#' + hex);
				},
				onSubmit: function (hsb, hex, rgb) {
					$("#" + lastClicked).css('color', '#' + hex);
					var num_content = lastClicked.substr(4,1);
					$("#content" + num_content + "color").val('#' + hex);
				}
		});
	};
	
	var showTab = function(e) {
		var tabIndex = $('ul.navigationTabs a')
							.removeClass('active')
							.index(this);
		$(this)
			.addClass('active')
			.blur();
		$('div.tab')
			.hide()
				.eq(tabIndex)
				.show();
	};
	
	EYE.register(initLayout, 'init');
})(jQuery)