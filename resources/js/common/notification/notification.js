

window.toastr = require('toastr/build/toastr.min.js');
/**
 * notification.send({
 *  type: 'error',
 *  title: 'Ошибка',
 *  text: 'Текст ошибки'
 * })
 *
 *
 * @type {{send(*): void}}
 */
window.notification = {
    send(notification) {
        switch (notification.type) {
            case 'warning':
                window.toastr.warning(notification.text, notification.title, notification.options);
                break;
            case 'success':
                window.toastr.success(notification.text, notification.title, notification.options);
                break;
            case 'error':
                window.toastr.error(notification.text, notification.title, notification.options);
                break;
            case 'info':
                window.toastr.info(notification.text, notification.title, notification.options);
                break;
            default:
                window.toastr.info(notification.text, notification.title, notification.options);
                break;
        }
    },
    error(message) {
        this.send({
            type: 'error',
            text: message,
            title: 'Error',
        })
    }
};
if (window.toastrOptions !== undefined) {
    window.toastr.options = window.toastrOptions;
}
if (window.toastrNotification !== undefined) {
    window.notification.send(window.toastrNotification);
}
