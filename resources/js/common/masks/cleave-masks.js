import Cleave from 'cleave.js';

const cleaveMasksParent = {

    cleave: null,

    type: {
        phone: '[data-role="js-mask-phone"]',
        phoneUs: '[data-role="js-mask-phone-us"]',
        date: '[data-role="js-mask-date"]',
        datetime: '[data-role="js-mask-datetime"]',
        time: '[data-role="js-mask-time"]',
        phoneint: '[data-role="js-mask-phone-int"]',
        int: '[data-role="js-mask-int"]',
        price: '[data-role="js-mask-price"]',
        birthday: '[data-role="js-mask-birthday"]',
        instagram: '[data-role="js-mask-instagram"]',
    },

    phoneCountryCode: 'code-country',

    // Маски взяты с https://ru.wikipedia.org/wiki/%D0%A2%D0%B5%D0%BB%D0%B5%D1%84%D0%BE%D0%BD%D0%BD%D1%8B%D0%B5_%D0%BA%D0%BE%D0%B4%D1%8B_%D1%81%D1%82%D1%80%D0%B0%D0%BD
    phoneMasks: {
        one: {
            chars: [
                '1', '7',
            ],
            check: function (chars) {
                const self = this;
                return self.chars.includes(chars);
            },
            cleave: function (target) {
                const self = this;
                $(target).data('code-country', 1);
                return new Cleave(target, {
                    blocks: [0, 1, 0, 3, 0, 3, 2, 4], // в конце должно быть 2, с запасом на 3ий вариант
                    delimiters: ['+', ' ', '(', ')', ' ', '-', '-'],
                    numericOnly: true,
                });
            },
        },
        two: {
            chars: [
                '20', '27', '28',
                '30', '31', '32', '33', '34', '36', '39',
                '40', '41', '43', '44', '45', '46', '47', '48', '49',
                '51', '52', '53', '54', '55', '56', '57', '58',
                '60', '61', '62', '63', '64', '65', '66',
                '73', '74', '76', '77', '78', //'79',
                '81', '82', '83', '84', '86', //'89',
                '90', '91', '92', '93', '94', '95', '98',
            ],
            check: function (chars) {
                const self = this;
                return self.chars.includes(chars);
            },
            cleave: function (target) {
                const self = this;
                $(target).data('code-country', 2);
                return new Cleave(target, {
                    blocks: [0, 2, 0, 3, 0, 3, 2, 2],
                    delimiters: ['+', ' ', '(', ')', ' ', '-', '-'],
                    numericOnly: true,
                    maxLength: 20,
                });
            },
        },
        three: {
            chars: [
                '21*', '22*', '23*', '24*', '25*', '26*', '29*',
                '35*', '37*', '38*',
                '42*',
                '50*', '59*',
                '67*', '68*', '69*',
                '80*', '85*', '87*', '88*',
                '96*', '97*', '99*',
            ],
            check: function (chars) {
                const self = this;
                if (self.chars.includes(chars)) {
                    return true;
                }
                let char = chars.substr(0, 2) + '*';
                return self.chars.includes(char);
            },
            cleave: function (target) {
                const self = this;
                $(target).data('code-country', 3);
                return new Cleave(target, {
                    blocks: [0, 3, 0, 3, 0, 3, 2, 2],
                    delimiters: ['+', ' ', '(', ')', ' ', '-', '-'],
                    numericOnly: true,
                });
            },
        },
        four: {
            chars: [
                '1242', '1246', '1264', '1268', '1284',
                '1340', '1345',
                '1441', '1473',
                '1649', '1658', '1664', '1670', '1671', '1684',
                '1721', '1758', '1767', '1784', '1787',
                '1809', '1829', '1849', '1868', '1869', '1876',
                '1939',
            ],
            check: function (chars) {
                const self = this;
                let char = chars.substr(0, 2) + '*';
                console.log(char);
                return self.chars.includes(char);
            },
            cleave: function (target) {
                const self = this;
                $(target).data('code-country', 3);
                console.log('three', $(target).data());
                return new Cleave(target, {
                    blocks: [0, 3, 0, 3, 0, 3, 2, 2],
                    delimiters: ['+', ' ', '(', ')', ' ', '-', '-'],
                    numericOnly: true,
                });
            },
        },
    },

    destroy() {
        const self = this;
        if (self.cleave !== null) {
            self.cleave.destroy();
            self.cleave = null;
        }
    },

    integer: function ($target) {
        let self = this;
        self.cleave = new Cleave($target, {
            blocks: [$target.data('length') ? $target.data('length') : 2],
            numericOnly: true,
            numeralPositiveOnly: true,
        });
    },

    phoneUs: function ($target) {
        let self = this;
        self.cleave = new Cleave($target, {
            prefix: '+1',
            blocks: [2, 0, 3, 0, 3, 4],
            delimiters: [' ', '(', ')', ' ', '-'],
            numericOnly: true,
            numeralPositiveOnly: true,
        });
    },

    phone: function (target, focus = false) {
        const self = this;
        let value = target.val();
        value = value.replace(/[^0-9.]/g, '');
        // если начинается с +8 900 ... то сделать +7 900 ..
        // чтобы автокомплит срабатывал верно
        if (value.startsWith('89')) {
            value = '79' + value.slice(2);
            target.val(value);
            return;
        }
        self.destroy();
        let char;
        // для трех символов
        if (value.length >= 3) {
            char = value.substr(0, 3);
            if (self.phoneMasks.three.check(char)) {
                self.cleave = self.phoneMasks.three.cleave(target);
                return;
            }
        }
        // для двух введенных
        if (value.length >= 2) {
            char = value.substr(0, 2);
            if (self.phoneMasks.two.check(char)) {
                self.cleave = self.phoneMasks.two.cleave(target);
                return;
            }
        }
        // для двух введенных
        if (value.length >= 1) {
            char = value.substr(0, 1);
            if (self.phoneMasks.one.check(char)) {
                self.cleave = self.phoneMasks.one.cleave(target);
                return;
            }
        }
    },
    birthday: function (target) {
        let self = this;
        let value = target.val();
        self.cleave = new Cleave(target, {
            blocks: [2, 2, 4],
            delimiters: ['-', '-'],
            numericOnly: true
        });
    },
    date: function (target) {
        let self = this;
        let value = target.val();
        self.cleave = new Cleave(target, {
            blocks: [2, 2, 4],
            delimiters: ['.', '.'],
            numericOnly: true
        });
    },
    datetime: function (target) {
        let self = this;
        let value = target.val();
        self.cleave = new Cleave(target, {
            blocks: [2, 2, 4, 2, 2],
            delimiters: ['.', '.', ' ', ':'],
            numericOnly: true
        });
    },
    time: function (target) {
        let self = this;
        let value = target.val();
        self.cleave = new Cleave(target, {
            blocks: [2, 2],
            delimiters: [':'],
            numericOnly: true
        });
    },
    price: function (target) {
        let self = this;
        let value = target.val();
        self.cleave = new Cleave(target, {
            prefix: '$ ',
            numeral: true,
            numeralPositiveOnly: true,
            noImmediatePrefix: true,
            rawValueTrimPrefix: true,
            numeralIntegerScale: target.data('length') ? target.data('length') : 9,
            numeralDecimalScale: 2
        });
    },
    instagram: function (target) {
        const self = this;
        let value = target.val();
        if (value.startsWith('@')) {
            //value = value.slice(1);
            //target.val(value);
            return;
        }
        self.destroy();
        self.cleave = new Cleave(target, {
            prefix: '@',
        });
    },
};

