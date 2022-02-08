$(function () {
	Ajax.csrf();
	Auth.exit('.js-logout');
	Form.style('file');
	Form.wysiwyg();
	Form.files();
	List.bindDeletes();
	Images.fancy();
});
