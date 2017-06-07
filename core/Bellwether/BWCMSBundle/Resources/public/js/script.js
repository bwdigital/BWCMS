$( document ).ajaxStart(function() {
    $( "#loadingIndicatorHolder" ).show();
});
$( document ).ajaxComplete(function() {
    $( "#loadingIndicatorHolder" ).hide();
});

$(document).ready(function () {
    $('div.FCHolder ul.FCList  button.FCRemove').click(function (e) {
        e.preventDefault();
        $(this).parents('li').first().remove()
    });
    $('div.FCHolder button.FCAdd').click(function (e) {
        e.preventDefault();
        var totalItems = parseInt($(this).parent().attr('data-length'));
        $(this).parent().attr('data-length', (totalItems + 1));
        var fData = $(this).parent().data('prototype');
        fData = fData.replace(/__name__label__/g, totalItems);
        fData = fData.replace(/__name__/g, totalItems);
        $(this).parent().find('ul.FCList').first().append('<li>' + fData + '</li>');
        $(this).parent().find('button.FCRemove').last().click(function (e) {
            e.preventDefault();
            $(this).parents('li').first().remove();
        });
    });
    $('ul.FCList').sortable();
    // $('ul.FCList').sortable({
    //     start: function( event, ui ) {
    //         console.log($(this).find('textarea.editor').first().attr('id'));
    //         tinyMCE.execCommand('mceRemoveEditor', false, 'BWCF_sections_0_sectionContent');
    //         console.log('Hellooooo');
    //     },
    //     stop: function( event, ui ) {
    //
    //         tinyMCE.execCommand('mceAddEditor', true, 'BWCF_sections_0_sectionContent');
    //         console.log('Hellooooo');
    //     }
    // });
});

function showContentBrowser(ele) {
    var holder = $(ele).parent().parent();
    var cbURL = $(ele).data('url');
    var selectedValue = holder.find('input[type=hidden]').val();
    if(selectedValue.length!=0){
        cbURL = cbURL + '&selectedContentId=' + selectedValue;
    }
    window.open(cbURL, "contentBrowserWindow", "scrollbars,resizable,width=800,height=600");
    return false;
}

function clearContentBrowser(ele) {
    var holder = $(ele).parent().parent();
    holder.find('img.contentThumb').attr('src','');
    holder.find('input[type=text],input[type=hidden]').val('');
    console.log(holder);
    return false;
}