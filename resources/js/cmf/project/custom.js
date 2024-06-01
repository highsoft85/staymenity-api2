import {authActions, authActionsEvents} from "./authActions";

// Render Google Sign-in button
function renderButton() {
    gapi.signin2.render('gSignIn', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
    });
}

// Sign-in success callback
function onSuccess(googleUser) {
    // Get the Google profile data (basic)
    //var profile = googleUser.getBasicProfile();

    // Retrieve the Google account data
    gapi.client.load('oauth2', 'v2', function () {
        var request = gapi.client.oauth2.userinfo.get({
            'userId': 'me'
        });
        request.execute(function (resp) {
            // Display the user details
            var profileHTML = '<h3>Welcome '+resp.given_name+'! <a href="javascript:void(0);" onclick="signOut();">Sign out</a></h3>';
            profileHTML += '<img src="'+resp.picture+'"/><p><b>Google ID: </b>'+resp.id+'</p><p><b>Name: </b>'+resp.name+'</p><p><b>Email: </b>'+resp.email+'</p><p><b>Gender: </b>'+resp.gender+'</p><p><b>Locale: </b>'+resp.locale+'</p><p><b>Google Profile:</b> <a target="_blank" href="'+resp.link+'">click to view profile</a></p>';
            document.getElementsByClassName("userContent")[0].innerHTML = profileHTML;

            document.getElementById("gSignIn").style.display = "none";
            document.getElementsByClassName("userContent")[0].style.display = "block";
        });
    });
}

// Sign-in failure callback
function onFailure(error) {
    alert(error);
}

// Sign out the user
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        document.getElementsByClassName("userContent")[0].innerHTML = '';
        document.getElementsByClassName("userContent")[0].style.display = "none";
        document.getElementById("gSignIn").style.display = "block";
    });

    auth2.disconnect();
}

$(document).ready(function () {

    authActionsEvents.login();
    authActionsEvents.register();
    authActionsEvents.passwordEmail();
    authActionsEvents.passwordReset();


    $('body').on('click', '.--remove-parent', function () {
        const $parent = $($(this).data('parent'));
        if ($parent.length) {
            $parent.remove();
        }
    });







    const $openDialogImportant = $('.--open-dialog-important');
    if ($openDialogImportant.length) {
        setTimeout(function () {
            $openDialogImportant.trigger('click');
        }, 100);
    }
    $('body').on('change', '.abc-checkbox.is-radio input', function () {
        const $target = $(this);
        const $container = $target.closest('.abc-checkbox-container');
        if ($container.length) {
            $container.find('.abc-checkbox.is-radio input').prop('checked', false);
            $target.prop('checked', true);
        }
    });

    $('body').on('click', '.settings .card-header .spoiler', function () {
        $(this).closest('.settings').toggleClass('active');
        $(this).closest('.settings').find('.card-block').slideToggle();
    });

    $('body').on('shown.bs.tab', 'a[data-toggle="tab"][data-hidden-submit]', function (e) {
        var tab = $(e.target);
        var submit = tab.closest('.modal-content').find('.modal-footer .ajax-link');
        if (parseInt(tab.data('hidden-submit')) === 0) {
            submit.removeClass('hidden');
        } else {
            submit.addClass('hidden');
        }
    });
    $('body').on('change', '.switch-multi-save-dialog input', function () {
        const $switch = $(this);
        const $dialog__content = $switch.closest('.dialog__content');
        if ($switch.prop('checked')) {
            $dialog__content.addClass('--save-multi');
        } else {
            $dialog__content.removeClass('--save-multi');
        }
    });
    $('body').on('click', '#pages-dialogs-confirm .btn.--force-delete', function () {
        const $target = $(this);
        const $form = $target.closest('form');
        $form.find('input[name="force"]').val(1);
        $('#pages-dialogs-confirm .btn.--soft-delete')
            .removeClass('inner-form-submit')
            .attr('type', 'button');
        $target
            .addClass('inner-form-submit')
            .attr('type', 'submit');
        //$form.submit();
        //console.log($form);
    });
    $('body').on('click', '#pages-dialogs-confirm .btn.--soft-delete', function () {
        const $target = $(this);
        const $form = $target.closest('form');
        $form.find('input[name="force"]').val(0);
        $('#pages-dialogs-confirm .btn.--force-delete')
            .removeClass('inner-form-submit')
            .attr('type', 'button');
        $target
            .addClass('inner-form-submit')
            .attr('type', 'submit');
        //$form.submit();
        //console.log($form);
    });
});