$(document).ready(function () {
    window['cleave-mask']();
});

window['cleave-mask'] = function () {
    const $body = $('body');
    const cleaveMasks = _.clone(cleaveMasksParent);

    if ($(cleaveMasks.type.phone).length) {
        $(cleaveMasks.type.phone).each(function () {
            const cleaveMaskPhone = _.clone(cleaveMasks);
            cleaveMaskPhone.phone($(this));
        });
    }
    $body.on('focus', cleaveMasks.type.phone, function () {
        //cleaveMasks.phone($(this), true);
    }).on('focusout', cleaveMasks.type.phone, function () {
        const $target = $(this);
        let countryCode = parseInt($target.data('code-country'));
        switch (countryCode) {
            case 1:
                if ($target.val().length !== 18) {
                    $target.val('');
                }
                break;
            case 2:
                if ($target.val().length !== 19) {
                    $target.val('');
                }
                break;
            case 3:
                if ($target.val().length !== 20) {
                    $target.val('');
                }
                break;
        }
    }).on('input change', cleaveMasks.type.phone, function (event) {
        cleaveMasks.phone($(this));
    });

    // NUMBERS
    if ($(cleaveMasks.type.int).length) {
        $(cleaveMasks.type.int).each(function () {
            if ($(this).val() !== '') {
                cleaveMasks.integer($(this));
            }
        });
    }

    // PRICE
    if ($(cleaveMasks.type.price).length) {
        $(cleaveMasks.type.price).each(function () {
            if ($(this).val() !== '') {
                cleaveMasks.price($(this));
            }
        });
    }
    // integer
    $body.on('focus', cleaveMasks.type.int, function () {
        cleaveMasks.integer($(this));
    }).on('focusout', cleaveMasks.type.int, function () {

    });

    if ($(cleaveMasks.type.phoneUs).length) {
        $(cleaveMasks.type.phoneUs).each(function () {
            const cleaveMaskPhone = _.clone(cleaveMasks);
            cleaveMaskPhone.phoneUs($(this));
        });
    }
    // integer
    $body.on('focus', cleaveMasks.type.phoneUs, function () {
        //cleaveMasks.phoneUs($(this));
    }).on('focusout', cleaveMasks.type.phoneUs, function () {
        const $target = $(this);
        if ($target.val().length !== 17) {
            $target.val('+1 ');
        }
    }).on('input change', cleaveMasks.type.phoneUs, function (event) {

    });

    // birthday
    $body.on('focus', cleaveMasks.type.birthday, function () {
        cleaveMasks.birthday($(this));
    }).on('focusout', cleaveMasks.type.birthday, function () {

    });

    // date
    $body.on('focus', cleaveMasks.type.date, function () {
        cleaveMasks.date($(this));
    }).on('focusout', cleaveMasks.type.date, function () {

    });

    // date
    $body.on('focus', cleaveMasks.type.price, function () {
        cleaveMasks.price($(this));
    }).on('focusout', cleaveMasks.type.price, function () {

    });

    // datetime
    $body.on('focus', cleaveMasks.type.datetime, function () {
        cleaveMasks.datetime($(this));
    }).on('focusout', cleaveMasks.type.datetime, function () {

    });

    // datetime
    $body.on('focus', cleaveMasks.type.time, function () {
        cleaveMasks.time($(this));
    }).on('focusout', cleaveMasks.type.time, function () {

    });

    // instagram
    $body.on('focus', cleaveMasks.type.instagram, function () {
        cleaveMasks.instagram($(this));
    }).on('focusout', cleaveMasks.type.instagram, function () {

    });

    // instagram
    if ($(cleaveMasks.type.instagram).length) {
        $(cleaveMasks.type.instagram).each(function () {
            if ($(this).val() !== '') {
                cleaveMasks.instagram($(this));
            }
        });
    }
};
