var AddIndex = {
    init: function () {
        // CKEDITOR.disableAutoInline = true;
        // CKEDITOR.inline( 'form-description' );
        // CKEDITOR.replace('form-description', {
        //     filebrowserImageBrowseUrl: '' + '/statics/asset/global/plugins/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=' +
        //     '' + '/statics/asset/global/plugins/ckeditor/filemanager/connectors/php/connector.php'
        // });
    }
}

$(function () {
    //AddIndex.init();
    var test = $("#form-add input[type=checkbox], #form-add input[type=radio]");
    if (test.size() > 0) {
        test.each(function () {
            if ($(this).parents(".checker").size() == 0) {
                $(this).show();
                $(this).uniform();
            }
        });
    }
});