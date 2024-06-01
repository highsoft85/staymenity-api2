let coordsTimeout;

$(document).ready(function () {
    setTimeout(function () {
        window['coordinates']();
    }, 1000);
});

window['coordinates'] = function () {
    const $forms = $('.--coordinates-container');

    $forms.each(function () {
        const $form = $(this);
        const $title = $form.find('.--geo-input');
        const $latitude = $form.find('.--is-latitude');
        const $longitude = $form.find('.--is-longitude');
        const $zoom = $form.find('.--is-zoom');
        const $map = $form.find('.--map-container');

        let aCoordinates = [];
        let coordinates = [];
        const $coordinatesForm = $form.find('.--geo-coordinate-form');
        $coordinatesForm.find('input').each(function (key, $item) {
            if (aCoordinates[$item.name] === undefined) {
                aCoordinates[$item.name] = [];
            }
            aCoordinates[$item.name].push($item.value);
            if (aCoordinates[$item.name].length === 2) {
                coordinates.push(aCoordinates[$item.name]);
            }
        });

        const geoInstance = _.clone(geo);

        if ($latitude.val() !== '' && $longitude.val() !== '') {
            geoInstance.setMap($map).init({
                latitude: parseFloat($latitude.val()),
                longitude: parseFloat($longitude.val()),
                zoom: parseInt($zoom.val()),
            }).prependCoordinates(coordinates).search();
        }
        $title.on('keyup', function () {
            const value = $(this).val();
            clearTimeout(coordsTimeout);
            coordsTimeout = setTimeout(function () {
                geoInstance.setMap($map).init({
                    name: value,
                }).prependCoordinates(coordinates).search();
            }, 500);
        });
        $latitude.on('paste', function (e) {
            const clipboardData = e.originalEvent.clipboardData;
            if (clipboardData === undefined) {
                return;
            }
            const lat = clipboardData.getData('Text');
            const lon = $longitude.val();
            clearTimeout(coordsTimeout);
            coordsTimeout = setTimeout(function () {
                console.log(lat, lon);
                geoInstance.setMap($map).init({
                    latitude: lat,
                    longitude: lon,
                }).prependCoordinates(coordinates).map();
            }, 500);
        });
        $longitude.on('change paste', function (e) {
            const lat = $latitude.val();
            const clipboardData = e.originalEvent.clipboardData;
            if (clipboardData === undefined) {
                return;
            }
            const lon = clipboardData.getData('Text');
            clearTimeout(coordsTimeout);
            coordsTimeout = setTimeout(function () {
                console.log(lat, lon);
                geoInstance.setMap($map).init({
                    latitude: lat,
                    longitude: lon,
                }).prependCoordinates(coordinates).map();
            }, 500);
        });
    });
};


