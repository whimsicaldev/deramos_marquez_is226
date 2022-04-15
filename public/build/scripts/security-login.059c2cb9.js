$('#submit-signin').on('click', () => {
    Object.values($('#signin').children('input[type="text"], input[type="password"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });
});

$('#signup-modal-body').on('submit', e => {
    Object.values($('#signup-modal-body').find('input[type="text"], input[type="password"], input[type="email"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });

    if($('#password1').val() !== $('#password2').val()) {
        e.preventDefault();
        e.stopPropagation();
        $('#password1')[0].setCustomValidity("Invalid field.");
        $('#password2')[0].setCustomValidity("Invalid field.");
        $('#form-help').text('Passwords did not match.');
    } else {
        $('#password1')[0].setCustomValidity();
        $('#password2')[0].setCustomValidity();
        $('#form-help').text('');
    }
});

$('#terms-and-conditions').on('click', () => {
    $('#submit-signup').attr('disabled', !$('#submit-signup').attr('disabled'));
});