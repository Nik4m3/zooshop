$(document).ready(function() {
    /* Обработка клика на кнопку "продать" */
    $('.sell-btn').click(function(e) {
        e.preventDefault();
        var row = $(this).parent().parent(),
            rowId = row.children().first().text(),
            data = getData('POST', '/sell', {'id': rowId}, noasync = true);
        setFlash(data.status, data.description);
        if (data.status === 'success') {
            row.remove();
        }
    });
});
