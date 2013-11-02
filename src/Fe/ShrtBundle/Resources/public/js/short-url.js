$(function() {
    $('.short-url-form').submit(function (e) {
        var url = $('#short_url_url').val();
        $.get('/api/1/url/short?url=' + url, function(response) {
            $('.short-url-response').removeClass('hide');
            $('.short-url-response h1 a').text(response).attr('href', response);
        });
        e.preventDefault();
    });
    $('.short-url-response h1').click(function (e) {
        selectText(this);
        e.preventDefault();
    })
});

// http://stackoverflow.com/questions/11128130/select-text-in-javascript
function selectText(element) {
    var doc = document;

    if (doc.body.createTextRange) { // ms
        var range = doc.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) { // moz, opera, webkit
        var selection = window.getSelection();
        var range = doc.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}