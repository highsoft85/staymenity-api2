

/*
 * функция смены окончаний
 * на вход принимает число и объект со значениями слова в различных склонениях:
 * {'nom': 'слово', 'gen':'слова', 'plu':'слов'}
 * {0: 'слово', 1:'слова', 2:'слов'}
 * ['слово', 'слова', 'слов']
 */
window.HELPER = {
    units(num, cases) {
        num = Math.abs(num);

        let word = '';

        if (num.toString().indexOf('.') > -1) {
            word = cases[1];
        } else {
            word = (
                num % 10 == 1 && num % 100 != 11
                    ? cases[0]
                    : num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20)
                    ? cases[1]
                    : cases[2]
            );
        }
        return word;
    },
    phoneFormat: function (value) {
        let val = value;
        if (val.length === 5) {
            return val.replace(/(\d)(\d\d)(\d\d)/, "$1-$2-$3"); // #-##-##
        }
        if (val.length === 6) {
            return val.replace(/(\d\d)(\d\d)(\d\d)/, "$1-$2-$3"); // ##-##-##
        }
        if (val.length === 7) {
            return val.replace(/(\d\d\d)(\d\d)(\d\d)/, "$1-$2-$3"); // ###-##-##
        }
        if (val.length === 11) {
            if (parseInt(val.charAt(0)) === 8) {
                val.substring(1);
                val = '7' + val;
            }
            return val.replace(/(\d)(\d\d\d)(\d\d\d)(\d\d)(\d\d)/, "+$1 ($2) $3-$4-$5");
        }
        return val;
    }
};

/**
 * Определение ширины скорлбара
 *
 * @returns {number}
 */
let getScrollbarWidth = function () {
    let outer = document.createElement("div");
    outer.style.visibility = "hidden";
    outer.style.width = "100px";
    outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps

    document.body.appendChild(outer);

    let widthNoScroll = outer.offsetWidth;
    // force scrollbars
    outer.style.overflow = "scroll";

    // add innerdiv
    let inner = document.createElement("div");
    inner.style.width = "100%";
    outer.appendChild(inner);

    let widthWithScroll = inner.offsetWidth;

    // remove divs
    outer.parentNode.removeChild(outer);

    return widthNoScroll - widthWithScroll;
};
document.getScrollbarWidth = getScrollbarWidth;

/**
 * Delay
 *
 *
 *  document.delay(function() {
        updateDatatableBySearch($element.val())
    }, 500);
 */
let delay = (function(){
    let timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

document.delay = delay;