const geo = {
    data: {},
    $map: null,
    myMap: null,

    otherCoordinates: [],

    setMap($map) {
        const self = this;
        self.$map = $map;
        return self;
    },


    init(data) {
        const self = this;
        self.data = data;
        return self;
    },
    zoom(val = undefined) {
        const self = this;
        const $form = self.$map.closest('.--coordinates-container');
        const $zoom = $form.find('.--is-zoom');
        if (val !== undefined) {
            $zoom.val(val);
        }
        return $zoom.val();
    },
    coordinates(latitude = undefined, longitude = undefined) {
        const self = this;
        const $form = self.$map.closest('.--coordinates-container');
        const $latitude = $form.find('.--is-latitude');
        const $longitude = $form.find('.--is-longitude');
        if (latitude !== undefined && longitude !== undefined) {
            $latitude.val(latitude);
            $longitude.val(longitude);
        }
        return this;
    },

    prependCoordinates(coordinates) {
        const self = this;
        self.otherCoordinates = coordinates;
        return self;
    },


    map() {
        const self = this;
        const $map = self.$map;
        if (!$map.length) {
            return;
        }
        $map.empty();
        let center = [55.753994, 37.622093];
        let coords = null;
        let zoom = 9;
        console.log(self.data);
        if (self.data.latitude !== undefined) {
            coords = [self.data.latitude, self.data.longitude];
            center = coords;
            zoom = self.data.zoom || zoom;
        }
        const id = $map.attr('id');
        const myMap = new ymaps.Map(id, {
            center: center,
            zoom: zoom,
        });

        if (self.otherCoordinates.length !== 0) {
            self.otherCoordinates.forEach((element, key) => {
                let placemark = new ymaps.Placemark(element, {}, {});
                myMap.geoObjects.add(placemark);
            });
        }

        if (coords !== null) {
            const myPlacemark = new ymaps.Placemark(coords, {}, {
                draggable: true,
            });
            myMap.geoObjects.add(myPlacemark);
            //событие по перетаскиванию
            myPlacemark.events.add('dragend', function (e) {
                let aCoordsNew = this.geometry.getCoordinates();
                //пишем коры в скрытый инпут
                self.coordinates(aCoordsNew[0], aCoordsNew[1]);
            }, myPlacemark);
            const $zoom = self.$map.closest('.--coordinates-container').find('.--is-zoom');
            $zoom.val(zoom);
            //self.getLocationComponents(myMap.geoObjects);
        }
        myMap.events.add('boundschange', function (e) {
            const $zoom = self.$map.closest('.--coordinates-container').find('.--is-zoom');
            if (e.get('newZoom') !== e.get('oldZoom')) {
                console.log(self.$map);
                $zoom.val(e.get('newZoom'));
            }
            if ($zoom.val() === '') {
                $zoom.val(e.get('newZoom'));
            }
        });
        return myMap;
    },

    //
    // $form() {
    //     return $('.--coordinates-container');
    // },
    setCoordinates(firstGeoObject) {
        const self = this;
        console.log(firstGeoObject);
        const $form = self.$map.closest('.--coordinates-container');
        const coords = firstGeoObject.geometry.getCoordinates();
        const $latitude = $form.find('.--is-latitude');
        const $longitude = $form.find('.--is-longitude');
        if (coords[0] !== undefined && coords[1] !== undefined) {
            $latitude.val(coords[0]);
            $longitude.val(coords[1]);
        }
        console.log(coords);
    },


    search() {
        const self = this;
        let myMap = self.map();
        if (self.data.name === undefined || self.data.name === '') {
            return;
        }
        // Поиск координат центра Нижнего Новгорода.
        ymaps.geocode(self.data.name, {
            /**
             * Опции запроса
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
             */
            // Сортировка результатов от центра окна карты.
            // boundedBy: myMap.getBounds(),
            // strictBounds: true,
            // Вместе с опцией boundedBy будет искать строго внутри области, указанной в boundedBy.
            // Если нужен только один результат, экономим трафик пользователей.
            results: 1
        }).then(function (res) {
            console.log(res);
            //console.log(res.geometry);
            //console.log(res.geometry._map);
            // Выбираем первый результат геокодирования.
            //self.getLocationComponents(res.geoObjects);
            let firstGeoObject = res.geoObjects.get(0),
                // Координаты геообъекта.
                coords = firstGeoObject.geometry.getCoordinates(),
                // Область видимости геообъекта.
                bounds = firstGeoObject.properties.get('boundedBy');

            firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
            // Получаем строку с адресом и выводим в иконке геообъекта.
            firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

            // Добавляем первый найденный геообъект на карту.
            myMap.geoObjects.add(firstGeoObject);
            // Масштабируем карту на область видимости геообъекта.
            myMap.setBounds(bounds, {
                // Проверяем наличие тайлов на данном масштабе.
                checkZoomRange: true
            });

            console.log('Координаты геообъекта: ', coords);
            self.setCoordinates(firstGeoObject);
            /**
             * Все данные в виде javascript-объекта.
             */
            console.log('Все данные геообъекта: ', firstGeoObject.properties.getAll());
            /**
             * Метаданные запроса и ответа геокодера.
             * @see https://api.yandex.ru/maps/doc/geocoder/desc/reference/GeocoderResponseMetaData.xml
             */
            console.log('Метаданные ответа геокодера: ', res.metaData);
            /**
             * Метаданные геокодера, возвращаемые для найденного объекта.
             * @see https://api.yandex.ru/maps/doc/geocoder/desc/reference/GeocoderMetaData.xml
             */
            console.log('Метаданные геокодера: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData'));
            /**
             * Точность ответа (precision) возвращается только для домов.
             * @see https://api.yandex.ru/maps/doc/geocoder/desc/reference/precision.xml
             */
            console.log('precision', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.precision'));
            /**
             * Тип найденного объекта (kind).
             * @see https://api.yandex.ru/maps/doc/geocoder/desc/reference/kind.xml
             */
            console.log('Тип геообъекта: %s', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.kind'));
            console.log('Название объекта: %s', firstGeoObject.properties.get('name'));
            console.log('Описание объекта: %s', firstGeoObject.properties.get('description'));
            console.log('Полное описание объекта: %s', firstGeoObject.properties.get('text'));
            //console.log('Область: %s', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName'));
            //console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.Address.Components'));

            /**
             * Прямые методы для работы с результатами геокодирования.
             * @see https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeocodeResult-docpage/#getAddressLine
             */
            console.log('\nГосударство: %s', firstGeoObject.getCountry());
            console.log('Населенный пункт: %s', firstGeoObject.getLocalities().join(', '));
            console.log('Адрес объекта: %s', firstGeoObject.getAddressLine());
            console.log('Наименование здания: %s', firstGeoObject.getPremise() || '-');
            console.log('Номер здания: %s', firstGeoObject.getPremiseNumber() || '-');

            self.setLocationComponents(firstGeoObject);

            /**
             * Если нужно добавить по найденным геокодером координатам метку со своими стилями и контентом балуна, создаем новую метку по координатам найденной и добавляем ее на карту вместо найденной.
             */
            /**
             var myPlacemark = new ymaps.Placemark(coords, {
             iconContent: 'моя метка',
             balloonContent: 'Содержимое балуна <strong>моей метки</strong>'
             }, {
             preset: 'islands#violetStretchyIcon'
             });

             myMap.geoObjects.add(myPlacemark);
             */
        });
    },

    getLocationComponents(geoObjects) {
        console.log(geoObjects);
        let firstGeoObject = geoObjects.get(0),
            // Координаты геообъекта.
            coords = firstGeoObject.geometry.getCoordinates(),
            // Область видимости геообъекта.
            bounds = firstGeoObject.properties.get('boundedBy');

        console.log('Компоненты: ', firstGeoObject);
        console.log('Компоненты: ', firstGeoObject.properties);
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty'));
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData'));
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.Address'));
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.Address.Components'));
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.Address.country_code'));
        console.log('Компоненты: ', firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName'));
    },

    setLocationComponents(firstGeoObject) {
        const self = this;
        const $form = self.$map.closest('.--coordinates-container');
        const text = firstGeoObject.properties.get('text');
        const address = firstGeoObject.getAddressLine();
        const country = firstGeoObject.getCountry();
        const province = firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName');
        const country_code = firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.Address.country_code');
        const locality = firstGeoObject.getLocalities().join(', ');

        console.log(
            province,
            firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea'),
            firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails.Country'),
            firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails'),
            firstGeoObject.properties.get('metaDataProperty.GeocoderMetaData'),
        );
        $form.find('.--support-text').val(text);
        $form.find('.--support-address').val(address);
        $form.find('.--support-country').val(country);
        $form.find('.--support-province').val(province);
        $form.find('.--support-locality').val(locality);
        $form.find('.--support-country_code').val(country_code);
    }
};
