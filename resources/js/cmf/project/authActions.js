const URL = window.api_url;

export const authActionsEvents = {
    login() {
        $('.--sanctum-admin-submit.--login').on('click', function (e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            const email = $form.find('input[name="email"]').val();
            const password = $form.find('input[name="password"]').val();
            authActions.login({
                email: email,
                password: password,
            });
        });
    },
    register() {
        $('.--sanctum-admin-submit.--register').on('click', function (e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            const email = $form.find('input[name="email"]').val();
            const password = $form.find('input[name="password"]').val();
            const gender = $form.find('input[name="gender"]').val();
            const role = $form.find('input[name="role"]').val();
            authActions.register({
                email: email,
                password: password,
                gender: gender,
                role: role,
            });
        });
    },
    passwordEmail() {
        $('.--sanctum-admin-submit.--password-email').on('click', function (e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            const email = $form.find('input[name="email"]').val();
            authActions.forgotPassword({
                email: email,
            });
        });
    },
    passwordReset() {
        $('.--sanctum-admin-submit.--password-reset').on('click', function (e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            const token = $form.find('input[name="token"]').val();
            const email = $form.find('input[name="email"]').val();
            const password = $form.find('input[name="password"]').val();
            const password_confirmation = $form.find('input[name="password_confirmation"]').val();
            authActions.resetPassword({
                token: token,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
            });
        });
    },

};

export const authActions = {

    /**
     *
     * @param callback
     */
    csrf(callback) {
        const self = this;
        fetch(`${URL}/sanctum/csrf-cookie`)
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                callback();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param data
     * @param callback
     */
    authLogin(data, callback) {
        fetch(`${URL}/auth/login`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify(data),
        })
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                return response.json();
            })
            .then(result => {
                callback(result);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param data
     * @param callback
     */
    authRegister(data, callback) {
        fetch(`${URL}/auth/register`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify(data),
        })
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                return response.json();
            })
            .then(result => {
                callback(result);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param data
     * @param callback
     */
    authForgotPassword(data, callback) {
        fetch(`${URL}/auth/password/email`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify(data),
        })
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                return response.json();
            })
            .then(result => {
                callback(result);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param data
     * @param callback
     */
    authResetPassword(data, callback) {
        fetch(`${URL}/auth/password/reset`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify(data),
        })
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                return response.json();
            }).then(result => {
                callback(result);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param token
     * @param callback
     */
    user(token, callback) {
        fetch(`${URL}/user`, {
            headers: {
                'Content-type': 'application/json',
                Authorization: 'Bearer ' + token,
            },
            method: 'POST',
        })
            .then(response => {
                if (!response.ok) throw Error(response.statusText);
                return response.json();
            })
            .then(result => {
                callback();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },

    /**
     *
     * @param data
     */
    login(data) {
        const self = this;
        self.csrf(function () {
            self.authLogin(data, function (result) {
                console.log(result);
                self.user(result.data.token, function () {
                    console.log('Login Success');
                });
            });
        });
    },

    /**
     *
     * @param data
     */
    register(data) {
        const self = this;
        self.csrf(function () {
            self.authRegister(data, function (result) {
                self.user(result.data.token, function () {
                    console.log('Login Success');
                });
            });
        });
    },

    /**
     *
     * @param data
     */
    forgotPassword(data) {
        const self = this;
        self.csrf(function () {
            self.authForgotPassword(data, function (result) {
                // отправляется сообщение
            });
        });
    },

    /**
     *
     * @param data
     */
    resetPassword(data) {
        const self = this;
        self.csrf(function () {
            self.authResetPassword(data, function (result) {
                // дальше редирект на страницу логина
                // либо
                // отвечать сразу с токеном и сразу авторизовывать
                self.user(result.data.token, function () {
                    console.log('Login Success');
                });
            });
        });
    }
};
