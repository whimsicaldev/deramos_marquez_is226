$('#submit-signin').on('click', () => {
    Object.values($('#signin').children('input[type="text"], input[type="password"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });
});

$('#signup-modal-body').on('submit', e => {
    alert('whuuuuut?!?!');
    Object.values($('#signup-modal-body').find('input[type="text"], input[type="password"], input[type="email"]'))
    .forEach(elem => {
        $(elem).addClass('submitted');
    });

    if($('#user_signup_password1').val() !== $('#user_signup_password2').val()) {
        e.preventDefault();
        e.stopPropagation();
        $('#user_signup_password1')[0].setCustomValidity("Invalid field.");
        $('#user_signup_password2')[0].setCustomValidity("Invalid field.");
        $('#form-help').text('Passwords did not match.');
    } else {
        $('#user_signup_password1')[0].setCustomValidity();
        $('#user_signup_password2')[0].setCustomValidity();
        $('#form-help').text('');
    }
});

$('#terms-and-conditions').on('click', () => {
    $('#submit-signup').attr('disabled', !$('#submit-signup').attr('disabled'));
});