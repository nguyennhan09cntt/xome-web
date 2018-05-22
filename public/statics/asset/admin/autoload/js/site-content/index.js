$(function(){
    Custom.initManualUpdate('site-content');
    $('#btn_add_new').click(function(){
        Metronic.redirect('site-content/edit/')
    });
});