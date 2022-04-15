$('#submit-signin').on('click', () => {
    Object.values($('#signin').children('input[type="text"], input[type="password"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });
});

$('#submit-signup').on('click', () => {
    Object.values($('#signup-modal-body').children('input[type="text"], input[type="password"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });
});