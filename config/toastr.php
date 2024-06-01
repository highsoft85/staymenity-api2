<?php

return [
    'options' => [
        "tapToDismiss" => true,
        "toastClass" => 'toast',
        "containerId" => 'toast-container',
        "debug" => false,

        "showMethod" => 'fadeIn', //fadeIn, slideDown, and show are built into jQuery
        "showDuration" => 3,
        "showEasing" => 'swing', //swing and linear are built into jQuery
        "onShown" => 'undefined',
        "hideMethod" => 'fadeOut',
        "hideDuration" => 0,
        "hideEasing" => 'swing',

        "extendedTimeOut" => 0,
        "iconClasses" => [
            "error" => 'toast-error',
            "info" => 'toast-info',
            "success" => 'toast-success',
            "warning" => 'toast-warning',
        ],
        "iconClass" => 'toast-info',
        "closeDuration" => 10,
        "positionClass" => 'toast-top-right',
        "timeOut" => 5000, // Set timeOut and extendedTimeOut to 0 to make it sticky
        "titleClass" => 'toast-title',
        "messageClass" => 'toast-message',
        "target" => 'body',
        //"closeHtml" => '<a class="ns-close icon" ><i class="fa fa-times"></i></a>',
        //"closeHtml"=> '<button class="btn-flat btn-icon" style="color: #999;"><i class="material-icons"></i></button>',
        "newestOnTop" => false,
        "preventDuplicates" => false,
        'closeOnHover' => false,
        'closeButton' => true,
    ],
];
