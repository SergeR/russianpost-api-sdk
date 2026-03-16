---
created: 2026-02-10T19:03:27+03:00
modified: 2026-02-24T11:46:43+03:00
---
## Авторизация

Все методы требуют авторизации:

- токеном авторизации приложения;
- ключом авторизации пользователя

Токен авторизации передаётся в заголовке запроса:

`Authorization: AccessToken sDBaa9XNfFargSyQ8KIEM40GB_ndPmLu`

Ключ авторизации получаются из логина (email) и пароля, разделёнными двоеточием и закодированными в base64:
```php
$auth_key = base64_encode($login . ':' . $password);
```

Ключ авторизации передаётся в заголовке X-User-Authorization:

`X-User-Authorization: Basic bG9naW46cGFzc3dvcmQ=`

## Методы API
### Создание заказа

HTTP-метод: `PUT`
URL:  `/1.0/user/backlog`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на создание заказа: [pochta-ru-create-order-schema.json](./pochta-ru-create-order-schema.json).

### Создание заказа v2

HTTP-метод: `PUT`
URL:  `/2.0/user/backlog`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на создание заказа: [pochta-ru-create-order-v2-schema.json](./pochta-ru-create-order-v2-schema.json).

### Поиск заказа

Ищет заказы по назначенному магазином идентификатору

HTTP-метод: `GET`
URL:  `/1.0/backlog/search`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `query` — Буквенно-цифровой идентификатор отправления

JSON-Schema ответа на запрос поиска заказа: [pochta-ru-search-order-schema.json](./pochta-ru-search-order-schema.json).

### Поиск заказа по идентификатору

Ищет заказы по назначенному магазином идентификатору

HTTP-метод: `GET`
URL:  `/1.0/backlog/{id}`
Content-Type: `application/json;charset=UTF-8`

Где `{id}` это идентификатор заказа

JSON-Schema ответа на запрос поиска заказа: [pochta-ru-search-id-schema.json](./pochta-ru-search-id-schema.json).

### Редактирование заказа

HTTP-метод: `PUT`
URL:  `/1.0/user/backlog/{id}`
Content-Type: `application/json;charset=UTF-8`

Где `{id}` это идентификатор заказа

JSON-Schema запроса и ответа на запрос редактирования заказа: [pochta-ru-order-edit-schema.json](./pochta-ru-order-edit-schema.json).

### Удаление заказа

HTTP-метод: `DELETE`
URL:  `/1.0/user/backlog`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос удаления заказа: [pochta-ru-order-delete-schema.json](./pochta-ru-order-delete-schema.json).

### Возврат заказов в «Новые»

Метод переводит заказы из партии в раздел Новые. Партия должна быть в статусе CREATED.

HTTP-метод: `POST`
URL:  `/1.0/user/backlog`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос возврат заказа в «новые» заказа: [pochta-ru-order-back-new-schema.json](./pochta-ru-order-back-new-schema.json).

### Поиск заказов по идентификатору группы

HTTP-метод: `GET`
URL:  `/1.0/backlog/by-group-name/{group-name}`
Content-Type: `application/json;charset=UTF-8`

Где `{group-name}` название группы.

JSON-Schema ответа на запрос поиска заказов: [pochta-ru-search-by-group-schema.json](./pochta-ru-search-by-group-schema.json).

### Создание партии из нескольких заказов

Автоматически создает партию и переносит указанные подготовленные заказы в эту партию. Если заказы относятся к разным типам и категориям – создается несколько партий. Заказы распределяются по соответствующим партиям. Каждому перенесенному заказу автоматически присваивается ШПИ.

HTTP-метод: `POST`
URL:  `/1.0/user/shipment`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `sending-date` — (Опционально) дата сдачи в почтовое отделение (yyyy-MM-dd)
 - `timezone-offset` — (Опционально) смещение даты сдачи от UTC в секундах
 - `use-online-balance` — (Опционально).Признак использования онлайн баланса, строка `'true'` или `'false'`

JSON-Schema запроса и ответа на запрос создания партии на отправку: [pochta-ru-create-shipment-schema.json](./pochta-ru-create-shipment-schema.json).

### Изменение дня отправки в почтовое отделение

Изменяет (устанавливает) новый день отправки в почтовое отделение.

HTTP-метод: `POST`
URL:  `/1.0/batch/{name}/sending/{year}/{month}/{dayOfMonth}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии
- `year` — Дата сдачи в почтовое отделение: год
- `month` — Дата сдачи в почтовое отделение: месяц
- `dayOfMonth` — Дата сдачи в почтовое отделение: день

JSON-Schema ответа на запрос изменения даты отправки партии: [pochta-ru-change-shipment-date-schema.json](./pochta-ru-change-shipment-date-schema.json).

### Перенос заказов в партию

Переносит подготовленные заказы в указанную партию. Если часть заказов не может быть помещена в партию (тип и категория партии не соответствует типу и категории заказа) - возвращается json объект с указанием индекса заказа в переданном массиве и типом ошибки, остальные заказы помещаются в указанную партию. Каждому перенесенному заказу автоматически присваивается ШПИ.

HTTP-метод: `POST`
URL:  `/1.0/batch/{name}/shipment`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии
- `group-name` — Идентификатор группы ММО

JSON-Schema ответа на запрос изменения даты отправки партии: [pochta-ru-batch-shipment-schema.json](./pochta-ru-batch-shipment-schema.json).

### Поиск партии по наименованию

Возвращает параметры партии.

HTTP-метод: `GET`
URL:  `/1.0/batch/{name}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии

JSON-Schema ответа на запрос поиска партии по наименованию: [pochta-ru-batch-search-schema.json](./pochta-ru-batch-search-schema.json).

### Поиск заказов с ШПИ (трек-номером)

Возвращает параметры партии.

HTTP-метод: `GET`
URL:  `/1.0/shipment/search`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `query` — Условие для поиска: номер заказа или ШПИ

JSON-Schema ответа на запрос поиска партии по наименованию: [pochta-ru-search-order-with-track-schema.json](./pochta-ru-order-with-track-schema.json).

### Добавление заказов в партию

Создает массив заказов и помещает непосредственно в партию. Автоматически рассчитывает и проставляет плату за пересылку. Каждому заказу автоматически присваивается ШПИ.

HTTP-метод: `PUT`
URL:  `/1.0/batch/{name}/shipment`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии

JSON-Schema запроса и ответа на добавление заказов в партию: [pochta-ru-shipment-add-schema.json](./pochta-ru-shipment-add-schema.json).

### Удаление заказов из партии

HTTP-метод: `DELETE`
URL:  `/1.0/shipment`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на удаление заказов из партии: [pochta-ru-remove-order-from-shipment-schema.json](./pochta-ru-remove-order-from-shipment-schema.json).

### Запрос данных о заказах в партии

HTTP-метод: `GET`
URL:  `/1.0/batch/{name}/shipment`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии
GET-параметры запроса:
 - `size` — Количество записей на странице (Опционально)
 - `sort` — Критерии сортировки в формате: `asc`(по возрастанию) или `desc` (по убыванию). По умолчанию порядок сортировки по возрастанию (Опционально)
 - `page` — Номер страницs (0..N) (Опционально)

JSON-Schema ответа на получение данных заказов в партии: [pochta-ru-orders-in-shipment-schema.json](./pochta-ru-orders-in-shipment-schema.json).

### Поиск всех партий

HTTP-метод: `GET`
URL:  `/1.0/batch`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `mailType` — Тип отправления (Опционально)
 - `mailCategory` — Категория отправления (Опционально)
 - `size` — Количество записей на странице (Опционально)
 - `sort` — Критерии сортировки в формате: `asc` (по возрастанию) или `desc` (по убыванию). По умолчанию порядок сортировки по возрастанию (Опционально)
 - `page` — Номер страницs (0..N) (Опционально)

JSON-Schema ответа на поиск всех партий: [pochta-ru-get-all-batches-schema.json](./pochta-ru-get-all-batches-schema.json).

### Поиск заказа в партии по внутреннему id

HTTP-метод: `GET`
URL:  `/1.0/shipment/{id}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `id` — Внутренний идентификатор отправления

JSON-Schema ответа на поиск заказа в партии по внутреннему id: [pochta-ru-order-in-shipment-by-id-schema.json](./pochta-ru-order-in-shipment-by-id-schema.json).

### Поиск заказов в партии по идентификатору группы

HTTP-метод: `GET`
URL:  `/1.0/shipment/by-group-name/{group-name}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `group-name` — Идентификатор группы отправлений

JSON-Schema ответа на поиск заказа в партии по внутреннему id: [pochta-ru-shipment-orders-by-group-name-schema.json](./pochta-ru-shipment-orders-by-group-name-schema.json).

### Генерация пакета документации

Генерирует и возвращает zip архив с 4-мя файлами:
- `Export.xls` , `Export.csv` — список с основными данными по заявкам в составе партии
- `F103.pdf` — форма ф103 по заявкам в составе партии
- В зависимости от типа и категории отправлений, формируется комбинация из сопроводительных документов в формате pdf ( формы: f7, f112, f22)

HTTP-метод: `GET`
URL:  `/1.0/forms/{name}/zip-all`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии
GET-параметры запроса:
 - `print-type` —  (Опционально).Тип печати (`THERMO` или `PAPER`) 
 - `print-type-form` — (Опционально). Тип печати уведомления (`ONE_SIDED` или `TWO_SIDED`)

### Генерация печатной формы Ф7п

Генерирует и возвращает pdf файл с формой ф7п для указанного заказа. Опционально в файл прикрепляется форма Ф22 (посылка онлайн).
Если параметр sending-date не передается, берется текущая дата.

HTTP-метод: `GET`
URL:  `/1.0/forms/{id}/f7pdf`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `id` — Уникальный идентификатор заказа
GET-параметры запроса:
 - `sending-date` — (Опционально). Дата отправки в почтовое отделение (yyyy-MM-dd)
 - `print-type` —  (Опционально).Тип печати (`THERMO` или `PAPER`) 

### Генерация печатной формы Ф112ЭК

Генерирует и возвращает pdf-файл с заполненной формой Ф112ЭК для указанного заказа. Только для заказа с «наложенным платежом». Если заказ не имеет данного атрибута, метод вернет ошибку. Если параметр sending-date не передается, берется текущая дата.

HTTP-метод: `GET`
URL:  `/1.0/forms/{id}/f112pdf`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `id` — Уникальный идентификатор заказа
GET-параметры запроса:
 - `sending-date` — (Опционально). Дата отправки в почтовое отделение (yyyy-MM-dd)

### Генерация печатных форм для заказа (до формирования партии)

Генерирует и возвращает pdf файл, который может содержать в зависимости от типа отправления:
- форму ф7п (бандероль, курьер-онлайн);
- форму Е-1 (EMS, EMS-оптимальное, Бизнес курьер, Бизнес курьер экспресс)
- конверт (письмо заказное).
Опционально прикрепляются формы: Ф112ЭК (отправление с наложенным платежом), уведомление (для заказного письма или бандероли).

HTTP-метод: `GET`
URL:  `/1.0/forms/backlog/{id}/forms`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `id` — Уникальный идентификатор заказа
GET-параметры запроса:
 - `sending-date` — (Опционально). Дата отправки в почтовое отделение (yyyy-MM-dd)

### Генерация печатных форм для заказа

Генерирует и возвращает pdf файл, который содержит, либо:
- форму ф7п (посылка, посылка-онлайн, бандероль, курьер-онлайн);
- форму Е-1 (EMS, EMS-оптимальное, Бизнес курьер, Бизнес курьер экспресс)
- конверт (письмо заказное).
Опционально прикрепляются формы: Ф112ЭК (отправление с наложенным платежом), Ф22 (посылка онлайн), уведомление и опись вложения (для заказного письма или бандероли).

HTTP-метод: `GET`
URL:  `/1.0/forms/{id}/forms`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `id` — Уникальный идентификатор заказа
GET-параметры запроса:
 - `sending-date` — (Опционально). Дата отправки в почтовое отделение (yyyy-MM-dd)
 - `print-type` —  (Опционально).Тип печати (`THERMO` или `PAPER`) 

### Генерация печатной формы Ф103

Генерирует и возвращает pdf файл с формой Ф103 для указанной партии.

HTTP-метод: `GET`
URL:  `/1.0/forms/{name}/f103pdf`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии

### Подготовка и отправка электронной формы Ф103

Присваивает уникальную версию партии для дальнейшего приема этой партии сотрудниками ОПС. Отправляет по e-mail электронную форму Ф103 в ОПС для регистрации.

HTTP-метод: `GET`
URL:  `/1.0/batch/{name}/checkin`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии
GET-параметры запроса:
- `useOnlineBalance` — (Опционально). Признак использования онлайн баланса. Строка `'true'` или `'false'`

JSON-Schema ответа запрос подготовки и отправки электронной формы Ф103: [pochta-ru-batch-checkin-schema.json](./pochta-ru-batch-checkin-schema.json).

### Генерация печатной формы акта осмотра содержимого

Генерирует и возвращает pdf файл с формой акта осмотра содержимого для указанной партии.

HTTP-метод: `GET`
URL:  `/1.0/forms/{id}/completeness-checking-form`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `name` — Наименование партии

### Генерация возвратного ярлыка на одной печатной странице

Генерирует и возвращает pdf файл возвратного ярлыка на одной печатной странице

HTTP-метод: `GET`
URL:  `/1.0/forms/{barcode}/easy-return-pdf`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `barcode` — ШПИ возвратного отправления
GET-параметры запроса:
 - `print-type` —  (Опционально).Тип печати (`THERMO` или `PAPER`) 

### Запрос данных о партиях в архиве

HTTP-метод: `GET`
URL:  `/1.0/archive`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema ответа на запрос данных о партиях в архиве: [pochta-ru-list-archive-schema.json](./pochta-ru-list-archive-schema.json).

### Перевод партии в архив

HTTP-метод: `PUT`
URL:  `/1.0/archive`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на перевод партии в архив: [pochta-ru-put-archive-schema.json](./pochta-ru-put-archive-schema.json).

### Возврат партии из архива

HTTP-метод: `POST`
URL:  `/1.0/archive/revert`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на возврат партии из архива: [pochta-ru-archive-revert-schema.json](./pochta-ru-archive-revert-schema.json).

### Поиск почтового отделения по индексу

HTTP-метод: `GET`
URL:  `/postoffice/1.0/{postal-code}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `postal-code` — Индекс почтового отделения
GET-параметры запроса:
 - `latitude` —  Широта
 - `longitude` — Долгота
 - `current-date-time` — Текущее время в формате yyyy-MM-dd'T'HH:mm:ss (Опционально)
 - `filter-by-office-type` — Фильтр по типам объектов в ответе. `true`: ГОПС, СОПС, ПОЧТОМАТ. `false`: все. Значение по-умолчанию - true.
 - `ufps-postal-code` — `true`: добавлять в ответ индекс УФПС для найденного отделения, `false`: не добавлять. Значение по-умолчанию: `false`.

JSON-Schema ответа на запрос поиска почтового отделения по индексу: [pochta-ru-postoffice-by-zip-schema.json](./pochta-ru-postoffice-by-zip-schema.json).

### Поиск обслуживающего ОПС по адресу

HTTP-метод: `GET`
URL:  `/postoffice/1.0/by-address`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `address` — Строка с адресом. Следует учесть, что чем точнее адрес, тем точнее будет поиск. Пример: Санкт-Петербург, улица Победы, 15к1
 - `top` — Количество ближайших почтовых отделений в результате поиска (Опционально). По умолчанию равно 3

JSON-Schema ответа на запрос поиска почтового отделения по адресу: [pochta-ru-postoffice-by-address-schema.json](./pochta-ru-postoffice-by-address-schema.json).

### Поиск почтовых сервисов ОПС

HTTP-метод: `GET`
URL:  `/postoffice/1.0/{postal-code}/services`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `postal-code` — Индекс почтового отделения

JSON-Schema ответа на запрос поиска почтовых сервисов ОПС: [pochta-ru-postoffice-services-schema.json](./pochta-ru-postoffice-services-schema.json).

### Поиск почтовых сервисов ОПС по идентификатору группы сервисов

HTTP-метод: `GET`
URL:  `/postoffice/1.0/{postal-code}/services/{service-group-id}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `postal-code` — Индекс почтового отделения
- `service-group-id` — Идентификатор группы сервисов.

JSON-Schema ответа на запрос поиска почтовых сервисов ОПС по идентификатору группы: [pochta-ru-postoffice-services-by-group-schema.json](./pochta-ru-postoffice-services-by-group-schema.json).

### Поиск почтовых отделений по координатам

HTTP-метод: `GET`
URL:  `/postoffice/1.0/nearby`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `latitude` —  Широта
 - `longitude` — Долгота
 - `top` — Количество ближайших почтовых отделений в результате поиска (Опционально). По умолчанию равно 3
 - `filter` — Дополнительное ограничение по времени работы для поиска ОПС. Возможные значения `ALL`, `ROUND_THE_CLOCK`, `CURRENTLY_WORKING`, `WORK_ON_WEEKENDS`
 - `current-date-time` — Текущее клиентское дата-время в таймзоне клиента. Формат: yyyy-MM-dd'T'HH:mm:ss. Данный параметр используется для определения отделений, работающих в данный момент, т.е. если эти данные нужны, параметр передавать обязательно!
 - `hide-private` — Исключать не публичные отделения (Опционально). По-умолчанию не исключать (false).
 - `filter-by-office-type` — Фильтр по типам объектов в ответе. `true`: ГОПС, СОПС, ПОЧТОМАТ. `false`: все. Значение по-умолчанию - true.
 - `yandex-address` — Адрес в том формате, в котором возвращает его сервис Яндекса для адреса, указанного пользователем. Пример: Санкт-Петербург, улица Победы, 15к1. Параметр необходим для определения является ли переданный адрес точным адресом отделения. Требует также заполненного параметра geoObject. (опционально)
 - `geo-object` — JSON-строка, содержащая объект GeoObject, получаемый для адреса в сервисе Яндекса. См. api.yandex.ru. Требует также заполненного параметра 'yandex-address'. (опционально)

JSON-Schema ответа на запрос поиска почтовых отделений по гео-координатам: [pochta-ru-postoffice-nearby-schema.json](./pochta-ru-postoffice-nearby-schema.json).
### Поиск почтовых индексов в населённом пункте

HTTP-метод: `GET`
URL:  `/postoffice/1.0/settlement.offices.codes`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `settlement` — Название населённого пункта (например Екатеринбург)
 - `region` — Область/край/республика, где расположен населённый пункт (например Свердловская)
 - `district` — Район, где расположен населённый пункт (для деревень, посёлков и т. д. - например Сухоложский)

JSON-Schema ответа на запрос поиска индексов почтовых отделений в населённом пункте: [pochta-ru-postoffice-settlement-codes-schema.json](./pochta-ru-postoffice-settlement-codes-schema.json).

### Выгрузка из паспорта ОПС

Выгружает данные ОПС, ПВЗ, Почтоматов из Паспорта ОПС.

Генерирует и возвращает zip архив с текстовым файлом TYPEdd_MMMM_yyyy.txt, где:

- `TYPE` — тип объекта
- `dd_MMMM_yyyy` — время создания архива

HTTP-метод: `GET`
URL:  `/postoffice/1.0/settlement.offices.codes`
Accept: `application/octet-stream`

### Получение списка свободных таймслотов

Получение списка свободных таймслотов с учетом плановой даты передачи груза на ОПС. Данный метод необходимо вызывать, когда заказ еще не создан.

HTTP-метод: `GET`
URL:  `/external/v1/timeslots-by-postindex`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `postIndexFrom` — Почтовый индекс отправителя
 - `postIndexTo` — Почтовый индекс получателя
 - `contractNumber` — (Опционально). Номер договора
 - `plannedShippingDate` — Плановая дата в формате: yyyy-MM-dd
 - `address` — Адрес получателя
 - `workTypeCode` — Код типа работ. В текущей реализации может принимать значение: `delivery`
 - `mailTypeCode` — Код типа отправления. В текущей реализации может принимать значение `24` (Курьер онлайн)

JSON-Schema ответа на запрос получения списка свободных таймслотов: [pochta-ru-timeslots-by-postindex-schema.json](./pochta-ru-timeslots-by-postindex-schema.json).
### Оформление бронирования таймслота

Бронирует отправление на таймслот по id. В качестве ШПИ может использоваться как ШПИ так и UUID.
Выполняется проверка наличия указанного в запросе таймслота путем вызова метода: timeslotsByAddress/`external-timeslots-by-postindex`. В случае несовпадения id таймслота в запросе id таймслота из ответа то метод timeslotsByAddress/`external-timeslots-by-postindex` возвращается ошибка `TIMESLOT_NOT_FOUND`.

HTTP-метод: `POST`
URL:  `/external/v1/booking-by-postindex`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос бронирования таймслота для отправления: [pochta-ru-timeslot-booking-by-postindex-schema.json](./pochta-ru-timeslot-booking-by-postindex-schema.json).

### Формирование заказа с таймслотом

Заменяет UUID заказа, созданный на этапе предварительного бронирования на ШПИ

HTTP-метод: `PUT`
URL:  `/external/v1/booking-by-postindex/`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `uuid` — будет заменен на реальный ШПИ из параметра barcode

JSON-Schema запроса и ответа на запрос формирования заказа с таймслотом: [pochta-ru-put-timeslot-booking-by-poistindex-schema.json](./pochta-ru-put-timeslot-booking-by-poistindex-schema.json).

### Получение списка свободных таймслотов при перебронировании

Получение списка свободных таймслотов при перебронировании. Данный метод необходимо вызывать, когда заказ существует в системе на любых этапах обработки.

HTTP-метод: `GET`
URL:  `/external/v1/timeslots-for-rebooking`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `uuidOrBarcode` — UUID предварительного бронирования или ШПИ
 - `contractNumber` — (Опционально). Номер договора
 - `plannedShippingDate` — Плановая дата в формате: yyyy-MM-dd

JSON-Schema ответа на запрос получения списка свободных таймслотов при перебронировании: [pochta-ru-timeslots-for-rebooking-schema.json](./pochta-ru-timeslots-for-rebooking-schema.json).

### Перебронирование интервала доставки

Метод бронирует новый таймслот для РПО на новую дату по идентификатору таймслота, и ШПИ РПО. Ранее забронированный интервал помечается как удаленный, емкость интервала увеличивается на 1.

HTTP-метод: `POST`
URL:  `/external/v1/booking-by-postindex`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос перебронирования интервала доставки: [pochta-ru-timeslot-rebooking-by-postindex-schema.json](./pochta-ru-timeslot-rebooking-by-postindex-schema.json).

### Отмена заказа на таймслот

Отменяет отправление зарегистрированный на таймслот по ШПИ или UUID.

HTTP-метод: `DELETE`
URL:  `/external/v1/booking-by-postindex/{uuid-or-barcode}`
Content-Type: `application/json;charset=UTF-8`
Параметры в URL:
- `uuid-or-barcode` — UUID предварительного бронирования или ШПИ.

JSON-Schema ответа на запрос отмены заказа на таймслот: [pochta-ru-timeslot-delete-booking-by-postindex-schema.json](./pochta-ru-timeslot-delete-booking-by-postindex-schema.json).

### Данные об отправлениях на долгосрочном хранении

HTTP-метод: `GET`
URL:  `/external/v1/timeslots-for-rebooking`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `query` — Условие для поиска: номер заказа или ШПИ

JSON-Schema ответа на запрос данных об отправлениях на долгосрочном хранении: [pochta-ru-long-term-archive-search-schema.json](./pochta-ru-long-term-archive-search-schema.json).

### Создание возвратного отправления для ранее созданного отправления

HTTP-метод: `PUT`
URL:  `/1.0/returns`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на создание возвратного отправления для ранее созданного отправления: [pochta-ru-returns-put-schema.json](./pochta-ru-returns-put-schema.json).

### Создание отдельного возвратного отправления

Создает возвратное отправление без прямого.

HTTP-метод: `PUT`
URL:  `/1.0/returns/return-without-direct`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на создание отдельного возвратного отправления: [pochta-ru-return-without-direct-schema.json](./pochta-ru-return-without-direct-schema.json).

### Удаление отдельного возвратного отправления

Удаляет отдельное возвратное отправление.

HTTP-метод: `DELETE`
URL:  `/1.0/returns/delete-separate-return`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `barcode` — ШПИ возвратного отправления

JSON-Schema ответа на запрос на удаление отдельного возвратного отправления: [pochta-ru-delete-separate-return-schema.json](./pochta-ru-delete-separate-return-schema.json).

### Редактирование отдельного возвратного отправления

HTTP-метод: `POST`
URL:  `/1.0/returns/`
Content-Type: `application/json;charset=UTF-8`
GET-параметры запроса:
 - `barcode` — ШПИ возвратного отправления

JSON-Schema запроса и ответа на запрос на редактирование отдельного возвратного отправления: [pochta-ru-returns-post-schema.json](./pochta-ru-returns-post-schema.json).

### Текущие точки сдачи

Возвращает список текущих точек сдачи.

HTTP-метод: `GET`
URL:  `/1.0/user-shipping-points`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema ответа на запрос списка текущих точек сдачи: [pochta-ru-user-shipping-points-schema.json](./pochta-ru-user-shipping-points-schema.json).

### Текущие настройки пользователя

HTTP-метод: `GET`
URL:  `/1.0/settings`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema ответа на запрос текущих настройках пользователя: [pochta-ru-user-settings-schema.json](./pochta-ru-user-settings-schema.json).

### Нормализация адреса

Разделяет и помещает сущности переданных адресов (город, улица) в соответствующие поля возвращаемого объекта. Параметр `id` (идентификатор записи) используется для установления соответствия переданных и полученных записей, так как порядок сортировки возвращаемых записей не гарантируется. Метод автоматически ищет и возвращает индекс близлежащего ОПС по указанному адресу.
Адрес считается корректным к отправке, если в ответе запроса:
- `quality-code`=`GOOD`, `POSTAL_BOX`, `ON_DEMAND` или `UNDEF_05`;
- `validation-code`=`VALIDATED`, `OVERRIDDEN` или `CONFIRMED_MANUALLY`.

HTTP-метод: `POST`
URL:  `/1.0/clean/address`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на нормализацию адреса: [pochta-ru-clean-address-schema.json](./pochta-ru-clean-address-schema.json).

### Нормализация ФИО

Очищает, разделяет и помещает значения ФИО в соответствующие поля возвращаемого объекта. Параметр `id` (идентификатор записи) используется для установления соответствия переданных и полученных записей, так как порядок сортировки возвращаемых записей не гарантируется.

HTTP-метод: `POST`
URL:  `/1.0/clean/physical`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на нормализацию ФИО: [pochta-ru-clean-physical-schema.json](./pochta-ru-clean-physical-schema.json).

### Нормализация телефона

Принимает номера телефонов в неотформатированном виде, который может включать пробелы, символы: +-(). Очищает, разделяет и помещает сущности телефона (код города, номер) в соответствующие поля возвращаемого объекта. Если номер телефона 11-ти значный (мобильный), то дополнительные параметры, кроме `original-phone` и `id`, указывать не обязательно. Если номер телефона стационарный, то необходимо опционально указать дополнительные параметры для определения кода города. Параметр `id` (идентификатор записи) используется для установления соответствия переданных и полученных записей, так как порядок сортировки возвращаемых записей не гарантируется.

HTTP-метод: `POST`
URL:  `/1.0/clean/phone`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на нормализацию телефона: [pochta-ru-clean-phone-schema.json](./pochta-ru-clean-phone-schema.json).

### Расчет стоимости пересылки

Рассчитывает стоимость пересылки в зависимости от указанных входных данных. Индекс ОПС точки отправления берется из профиля клиента. Возвращаемые значения указываются в копейках.

HTTP-метод: `POST`
URL:  `/1.0/tariff`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema запроса и ответа на запрос на расчет стоимости пересылки: [pochta-ru-tariff-schema.json](./pochta-ru-tariff-schema.json).

### Текущее количество запросов по API

Возвращает общее количество запросов в сутки, разрешенное для пользователя, и остаток в текущих сутках.

HTTP-метод: `GET`
URL:  `/1.0/settings/limit`
Content-Type: `application/json;charset=UTF-8`

JSON-Schema ответа на запрос о текущем количестве запросов по API: [pochta-ru-settings-limit-schema.json](./pochta-ru-settings-limit-schema.json).

## Описание свойств

### Тип адреса

| Значение  | Описание                           |
| --------- | ---------------------------------- |
| `DEFAULT` | Стандартный (улица, дом, квартира) |
| `PO_BOX`  | Абонентский ящик                   |
| `DEMAND`  | До востребования                   |
| `UNIT`    | Для военных частей                 |

### Категория партии

| Значение                                     | Описание                                        |
| -------------------------------------------- | ----------------------------------------------- |
| `SIMPLE`                                     | Простое                                         |
| `ORDERED`                                    | Заказное                                        |
| `ORDINARY`                                   | Обыкновенное                                    |
| `WITH_DECLARED_VALUE`                        | С объявленной ценностью                         |
| `WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`   | С объявленной ценностью и наложенным платежом   |
| `WITH_DECLARED_VALUE_AND_COMPULSORY_PAYMENT` | С объявленной ценностью и обязательным платежом |
| `WITH_DECLARED_VALUE_AND_COMPULSORY_PAYMENT` | С объявленной ценностью и обязательным платежом |
| `WITH_COMPULSORY_PAYMENT`                    | С обязательным платежом                         |
| `COMBINED`                                   | Комбинированное                                 |

### Категория РПО

| Значение                                            | Описание                                                      |
| --------------------------------------------------- | ------------------------------------------------------------- |
| `SIMPLE`                                            | Простое                                                       |
| `ORDERED`                                           | Заказное                                                      |
| `ORDINARY`                                          | Обыкновенное                                                  |
| `WITH_DECLARED_VALUE`                               | С объявленной ценностью                                       |
| `WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`          | С объявленной ценностью и наложенным платежом                 |
| `WITH_DECLARED_VALUE_AND_COMPULSORY_PAYMENT`        | С объявленной ценностью и обязательным платежом               |
| `WITH_COMPULSORY_PAYMENT`                           | С обязательным платежом                                       |
| `COMBINED_ORDINARY`                                 | Комбинированное обыкновенное                                  |
| `COMBINED_WITH_DECLARED_VALUE`                      | Комбинированное с объявленной ценностью                       |
| `COMBINED_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY` | Комбинированное с объявленной ценностью и наложенным платежом |

### Вид РПО

| Значение              | Описание                |
| --------------------- | ----------------------- |
| `POSTAL_PARCEL`       | Посылка "нестандартная" |
| `ONLINE_PARCEL`       | Посылка "онлайн"        |
| `ONLINE_COURIER`      | Курьер "онлайн"         |
| `EMS`                 | Отправление EMS         |
| `EMS_OPTIMAL`         | EMS оптимальное         |
| `EMS_RT`              | EMS РТ                  |
| `EMS_TENDER`          | EMS тендер              |
| `LETTER`              | Письмо                  |
| `LETTER_CLASS_1`      | Письмо 1-го класса      |
| `BANDEROL`            | Бандероль               |
| `BUSINESS_COURIER`    | Бизнес курьер           |
| `BUSINESS_COURIER_ES` | Бизнес курьер экпресс   |
| `PARCEL_CLASS_1`      | Посылка 1-го класса     |
| `BANDEROL_CLASS_1`    | Бандероль 1-го класса   |
| `VGPO_CLASS_1`        | ВГПО 1-го класса        |
| `SMALL_PACKET`        | Мелкий пакет            |
| `EASY_RETURN`         | Легкий возврат          |
| `VSD`                 | Отправление ВСД         |
| `ECOM`                | ЕКОМ                    |
| `ECOM_MARKETPLACE`    | ЕКОМ Маркетплейс        |
| `HYPER_CARGO`         | Доставка день в день    |
| `COMBINED`            | Комбинированное         |

### Продукты

| Значение                                                    | Описание                                                          |
| ----------------------------------------------------------- | ----------------------------------------------------------------- |
| `LETTER_ORDERED`                                            | Заказное письмо                                                   |
| `LETTER_WITH_DECLARED_VALUE`                                | Письмо с объявленной ценностью                                    |
| `LETTER_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`           | Письмо с объявленной ценностью и наложенным платежом              |
| `INTERNATIONAL_LETTER_ORDERED`                              | Международное заказное письмо                                     |
| `BANDEROL_SIMPLE`                                           | Простая бандероль (консолидатор)                                  |
| `BANDEROL_ORDERED`                                          | Заказная бандероль                                                |
| `BANDEROL_WITH_DECLARED_VALUE`                              | Бандероль с объявленной ценностью                                 |
| `BANDEROL_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`         | Бандероль с объявленной ценностью и наложенным платежом           |
| `POSTAL_PARCEL_ORDINARY`                                    | Посылка обыкновенная                                              |
| `POSTAL_PARCEL_WITH_DECLARED_VALUE`                         | Посылка с объявленной ценностью                                   |
| `POSTAL_PARCEL_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`    | Посылка с объявленной ценностью и наложенным платежом             |
| `INTERNATIONAL_POSTAL_PARCEL_ORDINARY`                      | Посылка обыкновенная международная                                |
| `EMS_ORDINARY`                                              | EMS обыкновенное                                                  |
| `EMS_WITH_DECLARED_VALUE`                                   | EMS с объявленной ценностью                                       |
| `EMS_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`              | EMS с объявленной ценностью и наложенным платежом                 |
| `EMS_OPTIMAL_ORDINARY`                                      | EMS оптимальное обыкновенное                                      |
| `EMS_OPTIMAL_WITH_DECLARED_VALUE`                           | EMS оптимальное с объявленной ценностью                           |
| `EMS_OPTIMAL_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`      | EMS оптимальное с объявленной ценностью и наложенным платежом     |
| `EMS_RT_ORDINARY`                                           | EMS РТ                                                            |
| `EMS_RT_WITH_DECLARED_VALUE`                                | EMS с объявленной ценностью                                       |
| `ONLINE_PARCEL_ORDINARY`                                    | Посылка онлайн обыкновенная                                       |
| `ONLINE_PARCEL_WITH_DECLARED_VALUE`                         | Посылка онлайн с объявленной ценностью                            |
| `ONLINE_PARCEL_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`    | Посылка онлайн с объявленной ценностью и наложенным платежом      |
| `ONLINE_COURIER_ORDINARY`                                   | Курьер онлайн обыкновенное                                        |
| `ONLINE_COURIER_WITH_DECLARED_VALUE`                        | Курьер онлайн с объявленной ценностью                             |
| `ONLINE_COURIER_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`   | Курьер онлайн с объявленной ценностью и наложенным платежом       |
| `BUSINESS_COURIER_ORDINARY`                                 | Бизнес Курьер обыкновненное                                       |
| `BUSINESS_COURIER_WITH_DECLARED_VALUE`                      | Бизнес Курьер с объявленной ценностью                             |
| `BUSINESS_COURIER_ES_ORDINARY`                              | Бизнес Курьер экспресс обыкновненное                              |
| `BUSINESS_COURIER_ES_WITH_DECLARED_VALUE`                   | Бизнес Курьер экспресс с объявленной ценностью                    |
| `PARCEL_CLASS_1_ORDINARY`                                   | Посылка 1-го класса обыкновенная                                  |
| `PARCEL_CLASS_1_WITH_DECLARED_VALUE`                        | Посылка 1-го класса с объявленной ценностью                       |
| `PARCEL_CLASS_1_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`   | Посылка 1-го класса с объявленной ценностью и наложенным платежом |
| `LETTER_CLASS_1_ORDERED`                                    | Письмо 1-го класса заказное                                       |
| `LETTER_CLASS_1_WITH_DECLARED_VALUE`                        | Письмо 1-го класса с объявленной ценностью                        |
| `LETTER_CLASS_1_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY`   | Письмо 1-го класса с объявленной ценностью и наложенным платежом  |
| `BANDEROL_CLASS_1_ORDERED`                                  | Бандероль 1 класса заказное                                       |
| `BANDEROL_CLASS_1_WITH_DECLARED_VALUE`                      | Бандероль 1 класса с объявленной ценностью                        |
| `BANDEROL_CLASS_1_WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY` | Бандероль 1 класса с объявленной ценностью и наложенным платежом  |
| `LETTER_SIMPLE`                                             | Письмо обыкновенное (консолидатор)                                |
| `SINGLE_LETTER_SIMPLE`                                      | Простое письмо единичное                                          |
| `SINGLE_BANDEROL_SIMPLE`                                    | Простая бандероль единичная                                       |
| `SMALL_PACKET_ORDERED`                                      | Мелкий пакет заказной                                             |
| `INTERNATIONAL_EMS_ORDINARY`                                | EMS международное обыкновенное                                    |
| `INTERNATIONAL_SINGLE_LETTER_SIMPLE`                        | Международное простое письмо                                      |
| `VGPO_CLASS_1_ORDERED`                                      | ВГПО 1-го класса заказное                                         |
| `VGPO_CLASS_1_SIMPLE`                                       | ВГПО 1-го класса простое                                          |
| `EMS_TENDER_ORDINARY`                                       | EMS Тендер                                                        |
| `EMS_TENDER_WITH_DECLARED_VALUE`                            | EMS Тендер с объявленной ценностью                                |
| `VSD_ORDINARY`                                              | Отправление ВСД                                                   |
| `ECOM_ORDINARY`                                             | ЕКОМ обыкновенное                                                 |
| `ECOM_WITH_COMPULSORY_PAYMENT`                              | ЕКОМ с обязательным платежом                                      |
| `ECOM_MARKETPLACE_WITH_DECLARED_VALUE`                      | ЕКОМ Маркетплейс с объявленной ценностью                          |
| `EASY_RETURN_ORDINARY`                                      | Легкий возврат обыкновенное                                       |
| `EASY_RETURN_WITH_DECLARED_VALUE`                           | Легкий возврат с объявленной ценностью                            |

### Статусы партии

| Значение   | Описание                                                 |
| ---------- | -------------------------------------------------------- |
| `CREATED`  | Партия создана                                           |
| `FROZEN`   | Партия в процессе приема, редактирование запрещено       |
| `ACCEPTED` | Партия принята в отделении связи                         |
| `SENT`     | По заказам в партии существуют данные в сервисе трекинга |
| `ARCHIVED` | Партия находится в архиве                                |

### Коды отметок внутренних и международных отправлений

Приграничная

| Значение                       | Описание                            |
| ------------------------------ | ----------------------------------- |
| `WITHOUT_MARK`                 | Без отметки                         |
| `WITH_SIMPLE_NOTICE`           | С простым уведомлением              |
| `WITH_ORDER_OF_NOTICE`         | С заказным уведомлением             |
| `WITH_INVENTORY`               | С описью                            |
| `CAUTION_FRAGILE`              | Осторожно (Хрупкая/Терморежим)      |
| `HEAVY_HANDED`                 | Тяжеловесная                        |
| `LARGE_BULKY`                  | Крупногабаритная (Громоздкая)       |
| `WITH_DELIVERY`                | С доставкой (Доставка нарочным)     |
| `AWARDED_IN_OWN_HANDS`         | Вручить в собственные руки          |
| `WITH_DOCUMENTS`               | С документами                       |
| `WITH_GOODS`                   | С товарами                          |
| `NO_RETURN`                    | Возврату не подлежит                |
| `NONSTANDARD`                  | Нестандартная                       |
| `BORDER`                       |                                     |
| `INSURED`                      | С ОЦ                                |
| `WITH_ELECTRONIC_NOTIFICATION` | С электронным уведомлением          |
| `BUSINESS_COURIER_EXPRESS`     | Курьер бизнес-экспресс              |
| `NONSTANDARD_UPTO_10KG`        | Нестандартная до 10 кг              |
| `NONSTANDARD_UPTO_20KG`        | Нестандартная до 20 кг              |
| `WITH_CASH_ON_DELIVERY`        | С наложенным платежом               |
| `SAFETY_GUARANTEE`             | Гарантия сохранности                |
| `ASSURE_PACKAGE`               | Заверительный пакет                 |
| `COURIER_DELIVERY`             | Доставка курьером                   |
| `COMPLETENESS_CHECKING`        | Проверка комплектности              |
| `OVERSIZED`                    | Негабаритная                        |
| `RUPOST_PACKAGE`               | В упаковке Почты России             |
| `DELIVERY_WITH_COD`            | Оплата при получении                |
| `VSD`                          | Возврат сопроводительных документов |
| `EASY_RETURN`                  | Легкий возврат                      |

### Способы оплаты

| Значение              | Описание           |
| --------------------- | ------------------ |
| `CASHLESS`            | Безналичный расчет |
| `STAMP`               | Оплата марками     |
| `FRANKING`            | Франкирование      |
| `TO_FRANKING`         | На франкировку     |
| `ONLINE_PAYMENT_MARK` | Знак онлайн оплаты |

### Вид транспортировки

| Значение   | Описание                                       |
| ---------- | ---------------------------------------------- |
| `SURFACE`  | Наземный                                       |
| `AVIA`     | Авиа                                           |
| `COMBINED` | Комбинированный                                |
| `EXPRESS`  | Системой ускоренной почты                      |
| `STANDARD` | Используется для отправлений "EMS Оптимальное" |

### Категория уведомления о вручении РПО

| Значение     | Описание    |
| ------------ | ----------- |
| `SIMPLE`     | Простое     |
| `ORDERED`    | Заказное    |
| `ELECTRONIC` | Электронное |

### Код качества нормализации адреса

| Значение     | Описание                                |
| ------------ | --------------------------------------- |
| `GOOD`       | Пригоден для почтовой рассылки          |
| `ON_DEMAND`  | До востребования                        |
| `POSTAL_BOX` | Абонентский ящик                        |
| `UNDEF_01`   | Не определен регион                     |
| `UNDEF_02`   | Не определен город или населенный пункт |
| `UNDEF_03`   | Не определена улица                     |
| `UNDEF_04`   | Не определен номер дома                 |
| `UNDEF_05`   | Не определена квартира/офис             |
| `UNDEF_06`   | Не определен                            |
| `UNDEF_07`   | Иностранный адрес                       |

### Код проверки нормализации адреса

| Значение                                      | Описание                                       |
| --------------------------------------------- | ---------------------------------------------- |
| `CONFIRMED_MANUALLY`                          | Подтверждено контролером                       |
| `VALIDATED`                                   | Уверенное распознавание                        |
| `OVERRIDDEN`                                  | Распознан: адрес был перезаписан в справочнике |
| `NOT_VALIDATED_HAS_UNPARSED_PARTS`            | На проверку, неразобранные части               |
| `NOT_VALIDATED_HAS_ASSUMPTION`                | На проверку, предположение                     |
| `NOT_VALIDATED_HAS_NO_MAIN_POINTS`            | На проверку, нет основных частей               |
| `NOT_VALIDATED_HAS_NUMBER_STREET_ASSUMPTION`  | На проверку, предположение по улице            |
| `NOT_VALIDATED_HAS_NO_KLADR_RECORD`           | На проверку, нет в КЛАДР                       |
| `NOT_VALIDATED_HOUSE_WITHOUT_STREET_OR_NP`    | На проверку, нет улицы или населенного пункта  |
| `NOT_VALIDATED_HOUSE_EXTENSION_WITHOUT_HOUSE` | На проверку, нет дома                          |
| `NOT_VALIDATED_HAS_AMBI`                      | На проверку, неоднозначность                   |
| `NOT_VALIDATED_EXCEDED_HOUSE_NUMBER`          | На проверку, большой номер дома                |
| `NOT_VALIDATED_INCORRECT_HOUSE`               | На проверку, некорректный дом                  |
| `NOT_VALIDATED_INCORRECT_HOUSE_EXTENSION`     | На проверку, некорректное расширение дома      |
| `NOT_VALIDATED_FOREIGN`                       | Иностранный адрес                              |
| `NOT_VALIDATED_DICTIONARY`                    | На проверку, не по справочнику                 |

### Код качества нормализации ФИО

| Значение             | Описание                 |
| -------------------- | ------------------------ |
| `CONFIRMED_MANUALLY` | Подтверждено контролером |
| `EDITED`             | Правильное значение      |
| `NOT_SURE`           | Сомнительное значение    |

### Код качества нормализации телефона

| Значение                    | Описание                                       |
| --------------------------- | ---------------------------------------------- |
| `CONFIRMED_MANUALLY`        | Подтверждено контролером                       |
| `GOOD`                      | Корректный телефонный номер                    |
| `GOOD_REPLACED_CODE`        | Изменен код телефонного номера                 |
| `GOOD_REPLACED_NUMBER`      | Изменен номер телефона                         |
| `GOOD_REPLACED_CODE_NUMBER` | Изменен код и номер телефона                   |
| `GOOD_CITY_CONFLICT`        | Конфликт по городу                             |
| `GOOD_REGION_CONFLICT`      | Конфликт по региону                            |
| `FOREIGN`                   | Иностранный телефонный номер                   |
| `CODE_AMBI`                 | Неоднозначный код телефонного номера           |
| `EMPTY`                     | Пустой телефонный номер                        |
| `GARBAGE`                   | Телефонный номер содержит некорректные символы |
| `GOOD_CITY`                 | Восстановлен город в телефонном номере         |
| `GOOD_EXTRA_PHONE`          | Запись содержит более одного телефона          |
| `UNDEF`                     | Телефон не может быть распознан                |
| `INCORRECT_DATA`            | Телефон не может быть распознан                |

### Тип операции из трекинга

| Значение                                 | Описание                                               |
| ---------------------------------------- | ------------------------------------------------------ |
| `UNKNOWN`                                | Тип операции не определен                              |
| `ACCEPTING`                              | Прием                                                  |
| `GIVING`                                 | Вручение                                               |
| `RETURNING`                              | Возврат                                                |
| `DELIVERING`                             | Досылка почты                                          |
| `SKIPPING`                               | Невручение                                             |
| `STORING`                                | Хранение                                               |
| `HOLDING`                                | Временное хранение                                     |
| `PROCESSING`                             | Обработка                                              |
| `IMPORTING`                              | Импорт международной почты                             |
| `EXPORTING`                              | Экспорт международной почты                            |
| `CUSTOM_ACCEPTING`                       | Принято таможней                                       |
| `TRYING`                                 | Неудачная попытка вручения                             |
| `REGISTERING`                            | Регистрация отправки                                   |
| `CUSTOM_LEGALIZING`                      | Таможенное оформление                                  |
| `DELIGATING`                             | Передача на временное хранение                         |
| `DESTROYING`                             | Уничтожение                                            |
| `ACCOUNTING`                             | Передача вложения на баланс                            |
| `LOSS_REGISTRATION`                      | Регистрация утраты                                     |
| `CUSTOM_DUTY_RECEIVING`                  | Таможенные платежи поступили                           |
| `DM_REGISTRATION`                        | Регистрация                                            |
| `DM_DELIVERING`                          | Доставка                                               |
| `DM_NON_DELIVERING`                      | Недоставка                                             |
| `TEMPORARY_STORING_ARRIVING`             | Поступление на временное хранение                      |
| `PROLONGATION_CUSTOM_STORING`            | Продление срока выпуска таможней                       |
| `OPENING`                                | Вскрытие                                               |
| `CANCELLATION`                           | Отмена                                                 |
| `ID_ASSIGNMENT`                          | Присвоен идентификатор                                 |
| `ELECTRONIC_REGISTRATION_RECEIVED`       | Получена электронная регистрация                       |
| `REGISTRATION_PASSAGE_IN_MMPO`           | Регистрация прохождения в ММПО                         |
| `SRM_DISPATCH`                           | Отправка SRM                                           |
| `CARRIER_PROCESSING`                     | Обработка перевозчиком                                 |
| `APO_RECEIPT`                            | Поступление АПО                                        |
| `INTERNATIONAL_PROCESSING`               | Международная обработка                                |
| `ELECTRONIC_NOTIFICATION_UPLOADED`       | Электронное уведомление загружено                      |
| `REFUSED_COURIER_DELIVERY`               | Отказ в курьерской доставке                            |
| `CLARIFICATION_DELIVERY_PAYMENT_TYPE`    | Уточнение вида оплаты доставки                         |
| `PRE_FORMALIZATION`                      | Предварительное оформление                             |
| `RETAINED_FOR_CLARIFICATION_FROM_SENDER` | Задержка для уточнений у отправителя                   |
| `PRELIMINARY_CUSTOMS_DECLARATION`        | Предварительное таможенное декларирование              |
| `CUSTOMS_CONTROL`                        | Таможенный контроль                                    |
| `CUSTOMS_CHARGES_PROCESSING`             | Обработка таможенных платежей                          |
| `SECOND_UNSUCCESSFUL_DELIVERY_ATTEMPT`   | Вторая неудачная попытка вручения                      |
| `DELIVERY_PERMITTED`                     | Вручение разрешено                                     |
| `ACCEPTANCE_REJECTED`                    | Отказ в приеме                                         |
| `ELECTRONIC_NOTIFICATION_REFUSED`        | Отказ от отправки электронного уведомления получателем |
| `ID_ASSIGNMENT_CANCELATION`              | Отмена присвоения идентификатора                       |
| `PARTIAL_DELIVERY`                       | Частичное вручение                                     |
| `EXTEND_SHELF_LIFE_REFUSED`              | Отказ в продлении срока хранения                       |

### Атрибут операции из трекинга

| Значение                                       | Описание                                                                                                                                                                                                                                                                                                           |
| ---------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `UNKNOWN`                                      | Атрибут операции не определен                                                                                                                                                                                                                                                                                      |
| `FOREIGN_ACCEPTING`                            | Прием иностранного отправления                                                                                                                                                                                                                                                                                     |
| `SINGLE_ACCEPTING`                             | Единичный                                                                                                                                                                                                                                                                                                          |
| `PARTIAL_ACCEPTING`                            | Партионный                                                                                                                                                                                                                                                                                                         |
| `PARTIAL_ACCEPTING_REMOTE`                     | Партионный электронно                                                                                                                                                                                                                                                                                              |
| `GIVING_COMMON`                                | Случай операции "Вручение" зарубежного отправления, когда код операции и аттрибута операции, получаемый в ответе, равен 0, т.е. мы не в состоянии по описанию операции "Вручение" точно определить было ли отправление получено адресатом или отправителем, а также как оно было получено, через почтомат или нет. |
| `GIVING_RECIPIENT`                             | Вручение адресату                                                                                                                                                                                                                                                                                                  |
| `GIVING_SENDER`                                | Вручение отправителю                                                                                                                                                                                                                                                                                               |
| `GIVING_RECIPIENT_IN_PO`                       | Выдано адресату через почтомат                                                                                                                                                                                                                                                                                     |
| `GIVING_SENDER_IN_PO`                          | Выдано отправителю через почтомат                                                                                                                                                                                                                                                                                  |
| `GIVING_RECIPIENT_REMOTE`                      | Адресату электронно                                                                                                                                                                                                                                                                                                |
| `GIVING_RECIPIENT_POSTMAN`                     | Адресату почтальоном                                                                                                                                                                                                                                                                                               |
| `GIVING_SENDER_POSTMAN`                        | Отправителю почтальоном                                                                                                                                                                                                                                                                                            |
| `GIVING_RECIPIENT_COURIER`                     | Адресату курьером                                                                                                                                                                                                                                                                                                  |
| `GIVING_SENDER_COURIER`                        | Отправителю курьером                                                                                                                                                                                                                                                                                               |
| `GIVING_RECIPIENT_CONTROL`                     | Адресату с контролем ответа                                                                                                                                                                                                                                                                                        |
| `GIVING_RECIPIENT_CONTROL_POSTMAN`             | Адресату с контролем ответа почтальоном                                                                                                                                                                                                                                                                            |
| `GIVING_RECIPIENT_CONTROL_COURIER`             | Адресату с контролем ответа курьером                                                                                                                                                                                                                                                                               |
| `RETURNING_BY_EXPIRED_STORING`                 | .Истек срок хранения                                                                                                                                                                                                                                                                                               |
| `RETURNING_BY_SENDER_REQUEST`                  | Заявление отправителя                                                                                                                                                                                                                                                                                              |
| `RETURNING_BY_RECEPIENT_ABSENCE`               | Отсутствие адресата по указанному адресу                                                                                                                                                                                                                                                                           |
| `RETURNING_BY_RECEPIENT_REJECT`                | Отказ адресата                                                                                                                                                                                                                                                                                                     |
| `RETURNING_BY_RECEPIENT_DEATH`                 | Смерть адресата                                                                                                                                                                                                                                                                                                    |
| `RETURNING_BY_UNREADABLE_ADDRESS`              | Невозможно прочесть адрес адресата                                                                                                                                                                                                                                                                                 |
| `RETURNING_BY_CUSTOM`                          | Возврат таможни                                                                                                                                                                                                                                                                                                    |
| `RETURNING_BY_UNKNOWN_RECEPIENT`               | Адресат, абонирующий абонементный почтовый шкаф, не указан или указан неправильно                                                                                                                                                                                                                                  |
| `RETURNING_BY_OTHER_REASONS`                   | Иные обстоятельства                                                                                                                                                                                                                                                                                                |
| `RETURNING_BY_WRONG_ADRESS`                    | Неверный адрес                                                                                                                                                                                                                                                                                                     |
| `DELIVERING_BY_CLIENT_REQUEST`                 | По заявлению пользователя                                                                                                                                                                                                                                                                                          |
| `DELIVERING_TO_NEW_ADDRESS`                    | Выбытие адресата по новому адресу                                                                                                                                                                                                                                                                                  |
| `DELIVERING_BY_ROUTER`                         | Засылка                                                                                                                                                                                                                                                                                                            |
| `LOST`                                         | Утрачено                                                                                                                                                                                                                                                                                                           |
| `CONFISCATED`                                  | Изъято                                                                                                                                                                                                                                                                                                             |
| `SKIPPING_BY_ERROR`                            | Засылка                                                                                                                                                                                                                                                                                                            |
| `SKIPPING_BY_CUSTOM`                           | Решение таможни                                                                                                                                                                                                                                                                                                    |
| `UNDELIVERED`                                  | Доставка по указанному адресу не осуществляется                                                                                                                                                                                                                                                                    |
| `POSTE_RESTANTE_STORING`                       | До востребования                                                                                                                                                                                                                                                                                                   |
| `STORING_IN_BOX`                               | На абонементный ящик                                                                                                                                                                                                                                                                                               |
| `TEMPORAL_STORING`                             | Установленный срок хранения                                                                                                                                                                                                                                                                                        |
| `ADDITIONAL_STORING`                           | Продление срока хранения по заявлению адресата                                                                                                                                                                                                                                                                     |
| `CUSTOM_HOLDING`                               | Продление срока выпуска таможней                                                                                                                                                                                                                                                                                   |
| `UNASSIGNED`                                   | Нероздано                                                                                                                                                                                                                                                                                                          |
| `UNCLAIMED`                                    | Невостребовано                                                                                                                                                                                                                                                                                                     |
| `PROHIBITED`                                   | Содержимое запрещено к пересылке                                                                                                                                                                                                                                                                                   |
| `SORTING`                                      | Сортировка                                                                                                                                                                                                                                                                                                         |
| `SENT`                                         | Покинуло место приёма                                                                                                                                                                                                                                                                                              |
| `ARRIVED`                                      | Прибыло в место вручения                                                                                                                                                                                                                                                                                           |
| `DELIVERED_TO_SORTING`                         | Прибыло в сортировочный центр                                                                                                                                                                                                                                                                                      |
| `SORTED`                                       | Покинуло сортировочный центр                                                                                                                                                                                                                                                                                       |
| `DELIVERED_TO_EXCHANGE_HUB`                    | Прибыло в место международного обмена                                                                                                                                                                                                                                                                              |
| `PROCESSED_BY_EXCHANGE_HUB`                    | Покинуло место международного обмена                                                                                                                                                                                                                                                                               |
| `DELIVERED_TO_HUB`                             | Прибыло в место транзита                                                                                                                                                                                                                                                                                           |
| `LEAVED_HUB`                                   | Покинуло место транзита                                                                                                                                                                                                                                                                                            |
| `DELIVERED_TO_PO`                              | Прибыло в почтомат                                                                                                                                                                                                                                                                                                 |
| `EXPIRED_PO_STORING`                           | Истекает срок хранения в почтомате                                                                                                                                                                                                                                                                                 |
| `FORWARDED`                                    | Переадресовано в почтомат                                                                                                                                                                                                                                                                                          |
| `GET`                                          | Изъято из почтомата                                                                                                                                                                                                                                                                                                |
| `ARRIVED_IN_RUSSIA`                            | Прибыло на территорию РФ                                                                                                                                                                                                                                                                                           |
| `ARRIVED_IN_PARCELS_CENTER`                    | Прибыло в Центр выдачи посылок                                                                                                                                                                                                                                                                                     |
| `GIVEN_TO_COURIER`                             | Передано курьеру                                                                                                                                                                                                                                                                                                   |
| `GIVEN_FOR_REMOTE`                             | Доставлено для вручения электронно                                                                                                                                                                                                                                                                                 |
| `DELIVERED_HYBRID`                             | Прибыло в ЦГП                                                                                                                                                                                                                                                                                                      |
| `GIVEN_TO_POSTMAN`                             | Передано почтальону                                                                                                                                                                                                                                                                                                |
| `GIVEN_FOR_BOXROOM`                            | Передача в кладовую хранения                                                                                                                                                                                                                                                                                       |
| `LEFT_POSTOFFICE`                              | Покинуло место возврата/досыла                                                                                                                                                                                                                                                                                     |
| `SPECIFY_ADDRESS`                              | Уточнение адреса                                                                                                                                                                                                                                                                                                   |
| `EXPECTING_COURIER_DELIVERY`                   | Ожидает курьерской доставки                                                                                                                                                                                                                                                                                        |
| `PROLONG_STORAGE_DATE`                         | Продление срока хранения                                                                                                                                                                                                                                                                                           |
| `NOTIFICATION_SENT`                            | Направлено извещение                                                                                                                                                                                                                                                                                               |
| `NOTIFICATION_RECEIVED`                        | Доставлено извещение                                                                                                                                                                                                                                                                                               |
| `POCHTOMAT_ORDERED`                            | Доставка в почтомат заказана                                                                                                                                                                                                                                                                                       |
| `POSTMAN_ORDERED`                              | Доставка почтальоном заказана                                                                                                                                                                                                                                                                                      |
| `COURIER_ORDERED`                              | Курьерская доставка заказана                                                                                                                                                                                                                                                                                       |
| `IMPORTED`                                     | Импорт международной почты                                                                                                                                                                                                                                                                                         |
| `EXPORTED`                                     | Экспорт международной почты                                                                                                                                                                                                                                                                                        |
| `ACCEPTED_BY_CUSTOM`                           | Принято таможней                                                                                                                                                                                                                                                                                                   |
| `FAILED_BY_TEMPORAL_ABSENCE_OF_RECEPIENT`      | Временное отсутствие адресата                                                                                                                                                                                                                                                                                      |
| `FAILED_BY_DELAYING_REQUEST`                   | Доставка отложена по просьбе адресата                                                                                                                                                                                                                                                                              |
| `FAILED_BY_UNFILLED_ADDRESS`                   | Неполный адрес                                                                                                                                                                                                                                                                                                     |
| `FAILED_BY_INVALID_ADDRESS`                    | Неправильный адрес                                                                                                                                                                                                                                                                                                 |
| `FAILED_BY_RECEPIENT_LEAVING`                  | Адресат выбыл                                                                                                                                                                                                                                                                                                      |
| `FAILED_BY_RECEPINT_REJECT`                    | Отказ от получения                                                                                                                                                                                                                                                                                                 |
| `UNOVERCAMING_FAIL`                            | Обстоятельства непреодолимой силы                                                                                                                                                                                                                                                                                  |
| `FAILED_WITH_ANOTHER_REASON`                   | Иная                                                                                                                                                                                                                                                                                                               |
| `WAITING_RECEPIENT_IN_OFFICE`                  | Адресат заберет отправление сам                                                                                                                                                                                                                                                                                    |
| `RECEPIENT_NOT_FOUND`                          | Нет адресата                                                                                                                                                                                                                                                                                                       |
| `TECHNICALLY_FAILED`                           | По техническим причинам                                                                                                                                                                                                                                                                                            |
| `FAILED_BY_EXPIRATION_TIME`                    | v                                                                                                                                                                                                                                                                                                                  |
| `REGISTERED`                                   | Регистрация отправки                                                                                                                                                                                                                                                                                               |
| `CUSTOM_LEGALIZED`                             | Выпущено таможней                                                                                                                                                                                                                                                                                                  |
| `LEGALIZED`                                    | Выпущено таможней                                                                                                                                                                                                                                                                                                  |
| `CANCELED_LEGLIZATION`                         | Возвращено таможней                                                                                                                                                                                                                                                                                                |
| `PROCESSED_BY_CUSTOM`                          | Осмотрено таможней                                                                                                                                                                                                                                                                                                 |
| `REJECTED_BY_CUSTOM`                           | Отказ в выпуске                                                                                                                                                                                                                                                                                                    |
| `PASSED_WITH_CUSTOM_NOTIFY`                    | Направлено с таможенным уведомлением                                                                                                                                                                                                                                                                               |
| `PASSED_WITH_CUSTOM_TAX`                       | Направлено с обязательной уплатой таможенных платежей                                                                                                                                                                                                                                                              |
| `DELIGATED`                                    | Передача на временное хранение                                                                                                                                                                                                                                                                                     |
| `DESTROYED`                                    | Уничтожение                                                                                                                                                                                                                                                                                                        |
| `ACCOUNTED`                                    | Передача вложения на баланс                                                                                                                                                                                                                                                                                        |
| `LOSS_REGISTERED`                              | Утрата зарегистрирована                                                                                                                                                                                                                                                                                            |
| `CUSTOM_DUTY_RECEIVED`                         | Таможенные платежи поступили                                                                                                                                                                                                                                                                                       |
| `DM_REGISTERED`                                | Регистрация                                                                                                                                                                                                                                                                                                        |
| `DM_DELIVERED`                                 | Доставка                                                                                                                                                                                                                                                                                                           |
| `DM_ABSENCE_POSTBOX`                           | Недоставка                                                                                                                                                                                                                                                                                                         |
| `DM_ABSENCE_ADDRESS`                           | Недоставка                                                                                                                                                                                                                                                                                                         |
| `DM_WRONG_POSTOFFICE_INDEX`                    | Недоставка                                                                                                                                                                                                                                                                                                         |
| `DM_WRONG_ADDRESS`                             | Недоставка                                                                                                                                                                                                                                                                                                         |
| `TEMPORARY_STORING_ARRIVED`                    | Поступление на временное хранение                                                                                                                                                                                                                                                                                  |
| `CUSTOM_STORING_PROLONGED`                     | Продление срока выпуска таможней                                                                                                                                                                                                                                                                                   |
| `CUSTOM_STORING_PROLONGED_1`                   | Запрещенные вложение                                                                                                                                                                                                                                                                                               |
| `CUSTOM_STORING_PROLONGED_2`                   | Импортируемые вложения являются предметом ограничений - Несоответствующая/отсутствующая лицензия                                                                                                                                                                                                                   |
| `CUSTOM_STORING_PROLONGED_3`                   | Несоответствующий/отсутствующий сертификат о происхождении груза                                                                                                                                                                                                                                                   |
| `CUSTOM_STORING_PROLONGED_4`                   | Несоответствующая/отсутствующая таможенная декларация                                                                                                                                                                                                                                                              |
| `CUSTOM_STORING_PROLONGED_5`                   | Контакт с клиентом для запроса информации невозможен                                                                                                                                                                                                                                                               |
| `CUSTOM_STORING_PROLONGED_6`                   | Некомплектная поставка                                                                                                                                                                                                                                                                                             |
| `CUSTOM_STORING_PROLONGED_7`                   | Передано в таможенный орган                                                                                                                                                                                                                                                                                        |
| `CUSTOM_STORING_PROLONGED_8`                   | Экспортируемые вложения являются предметом ограничений - Несоответствующая/отсутствующая лицензия                                                                                                                                                                                                                  |
| `CUSTOM_STORING_PROLONGED_9`                   | Неполная/некорректная документация, ожидается дополнительная документация                                                                                                                                                                                                                                          |
| `OPENED`                                       | Вскрытие                                                                                                                                                                                                                                                                                                           |
| `CANCELED_BY_SENDER_DEFAULT`                   | Отмена операции по требованию отправителя                                                                                                                                                                                                                                                                          |
| `CANCELED_BY_SENDER`                           | Отмена операции по требованию отправителя                                                                                                                                                                                                                                                                          |
| `CANCELED_BY_OPERATOR`                         | Отмена операции из-за ошибки оператора                                                                                                                                                                                                                                                                             |
| `ID_ASSIGNED`                                  | Присвоен идентификатор                                                                                                                                                                                                                                                                                             |
| `ELECTRONIC_REGISTRATION_RECEIVED`             | Получена электронная регистрация                                                                                                                                                                                                                                                                                   |
| `REGISTRATION_PASSAGE_IN_MMPO`                 | Регистрация прохождения в ММПО                                                                                                                                                                                                                                                                                     |
| `SRM_DISPATCH`                                 | Отправка SRM                                                                                                                                                                                                                                                                                                       |
| `TRANSPORT_ARRIVED`                            | Транспорт прибыл                                                                                                                                                                                                                                                                                                   |
| `ACCEPTED`                                     | Бронирование подтверждено                                                                                                                                                                                                                                                                                          |
| `ASSIGNED_TO_LOAD_PLAN`                        | Включено в план погрузки                                                                                                                                                                                                                                                                                           |
| `REMOVED_FROM_LOAD_PLAN`                       | Исключено из плана погрузки                                                                                                                                                                                                                                                                                        |
| `TRANSPORT_LEG_COMPLETED`                      | Транспортный участок завершен                                                                                                                                                                                                                                                                                      |
| `DELIVERED`                                    | Доставлено                                                                                                                                                                                                                                                                                                         |
| `MAIL_AT_DESTINATION`                          | Почта в месте назначения                                                                                                                                                                                                                                                                                           |
| `UPLIFTED`                                     | Погружено на борт                                                                                                                                                                                                                                                                                                  |
| `EN_ROUTE`                                     | В пути                                                                                                                                                                                                                                                                                                             |
| `MAIL_ARRIVED_AT_CARRIER_FACILITY`             | Почта поступила на склад перевозчика                                                                                                                                                                                                                                                                               |
| `TRANSFER`                                     | Перегрузка                                                                                                                                                                                                                                                                                                         |
| `HANDOVER_DELIVERED`                           | Передано другому перевозчику                                                                                                                                                                                                                                                                                       |
| `HANDOVER_RECEIVED`                            | Получено от другого перевозчика                                                                                                                                                                                                                                                                                    |
| `LOADED`                                       | Погружено                                                                                                                                                                                                                                                                                                          |
| `NOT_LOADED`                                   | Не погружено                                                                                                                                                                                                                                                                                                       |
| `OFFLOADED`                                    | Выгружено                                                                                                                                                                                                                                                                                                          |
| `RECEIVED`                                     | Принято к перевозке                                                                                                                                                                                                                                                                                                |
| `RETURNED`                                     | Возвращено                                                                                                                                                                                                                                                                                                         |
| `APO_RECEIPT`                                  | Принято к перевозке                                                                                                                                                                                                                                                                                                |
| `FORWARD_TO_CARRIER`                           | Передано перевозчику                                                                                                                                                                                                                                                                                               |
| `RECEIVED_DESIGNATED_OPERATOR`                 | Получено назначенным оператором                                                                                                                                                                                                                                                                                    |
| `PROCESSING_DESIGNATED_POSTAL_OPERATOR`        | Обработка назначенным оператором                                                                                                                                                                                                                                                                                   |
| `ELECTRONIC_NOTIFICATION_UPLOADED`             | Электронное уведомление загружено                                                                                                                                                                                                                                                                                  |
| `UNDELIVERABLE_SHIPMENT_TYPE`                  | Не подлежащий доставке вид почтового отправления                                                                                                                                                                                                                                                                   |
| `EXCEEDING_WEIGHT_LIMIT`                       | Превышение предельного веса, подлежащее доставке                                                                                                                                                                                                                                                                   |
| `EXCEEDING_OVERALL_DIMENSION`                  | Превышение габаритных размеров, подлежащее доставке                                                                                                                                                                                                                                                                |
| `DEFECTIVE_SHIPMENT`                           | Дефектное почтовое отправление                                                                                                                                                                                                                                                                                     |
| `CUSTOMS_NOTIFICATION_ENCLOSED`                | Наличие Таможенного уведомления                                                                                                                                                                                                                                                                                    |
| `AGREEMENT_EXCHANGE_COD_SHIPMENT_NOT_ENCLOSED` | Отсутствие Соглашения об обмене почтовыми отправлениями с наложенным платежом                                                                                                                                                                                                                                      |
| `RETURNED_SHIPMENT`                            | Возвращенное почтовое отправление                                                                                                                                                                                                                                                                                  |
| `EXCESS_COD_AMOUNT_CHARGED_AT_HOME`            | Превышение суммы наложенного платежа, подлежащей взиманию на дому                                                                                                                                                                                                                                                  |
| `INCORRECT_DOCUMENTATION_OR_LACK_THEREOF`      | Неверно оформленные бланки или их отсутствие                                                                                                                                                                                                                                                                       |
| `INCLUDED_IN_TARIFF`                           | Включена в тариф                                                                                                                                                                                                                                                                                                   |
| `CHARGEABLE`                                   | Платная                                                                                                                                                                                                                                                                                                            |
| `PREPAID`                                      | Предоплаченная                                                                                                                                                                                                                                                                                                     |
| `PRE_FORMALIZATION`                            | Предварительное оформление                                                                                                                                                                                                                                                                                         |
| `INCORRECT_UNREADABLE_INCOMPLETE_ADDRESS`      | Неправильный/нечитаемый/неполный адрес                                                                                                                                                                                                                                                                             |
| `AT_SENDER_REQUEST`                            | По требованию отправителя                                                                                                                                                                                                                                                                                          |
| `RETAINED_INCORRECT_DISPATCH`                  | Засыл отправления                                                                                                                                                                                                                                                                                                  |
| `REGISTRATION`                                 | Регистрация                                                                                                                                                                                                                                                                                                        |
| `PRELIMINARY_DECISION`                         | Предварительное решение "выпуск разрешен"                                                                                                                                                                                                                                                                          |
| `RETAINED_CUSTOM_WITHOUT_INSPECTION`           | Отказ в выпуске товаров. Требуется предъявление таможенному органу без осмотра                                                                                                                                                                                                                                     |
| `RETAINED_CUSTOM_WITH_INSPECTION`              | Отказ в выпуске товаров. Требуется предъявление таможенному органу с осмотром                                                                                                                                                                                                                                      |
| `REFUSAL_OF_REGISTRATION`                      | Отказ в регистрации                                                                                                                                                                                                                                                                                                |
| `GOODS_NOT_SHOWN`                              | Отказ в выпуске. Товары не предъявлены                                                                                                                                                                                                                                                                             |
| `DATA_FROM_TRAIDING_PLAFORM_RECIVED`           | Данные от торговой площадки получены                                                                                                                                                                                                                                                                               |
| `RELEASED_BY_CUSTOMS`                          | Выпуск разрешен                                                                                                                                                                                                                                                                                                    |
| `REJECTED_BY_CUSTOMS`                          | Отказ в выпуске                                                                                                                                                                                                                                                                                                    |
| `PAYMENTS_PAID`                                | Платежи уплачены                                                                                                                                                                                                                                                                                                   |
| `PAYMENT_AMOUNT_WITHHELD`                      | Сумма платежа удержана УО                                                                                                                                                                                                                                                                                          |
| `PAYMENT_AMOUNT_DEBITED`                       | Сумма платежа списана ФТС                                                                                                                                                                                                                                                                                          |
| `PAYMENT_AMOUNT_FOR_WITHHELD`                  | Сумма платежа для удержания УО                                                                                                                                                                                                                                                                                     |
| `PAYMENT_AMOUNT_WITHHELD_COMPLETELY`           | Сумма платежа удержана УО полностью                                                                                                                                                                                                                                                                                |
| `PAYMENT_AMOUNT_CALCULATED`                    | Сумма платежа рассчитана УО                                                                                                                                                                                                                                                                                        |
| `ADDRESSEE_NOT_AVAILABLE`                      | Временное отсутствие адресата                                                                                                                                                                                                                                                                                      |
| `ADDRESSEE_REQUESTED_LATE_DELIVERY`            | Доставка отложена по просьбе адресата                                                                                                                                                                                                                                                                              |
| `INCOMPLETE_ADDRESS`                           | Неполный адрес                                                                                                                                                                                                                                                                                                     |
| `UNREADABLE_INCORRECT_INCOMPLETE_ADDRESS`      | Неправильный/нечитаемый/неполный адрес                                                                                                                                                                                                                                                                             |
| `SECOND_ATTEMPT_ADDRESSEE_MOVED`               | Адресат выбыл                                                                                                                                                                                                                                                                                                      |
| `ITEM_REFUSED_BY_ADDRESSEE`                    | Адресат отказался от отправления                                                                                                                                                                                                                                                                                   |
| `FORCE_MAJEURE`                                | Форс-мажор/непредвиденные обстоятельства                                                                                                                                                                                                                                                                           |
| `OTHER`                                        | Иная                                                                                                                                                                                                                                                                                                               |
| `ADDRESSEE_REQUEST_OWN_PICK_UP`                | Адресат заберет отправление сам                                                                                                                                                                                                                                                                                    |
| `ADDRESSEE_CANNOT_BE_LOCATED`                  | Адресат не доступен                                                                                                                                                                                                                                                                                                |
| `DUE_TO_TECHNICAL_DIFFICULTIES`                | Неудачная доставка                                                                                                                                                                                                                                                                                                 |
| `STORAGE_PERIOD_EXPIRED`                       | Истек срок хранения в почтомате                                                                                                                                                                                                                                                                                    |
| `SECOND_ATTEMPT_SENDER_REQUEST`                | По требованию отправителя                                                                                                                                                                                                                                                                                          |
| `ITEM_DEMAGED_AND_OR_MISSING_CONTENS`          | Отправление повреждено и/или без вложения                                                                                                                                                                                                                                                                          |
| `AWAITING_PAYMENT`                             | В ожидании оплаты сбора                                                                                                                                                                                                                                                                                            |
| `MOVED_ADDRESSEE`                              | Адресат переехал                                                                                                                                                                                                                                                                                                   |
| `SECOND_ATTEMPT_ADDRESSEE_HAS_P_O_BOX`         | У адресата есть абонентский ящик                                                                                                                                                                                                                                                                                   |
| `SECOND_ATTEMPT_NO_HOME_DELIVERY`              | Нет доставки на дом                                                                                                                                                                                                                                                                                                |
| `NOT_MEET_CUSTOMS_REQUIREMENTS`                | Не отвечает таможенным требованиям                                                                                                                                                                                                                                                                                 |
| `SECOND_ATTEMPT_INCORRECT_DOCUMENTATION`       | Неполные/недостаточные/неверные документы                                                                                                                                                                                                                                                                          |
| `IMPOSSIBLE_CONTACT`                           | Невозможно связаться с клиентом                                                                                                                                                                                                                                                                                    |
| `SECOND_ATTEMPT_ADDRESSEE_ON_STRIKE`           | Адресат бастует                                                                                                                                                                                                                                                                                                    |
| `SECOND_ATTEMPT_PROHIBITED_ARTICLES`           | Запрещенные вложения – отправление не доставлено                                                                                                                                                                                                                                                                   |
| `IMPORTATION_RESTRICTED`                       | Отказ в импорте – запрещенные вложения                                                                                                                                                                                                                                                                             |
| `DISPATCH_INCORRECT`                           | Засыл отправления                                                                                                                                                                                                                                                                                                  |
| `ADDRESSEE_DECEASE`                            | За смертью получателя                                                                                                                                                                                                                                                                                              |
| `SECOND_ATTEMPT_LOCAL_HOLIDAY`                 | Национальный праздник                                                                                                                                                                                                                                                                                              |
| `ITEM_LOST`                                    | Утрата                                                                                                                                                                                                                                                                                                             |
| `DELIVERY_PERMITTED`                           | Вручение разрешено                                                                                                                                                                                                                                                                                                 |
| `ACCEPTANCE_REJECTED`                          | Отказ в приеме                                                                                                                                                                                                                                                                                                     |
| `ELECTRONIC_NOTIFICATION_REFUSED`              | Отказ от отправки электронного уведомления получателем                                                                                                                                                                                                                                                             |
| `ID_ASSIGNMENT_CANCELLED`                      | Присвоение идентификатора отменено                                                                                                                                                                                                                                                                                 |
| `SHIPMENT_ALREADY_DELIVERED`                   | Отправление уже вручено                                                                                                                                                                                                                                                                                            |
| `REAPPLICATION`                                | Заявка на продление срока хранения получена повторно                                                                                                                                                                                                                                                               |
| `LATER_24_HOURS_BEFORE_COMPLETION`             | Заявка на продление срока хранения подана позже, чем за 24 часа окончания нормативного срока хранения                                                                                                                                                                                                              |
| `RECEIVABLES`                                  | Наличие дебиторской задолженности по корпоративному клиенту                                                                                                                                                                                                                                                        |
| `COMPANY_BAN`                                  | Наличие запрета со стороны Компании дистанционной торговли (интернет-магазина) на продление срока хранения                                                                                                                                                                                                         |
| `DELIVERY_POINT_CLOSED`                        | ПВЗ закрыт                                                                                                                                                                                                                                                                                                         |

### Тип конверта

| Значение | Описание                       |
| -------- | ------------------------------ |
| `C4`     | 229мм x 324мм                  |
| `C5`     | 162мм x 229мм                  |
| `C6`     | 114мм x 162мм                  |
| `DL`     | 110мм x 220мм                  |
| `A6`     | 105мм x 148мм                  |
| `A7`     | 74мм x 105мм                   |
| `OX`     | Стикер ЗОО X6 (99 x 105 мм)    |
| `OA`     | Стикер ЗОО А6 (105 x 148,5 мм) |

### Разряд письма

| Значение         | Описание          |
| ---------------- | ----------------- |
| `WO_RANK`        | Без разряда       |
| `GOVERNMENTAL`   | Правительственное |
| `MILITARY`       | Воинское          |
| `OFFICIAL`       | Служебное         |
| `JUDICIAL`       | Судебное          |
| `PRESIDENTIAL`   | Президентское     |
| `CREDIT`         | Кредитное         |
| `ADMINISTRATIVE` | Административное  |

### Тип печати (формат адресного ярлыка)

| Значение | Описание                                     |
| -------- | -------------------------------------------- |
| `PAPER`  | А5, 14.8 x 21 см, лазерная и струйная печать |
| `THERMO` | А6, 10 x 15 см, термопечать                  |

### Категория вложения

| Значение            | Описание             |
| ------------------- | -------------------- |
| `GIFT`              | Подарок              |
| `DOCUMENT`          | Документы            |
| `SALE_OF_GOODS`     | Продажа товара       |
| `COMMERCIAL_SAMPLE` | Коммерческий образец |
| `OTHER`             | Прочее               |

### Список стран

|Код|Наименование на русском|Наименование на английском|
|---|---|---|
|895|Абхазия|Abkhazia|
|36|Австралия|Australia|
|40|Австрия|Austria|
|31|Азербайджан|Azerbaijan|
|8|Албания|Albania|
|12|Алжир|Algeria|
|660|Ангилья|Anguilla|
|24|Ангола|Angola|
|20|Андорра|Andorra|
|10|Антарктика|Antarctica|
|28|Антигуа и барбуда|Antigua and barbuda|
|32|Аргентина|Argentina|
|51|Армения|Armenia|
|533|Аруба|Aruba|
|4|Афганистан|Afghanistan|
|44|Багамы|Bahamas|
|50|Бангладеш|Bangladesh|
|52|Барбадос|Barbados|
|48|Бахрейн|Bahrain|
|112|Беларусь|Belarus|
|84|Белиз|Belize|
|56|Бельгия|Belgium|
|204|Бенин|Benin|
|60|Бермуды|Bermuda|
|100|Болгария|Bulgaria|
|68|Боливия|Bolivia (plurinational state of)|
|535|Бонэйр, синт-эстатиус и саба|Bonaire, sint eustatius and saba|
|70|Босния и герцеговина|Bosnia and herzegovina|
|72|Ботсвана|Botswana|
|76|Бразилия|Brazil|
|92|Британские виргинские острова|Virgin islands (british)|
|86|Британские территории в индийском океане|British indian ocean territory|
|96|Бруней|Brunei darussalam|
|74|Буве острова|Bouvet island|
|854|Буркина-фасо|Burkina faso|
|108|Бурунди|Burundi|
|64|Бутан|Bhutan|
|548|Вануату|Vanuatu|
|336|Ватикан|Vatican (holy see)|
|826|Великобритания|United kingdom of great britain and northern ireland|
|348|Венгрия|Hungary|
|862|Венесуэла боливарианская республика|Venezuela (bolivarian republic of)|
|850|Виргинские острова (сша)|Virgin islands (u.s.)|
|16|Восточное самоа|American samoa|
|704|Вьетнам|Viet nam|
|266|Габон|Gabon|
|332|Гаити|Haiti|
|328|Гайана|Guyana|
|270|Гамбия|Gambia|
|288|Гана|Ghana|
|312|Гваделупа|Guadeloupe|
|320|Гватемала|Guatemala|
|324|Гвинея|Guinea|
|624|Гвинея-бисау|Guinea-bissau|
|276|Германия|Germany|
|831|Гернси|Guernsey|
|292|Гибралтар|Gibraltar|
|340|Гондурас|Honduras|
|344|Гонконг|Hong kong|
|308|Гренада|Grenada|
|304|Гренландия|Greenland|
|300|Греция|Greece|
|268|Грузия|Georgia|
|316|Гуам|Guam|
|208|Дания|Denmark|
|180|Демократическая республика конго|Congo (democratic republic of the)|
|832|Джерси|Jersey|
|262|Джибути|Djibouti|
|212|Доминика|Dominica|
|214|Доминиканская республика|Dominican republic|
|818|Египет|Egypt|
|894|Замбия|Zambia|
|732|Западная сахара|Western sahara|
|716|Зимбабве|Zimbabwe|
|376|Израиль|Israel|
|356|Индия|India|
|360|Индонезия|Indonesia|
|400|Иордания|Jordan|
|368|Ирак|Iraq|
|364|Иран|Iran (islamic republic of)|
|372|Ирландия|Ireland|
|352|Исландия|Iceland|
|724|Испания|Spain|
|380|Италия|Italy|
|887|Йемен|Yemen|
|132|Кабо-верде|Cabo verde|
|398|Казахстан|Kazakhstan|
|136|Каймановы острова|Cayman islands|
|116|Камбоджа|Cambodia|
|120|Камерун|Cameroon|
|124|Канада|Canada|
|634|Катар|Qatar|
|404|Кения|Kenya|
|196|Кипр|Cyprus|
|417|Киргизия|Kyrgyzstan|
|296|Кирибати|Kiribati|
|156|Китай|China|
|408|Кндр|Korea (democratic people's republic of)|
|166|Кокос острова|Cocos (keeling) islands|
|170|Колумбия|Colombia|
|174|Коморы|Comoros|
|410|Корея|Korea (republic of)|
|914|Косово|Kosovo|
|188|Коста-рика|Costa rica|
|384|Кот-д'ивуар|Cote d'ivoire|
|192|Куба|Cuba|
|414|Кувейт|Kuwait|
|184|Кука острова|Cook islands|
|531|Кюрасао|Curaçao|
|418|Лаос|Lao people's democratic republic|
|428|Латвия|Latvia|
|426|Лесото|Lesotho|
|430|Либерия|Liberia|
|422|Ливан|Lebanon|
|434|Ливия|Libya|
|440|Литва|Lithuania|
|438|Лихтенштейн|Liechtenstein|
|442|Люксембург|Luxembourg|
|480|Маврикий|Mauritius|
|478|Мавритания|Mauritania|
|450|Мадагаскар|Madagascar|
|175|Майотта|Mayotte|
|446|Макао|Macao|
|807|Македония|Macedonia (the former yugoslav republic of)|
|454|Малави|Malawi|
|458|Малайзия|Malaysia|
|466|Мали|Mali|
|581|Малые тихоокеанские отдаленные острова соединенных штатов|United states minor outlying islands|
|462|Мальдивы|Maldives|
|470|Мальта|Malta|
|504|Марокко|Morocco|
|474|Мартиника|Martinique|
|584|Маршалловы острова|Marshall islands|
|484|Мексика|Mexico|
|583|Микронезия|Micronesia (federated states of)|
|508|Мозамбик|Mozambique|
|498|Молдова, республика|Moldova (republic of)|
|492|Монако|Monaco|
|496|Монголия|Mongolia|
|500|Монтсеррат|Montserrat|
|104|Мьянма|Myanmar|
|833|Мэн остров|Isle of man|
|516|Намибия|Namibia|
|520|Науру|Nauru|
|524|Непал|Nepal|
|562|Нигер|Niger|
|566|Нигерия|Nigeria|
|530|Нидерландские антилы|Netherlands antilles|
|528|Нидерланды|Netherlands|
|558|Никарагуа|Nicaragua|
|570|Ниуэ|Niue|
|554|Новая зеландия|New zealand|
|540|Новая каледония|New caledonia|
|578|Норвегия|Norway|
|574|Норфолк остров|Norfolk island|
|784|Объединенные арабские эмираты|United arab emirates|
|512|Оман|Oman|
|586|Пакистан|Pakistan|
|585|Палау|Palau|
|275|Палестина|Palestine, state of|
|591|Панама|Panama|
|598|Папуа новая гвинея|Papua new guinea|
|600|Парагвай|Paraguay|
|604|Перу|Peru|
|612|Питкерн|Pitcairn|
|616|Польша|Poland|
|620|Португалия|Portugal|
|630|Пуэрто-рико|Puerto rico|
|178|Республика конго|Congo|
|638|Реюньон|Réunion|
|162|Рождества остров|Christmas island|
|643|Российская федерация|Russian federation|
|646|Руанда|Rwanda|
|642|Румыния|Romania|
|222|Сальвадор|El salvador|
|882|Самоа|Samoa|
|674|Сан-марино|San marino|
|678|Сан-томе и принсипи|Sao tome and principe|
|682|Саудовская аравия|Saudi arabia|
|748|Свазиленд|Swaziland|
|654|Святая елена, остров вознесения, тристан-да-кунья|Saint helena, ascension and tristan da cunha|
|580|Северные марианские острова|Northern mariana islands|
|690|Сейшелы|Seychelles|
|652|Сен-бартелеми|Saint barthelemy|
|686|Сенегал|Senegal|
|663|Сен-мартен (французская часть)|Saint martin (french part)|
|666|Сен-пьер и микелон|Saint pierre and miquelon|
|670|Сент-винсент и гренадины|Saint vincent and the grenadines|
|659|Сент-киттс и невис|Saint kitts and nevis|
|662|Сент-люсия|Saint lucia|
|534|Сент-мартен (нидерландская часть)|Sint maarten (dutch part)|
|688|Сербия|Serbia|
|702|Сингапур|Singapore|
|760|Сирийская арабская республика|Syrian arab republic|
|703|Словакия|Slovakia|
|705|Словения|Slovenia|
|840|Соединенные штаты америки|United states of america|
|90|Соломоновы острова|Solomon islands|
|706|Сомали|Somalia|
|729|Судан|Sudan|
|740|Суринам|Suriname|
|694|Сьерра-леоне|Sierra leone|
|762|Таджикистан|Tajikistan|
|764|Таиланд|Thailand|
|158|Тайвань|Taiwan (province of china)|
|834|Танзания|Tanzania, united republic of|
|796|Теркс и кайкос острова|Turks and caicos islands|
|626|Тимор-лесте|Timor-leste|
|768|Того|Togo|
|772|Токелау|Tokelau|
|776|Тонга|Tonga|
|780|Тринидад и тобаго|Trinidad and tobago|
|798|Тувалу|Tuvalu|
|788|Тунис|Tunisia|
|795|Туркмения|Turkmenistan|
|792|Турция|Turkey|
|800|Уганда|Uganda|
|860|Узбекистан|Uzbekistan|
|804|Украина|Ukraine|
|876|Уоллeс и футуна острова|Wallis and futuna|
|858|Уругвай|Uruguay|
|234|Фарерские острова|Faroe islands|
|242|Фиджи|Fiji|
|608|Филиппины|Philippines|
|246|Финляндия|Finland|
|238|Фолклендские (мальвинские) острова|Falkland islands (malvinas)|
|250|Франция|France|
|249|Франция, метрополия|France, metropolitan|
|254|Французская гвиана|French guiana|
|258|Французская полинезия|French polynesia|
|260|Французские южные территории|French southern territories|
|334|Херд и макдональд острова|Heard island and mcdonald islands|
|191|Хорватия|Croatia|
|140|Центрально-африканская республика|Central african republic|
|148|Чад|Chad|
|499|Черногория|Montenegro|
|203|Чешская республика|Czechia|
|152|Чили|Chile|
|903|Чили, пасхи остров|Chile, easter island|
|756|Швейцария|Switzerland|
|752|Швеция|Sweden|
|744|Шпицберген и ян-майен|Svalbard and jan mayen|
|144|Шри ланка|Sri lanka|
|218|Эквадор|Ecuador|
|226|Экваториальная гвинея|Equatorial guinea|
|248|Эландские острова|Aland islands|
|232|Эритрея|Eritrea|
|233|Эстония|Estonia|
|231|Эфиопия|Ethiopia|
|239|Южная джорджия и южные сандвичевы острова|South georgia and the south sandwich islands|
|896|Южная осетия|South ossetia|
|710|Южно-африканская республика|South africa|
|728|Южный судан|South sudan|
|388|Ямайка|Jamaica|
|392|Япония|Japan|

### Список валют

|Код|Наименование|
|---|---|
|AUD|Австралийский доллар|
|AZN|Азербайджанский манат|
|ALL|Албанский лек|
|DZD|Алжирский динар|
|GBP|Английский фунт стерлингов|
|AOA|Ангольская кванза|
|ARS|Аргентинский песо|
|AMD|Армянский драм|
|AFN|Афганский афгани|
|BDT|Бангладешский так|
|BHD|Бахрейнский динар|
|BYN|Беларусский рубль|
|BGN|Болгарский лев|
|VEF|Боливар|
|BOB|Боливийский боливиано|
|BWP|Ботсванская пула|
|BRL|Бразильский реал|
|BND|Брунейский доллар|
|BIF|Бурундийский франк|
|HUF|Венгерский форинт|
|KPW|Вона кндр|
|KRW|Вон республики корея|
|VND|Вьетнамский донг|
|GMD|Гамбийский даласи|
|GNF|Гвинейский франк|
|HKD|Гонконгский доллар|
|GEL|Грузинский лари|
|DKK|Датская крона|
|DJF|Джибутийский франк|
|AED|Дирхам оаэ|
|NAD|Доллар намибии|
|USD|Доллар сша|
|EUR|Евро|
|EGP|Египетский фунт|
|ZMK|Замбийская квача|
|ILS|Израильский новый шекель|
|INR|Индийская рупия|
|IDR|Индонезийская рупия|
|JOD|Иорданский динар|
|IQD|Иракский динар|
|IRR|Иранский риал|
|ISK|Исландская крона|
|YER|Йеменский риал|
|KZT|Казахский тенге|
|KHR|Камбоджийский риель|
|CAD|Канадский доллар|
|QAR|Катарский риал|
|KES|Кенийский шилинг|
|KGS|Киргизский сом|
|CNY|Китайский юань|
|COP|Колумбийский песо|
|CDF|Конголезский франк|
|CRC|Костариканский колон|
|CUP|Кубинский песо|
|KWD|Кувейтский динар|
|LAK|Лаосский кип|
|SLL|Леоне сьерра-леоне|
|LBP|Ливанский фунт|
|LYD|Ливийский динар|
|MUR|Маврикийская рупия|
|MRO|Мавританская угия|
|MKD|Македонский динар|
|MWK|Малавийская квача|
|MGA|Малагасийский ариари|
|MYR|Малайзийский ринггит|
|MAD|Марокканский дирхам|
|MXN|Мексиканский песо|
|MZN|Мозамбикский метикал|
|MDL|Молдавский лей|
|MNT|Монгольский тугрик|
|NPR|Непальская рупия|
|NGN|Нигерийская найра|
|NIO|Никарагуанская золотая кордоба|
|TRY|Новая турецкая лира|
|NZD|Новозеландский доллар|
|RON|Новый румынский лей|
|TMT|Новый туркменский манат|
|NOK|Норвежская крона|
|OMR|Оманский риал|
|PKR|Пакистанская рупия|
|PYG|Парагвайский гуарани|
|PEN|Перуанская новая соль|
|PLN|Польский злотый|
|SAR|Риял саудовской аравии|
|RUB|Рубль российский|
|SZL|Свазиледский лилангени|
|SCR|Сейшельская рупия|
|RSD|Сербский динар|
|SGD|Сингапурский доллар|
|SYP|Сирийский фунт|
|SOS|Сомалийский шиллинг|
|XDR|Спз|
|SDG|Суданский динар|
|SRD|Суринамский доллар|
|TJS|Таджикский сомони|
|THB|Таиландский бат|
|TWD|Тайваньский новый доллар|
|TZS|Танзанийский шиллинг|
|TND|Тунисский динар|
|UGX|Угандийский шиллинг|
|UZS|Узбекский сум|
|UAH|Украинская гривна|
|UYU|Уругвайский песо|
|PHP|Филиппинский песо|
|XAF|Франк кфа|
|XOF|Франков кфа всеао|
|HRK|Хорватская куна|
|CZK|Чешская крона|
|CLP|Чилийский песо|
|SEK|Шведская крона|
|CHF|Швейцарский франк|
|LKR|Шри-ланкийская рупия|
|ETB|Эфиопский быр|
|ZAR|Южноафриканский рэнд|
|JPY|Японская йена|

### Статусы заявки на вызов курьера

|Значение|Описание|
|---|---|
|NOT_REQUIRED|Заявка на вызов курьера не требуется|
|AVAILABLE|Разрешена подача заявки на вызов курьера|
|REFUSED_BY_USER|Пользователь отказался от подачи заявки на вызок курьера|
|ORDER_IN_PROGRESS|Заявка на вызов курьера в процессе|
|ORDER_REJECTED|Заявка на вызов курьера отклонена на стороне КЦ|
|ATTEMPT_FAILED|Попытка отправки не удалась|
|ORDER_COMPLETED|Заявка завершена|
|MANUAL_DELIVERY|Самостоятельная доставка|

### Статус отправления по гиперлокальной доставке

|Значение|Описание|
|---|---|
|UNDEFINED|Неизвестный статус по заявке|
|CREATED|Заявка создана|
|DATA_VERIFIED|Заявка передана|
|DATA_VERIFICATION_FAILED|Ошибка при проверке заявки|
|CLIENT_VERIFIED|Клиент подтвержден|
|ROUTE_ASSIGNED|Назначен маршрут|
|COURIER_APPOINTED|Курьер назначен|
|CONTRACTOR_SELECTED|Курьер назначен|
|CONTRACTOR_SELECTION_FAILED|Не удалось подобрать курьера|
|PASSED_FOR_EXECUTION|Заявка передана в исполнение|
|ACCEPTED_FOR_EXECUTION_BY_CONTRACTOR|Заявка передана в исполнение|
|COURIER_ARRIVED_AT_PICKUP_PLACE|Курьер прибыл в место сбора|
|CARGO_PICKED_UP|Курьер забрал груз|
|COURIER_ARRIVED_AT_DELIVERY_PLACE|Курьер прибыл в место доставки|
|IN_FINAL_STAGES_OF_EXECUTION|Курьер прибыл в место доставки|
|CARGO_DELIVERED|Груз доставлен|
|COMPLETED|Заявка исполнена|
|CARGO_RETURN_INITIATED|Возврат заказа|
|CARGO_LOST_BY_CONTRACTOR|Исполнитель потерял отправление|
|CANCELLED_BY_CONTRACTOR|Заявка отменена курьером|
|CANCELLED_BY_CLIENT|Заявка отменена клиентом|
|CARGO_RETURNED|Груз возвращён на место возврата|
|PROCESSING_FAILED|Ошибка при исполнении заявки|

### Статус партии по гиперлокальной доставке

|Значение|Описание|
|---|---|
|BATCH_PROCESSING|Заявки в обработке|
|BATCH_EXECUTE|Заявки исполнены|
|BATCH_NOT_EXECUTE|Заявки не исполнены|
|BATCH_EXECUTE_PARTIALLY|Заявки исполнены частично|

### Коды видов сервиса, используемого для отправлений

|Значение|Описание|
|---|---|
|WITHOUT_SERVICE|Без сервиса|
|WITHOUT_OPENING|Без вскрытия|
|CONTENTS_CHECKING|С проверкой вложения|
|WITH_FITTING|С примеркой|
|COURIER_DELIVERY|Доставка курьером|
|PARTIAL_REDEMPTION|С частичным выкупом|
|FUNCTIONALITY_CHECKING|С проверкой работоспособности|

### Тип пункта выдачи

|Значение|Описание|
|---|---|
|DELIVERY_POINT|ПВЗ - пункт выдачи заказов|
|PICKUP_POINT|АПС - автоматизированная почтовая станция (почтамат)|

### Типоразмер

|Значение|Описание|
|---|---|
|S|до 260х170х80 мм|
|M|до 300х200х150 мм|
|L|до 400х270х180 мм|
|XL|530х260х220 мм|
|OVERSIZED|Негабарит (сумма сторон не более 1200 мм, сторона не более 600 мм)|

## Ошибки

### BatchError

| **Контекст** | **Метод**                                                    |
| ------------ | ------------------------------------------------------------ |
| BatchError   | POST /1.0/user/shipment  <br>POST /1.0/batch/{name}/shipment |

|Значение|Описание|
|---|---|
|ALL_SHIPMENTS_SENT|Все отправления уже отправлены|
|BARCODE_ERROR|Ошибка при получении ШПИ|
|EMPTY_ADDRESS_TYPE_TO|Тип адреса не указан|
|EMPTY_INDEX_TO|Почтовый индекс не указан|
|EMPTY_INSR_VALUE|Объявленная сумма не указана|
|EMPTY_MAIL_CATEGORY|Категория почтового отправления не указана|
|EMPTY_MAIL_DIRECT|Почтовое направление не указано|
|EMPTY_MAIL_TYPE|Тип почтового отправления не указан|
|EMPTY_MASS|Масса не указана|
|EMPTY_NUM_ADDRESS_TYPE|Не задан номер для соответствующего типа адреса|
|EMPTY_PAYMENT|Наложенный платеж не указан|
|EMPTY_PLACE_TO|Населенный пункт не указан|
|EMPTY_REGION_TO|Регион не заполнен|
|EMPTY_TRANSPORT_TYPE|Способ пересылки не указан|
|EMPTY_POSTOFFICE_CODE|Индекс приемного почтового отделения не задан|
|ILLEGAL_ADDRESS_TYPE_TO|Тип адреса некорректен|
|ILLEGAL_INDEX_TO|Почтовый индекс некорректен|
|ILLEGAL_INITIALS|ФИО некорректно|
|ILLEGAL_INSR_VALUE|Объявленная сумма некорректна|
|ILLEGAL_MAIL_CATEGORY|Категория почтового отправления некорректна|
|ILLEGAL_MAIL_DIRECT|Почтовое направление некорректно|
|ILLEGAL_MAIL_TYPE|Тип почтового отправления некорректен|
|ILLEGAL_MASS|Масса некорректна|
|ILLEGAL_MASS_EXCESS|Вес отправления не должен превышать N кг|
|ILLEGAL_PAYMENT|Наложенный платеж некорректен|
|ILLEGAL_POSTCODE|Индекс приемного почтового отделения в настройках и в партии отличаются|
|ILLEGAL_POSTOFFICE_CODE|Индекс приемного почтового отделения некорректен|
|ILLEGAL_TRANSPORT_TYPE|Некорректный способ пересылки|
|IMP13N_ERROR|Ошибка имперсонализации|
|INSR_VALUE_EXCEEDS_MAX|Превышено максимальное значение N руб|
|NO_AVAILABLE_POSTOFFICES|Нет доступных точек сдачи|
|NOT_FOUND|Отправление не найдено|
|PAST_DUE_DATE|Дата отправки просрочена|
|READONLY_STATE|Изменения в партии недопустимы|
|RESTRICTED_MAIL_CATEGORY|Для создания отправлений с наложенным платежом необходимо указать номер ЕСПП в настройках сервиса. Обратитесь к вашему персональному менеджеру в Почте или напишите письмо на почтовый ящик [support.otpravka@russianpost.ru](mailto:support.otpravka@russianpost.ru)|
|SENDING_MAIL_FAILED|Ошибка при отправке почты|
|TARIFF_ERROR|Ошибка при расчете тарифа|
|DIFFERENT_TRANSPORT_TYPE|Способы пересылки отправления и партии отличаются|
|DIFFERENT_MAIL_TYPE|Типы почтовых отправлений не совпадают|
|DIFFERENT_MAIL_CATEGORY|Категории почтовых отправления не совпадают|
|DIFFERENT_MAIL_RANK|Разряд отправления и партии отличаются|
|ABSENT_OVERSIZE_POSTMARK|Отправление не может быть добавлено в партию с отметкой "Негабаритная"|
|UNEXPECTED_OVERSIZE_POSTMARK|Негабаритное отправление не может быть добавлено в партию без отметки "Негабаритная"|
|ABSENT_FRAGILE_POSTMARK|Отправление без отметки "Осторожно/Хрупкое/Терморежим" не может быть добавлено в партию с отметкой "Осторожно/Хрупкое/Терморежим"|
|UNEXPECTED_FRAGILE_POSTMARK|Отправление с отметкой "Осторожно/Хрупкое/Терморежим" не может быть добавлено в партию без отметки "Осторожно/Хрупкое/Терморежим"|
|ABSENT_COURIER_DELIVERY_POSTMARK|Отправление без отметки "Курьер" не может быть добавлено в партию с отметкой "Курьер"|
|UNEXPECTED_COURIER_DELIVERY_POSTMARK|Отправление с отметкой "Курьер" не может быть добавлено в партию без отметки "Курьер"|
|ABSENT_ORDER_OF_NOTICE_POSTMARK|Отправление без отметки "С заказным уведомлением" не может быть добавлено в партию с отметкой "С заказным уведомлением"|
|UNEXPECTED_ORDER_OF_NOTICE_POSTMARK|Отправление с отметкой "С заказным уведомлением" не может быть добавлено в партию без отметки "С заказным уведомлением"|
|ABSENT_SIMPLE_NOTICE_POSTMARK|Отправление без отметки "С простым уведомлением" не может быть добавлено в партию с отметкой "С простым уведомлением"|
|UNEXPECTED_SIMPLE_NOTICE_POSTMARK|Отправление с отметкой "С простым уведомлением" не может быть добавлено в партию без отметки "С простым уведомлением"|
|UNDEFINED|Неопределенная ошибка|

### RemoveBacklogErrorCode  

| **Контекст**           | **Метод**           |
| ---------------------- | ------------------- |
| RemoveBacklogErrorCode | DELETE /1.0/backlog |

|Значение|Описание|
|---|---|
|UNDEFINED|Неопределенная ошибка|
|ACCESS_VIOLATION|Нарушение доступа|
|NOT_FOUND|Не найден|

### RemoveFromBatchErrorCode

| **Контекст**             | **Метод**                                        |
| ------------------------ | ------------------------------------------------ |
| RemoveFromBatchErrorCode | POST /1.0/user/backlog  <br>DELETE /1.0/shipment |

|Значение|Описание|
|---|---|
|UNDEFINED|Неопределенная ошибка|
|DELIVERY_IN_PROGRESS|Отправление уже отправлено|
|ACCESS_VIOLATION|Нарушение доступа|
|NOT_FOUND|Не найден|
|READONLY_STATE|Изменения в партии недопустимы|

### OrderValidationError

| **Контекст**         | **Метод**                                                                            |
| -------------------- | ------------------------------------------------------------------------------------ |
| OrderValidationError | PUT /1.0/user/backlog  <br>PUT /1.0/backlog/{id}  <br>PUT /1.0/batch/{name}/shipment |

|Значение|Описание|
|---|---|
|EMPTY_MAIL_CATEGORY|Категория почтового отправления не указана|
|ILLEGAL_MAIL_CATEGORY|Категория "%s" не поддерживается для данного типа отправления|
|RESTRICTED_MAIL_CATEGORY|Для создания отправлений с наложенным платежом необходимо указать номер ЕСПП в настройках сервиса. Обратитесь к вашему персональному менеджеру в Почте или напишите письмо на почтовый ящик [support.otpravka@russianpost.ru](mailto:support.otpravka@russianpost.ru)|
|EMPTY_MAIL_TYPE|Тип почтового отправления не указан|
|ILLEGAL_MAIL_TYPE|Тип почтового отправления некорректен|
|EMPTY_ADDRESS_TYPE_TO|Тип адреса не указан|
|ILLEGAL_ADDRESS_TYPE_TO|Тип адреса некорректен|
|EMPTY_MAIL_DIRECT|Почтовое направление не указано|
|ILLEGAL_MAIL_DIRECT|Почтовое направление некорректно|
|ILLEGAL_INDEX_TO|Почтовый индекс некорректен|
|EMPTY_INDEX_TO|Почтовый индекс не указан|
|EMPTY_REGION_TO|Регион не заполнен|
|EMPTY_PLACE_TO|Населенный пункт не указан|
|EMPTY_TELADDRESS|Телефон получателя является обязательным для данного вида отправления|
|EMPTY_INSR_VALUE|Объявленная сумма не указана|
|ILLEGAL_INSR_VALUE|Объявленная сумма некорректна|
|INSR_VALUE_EXCEEDS_MAX|Превышено максимальное значение %s руб|
|EMPTY_PAYMENT|Наложенный платеж не указан|
|ILLEGAL_PAYMENT|Наложенный платеж некорректен|
|NOT_INSURED_PAYMENT|Наложенный платеж превышает объявленную ценность|
|EMPTY_TRANSPORT_TYPE|Способ пересылки не указан|
|ILLEGAL_TRANSPORT_TYPE|Cервис пока не поддерживает расчёт стоимости доставки в этот регион|
|EMPTY_MASS|Масса не указана|
|ILLEGAL_MASS|Масса некорректна|
|ILLEGAL_MASS_EXCESS|Превышение максимальной массы|
|TARIFF_ERROR|Ошибка тарификации(дополнительно поле см. Details)|
|IMP13N_ERROR|Ошибка имперсонализации|
|ILLEGAL_INITIALS|ФИО некорректно|
|EMPTY_NUM_ADDRESS_TYPE|Не задан номер для соответствующего типа адреса|
|BARCODE_ERROR|Ошибка при получении ШПИ|
|DIFFERENT_POSTCODE|Способы пересылки отправления и партии отличаются|
|EMPTY_POSTOFFICE_CODE|Индекс отделения места приема не задан|
|ILLEGAL_POSTOFFICE_CODE|Индекс приемного почтового отделения некорректен|
|NO_AVAILABLE_POSTOFFICES|Нет доступных отделений места приема|
|ILLEGAL_FRAGILE|Отметка "Осторожно/Хрупкое/Терморежим" неприменима для указанного типа отправлений|
|EMPTY_MAIL_RANK|Код разряда почтового отправления не задан в настройках пользователя|
|EMPTY_ENVELOPE_TYPE|Не задан тип конверта|
|ILLEGAL_ENVELOPE_TYPE|Недопустимое значение "Тип конверта" для выбранного вида отправления|
|ILLEGAL_ADDRESS_LETTER|Недопустимое значение "Литера"|
|ILLEGAL_ADDRESS_SLASH|Недопустимое значение "Дробь"|
|ILLEGAL_ADDRESS_CORPUS|Недопустимое значение "Корпус"|
|ILLEGAL_ADDRESS_BUILDING|Недопустимое значение "Строение"|
|ILLEGAL_ADDRESS_ROOM|Недопустимое значение "Комната"|
|EMPTY_PAYMENT_METHOD|Способ оплаты не задан|
|ILLEGAL_PAYMENT_METHOD|Некорректный способ оплаты|
|ABSENT_OVERSIZE_POSTMARK|Отправление не может быть добавлено в партию с отметкой "Негабаритная"|
|UNEXPECTED_OVERSIZE_POSTMARK|Негабаритное отправление не может быть добавлено в партию без отметки "Негабаритная"|
|ABSENT_COURIER_DELIVERY_POSTMARK|Отправление без отметки "Курьер" не может быть добавлено в партию с отметкой "Курьер"|
|UNEXPECTED_COURIER_DELIVERY_POSTMARK|Отправление с отметкой "Курьер" не может быть добавлено в партию без отметки "Курьер"|
|ABSENT_FRAGILE_POSTMARK|Отправление без отметки "Осторожно/Хрупкое/Терморежим" не может быть добавлено в партию с отметкой "Осторожно/Хрупкое/Терморежим"|
|UNEXPECTED_FRAGILE_POSTMARK|Отправление с отметкой "Осторожно/Хрупкое/Терморежим" не может быть добавлено в партию без отметки "Осторожно/Хрупкое/Терморежим"|
|ABSENT_ORDER_OF_NOTICE_POSTMARK|Отправление без отметки "С заказным уведомлением" не может быть добавлено в партию с отметкой "С заказным уведомлением"|
|UNEXPECTED_ORDER_OF_NOTICE_POSTMARK|Отправление с отметкой "С заказным уведомлением" не может быть добавлено в партию без отметки "С заказным уведомлением"|
|ABSENT_SIMPLE_NOTICE_POSTMARK|Отправление без отметки "С простым уведомлением" не может быть добавлено в партию с отметкой "С простым уведомлением"|
|UNEXPECTED_SIMPLE_NOTICE_POSTMARK|Отправление с отметкой "С простым уведомлением" не может быть добавлено в партию без отметки "С простым уведомлением"|
|UNDEFINED|Неопределенная ошибка|

### TariffErrorCode

| **Контекст**    | **Метод**        |
| --------------- | ---------------- |
| TariffErrorCode | POST /1.0/tariff |

|Значение|Описание|
|---|---|
|UNDEFINED|Ошибка при расчете тарифа|
|CODE_1372|Доставка по указанному маршруту не осуществляется|

## Состояния

### Тип последней операции из трекинга

|Значение|Описание|
|---|---|
|UNKNOWN|Тип операции не определен|
|ACCEPTING|Прием|
|GIVING|Вручение|
|RETURNING|Возврат|
|DELIVERING|Досылка почты|
|SKIPPING|Невручение|
|STORING|Хранение|
|HOLDING|Временное хранение|
|PROCESSING|Обработка|
|IMPORTING|Импорт международной почты|
|EXPORTING|Экспорт международной почты|
|CUSTOM_ACCEPTING|Принято таможней|
|TRYING|Неудачная попытка вручения|
|REGISTERING|Регистрация отправки|
|CUSTOM_LEGALIZING|Таможенное оформление|
|DELIGATING|Передача на временное хранение|
|DESTROYING|Уничтожение|
|ACCOUNTING|Передача вложения на баланс|
|LOSS_REGISTRATION|Регистрация утраты|
|CUSTOM_DUTY_RECEIVING|Таможенные платежи поступили|
|DM_REGISTRATION|Регистрация|
|DM_DELIVERING|Доставка|
|DM_NON_DELIVERING|Недоставка|
|TEMPORARY_STORING_ARRIVING|Поступление на временное хранение|
|PROLONGATION_CUSTOM_STORING|Продление срока выпуска таможней|
|OPENING|Вскрытие|
|CANCELLATION|Отмена|
|ID_ASSIGNMENT|Присвоен идентификатор|

### Атрибут последней операции из трекинга

|Значение|Описание|
|---|---|
|UNKNOWN|Неизвестный атрибут|
|FOREIGN_ACCEPTING|Приём|
|SINGLE_ACCEPTING|Принято в отделении связи|
|PARTIAL_ACCEPTING|Принято в отделении связи|
|PARTIAL_ACCEPTING_REMOTE|Электронное письмо принято|
|GIVING_RECIPIENT|Получено адресатом|
|GIVING_SENDER|Получено отправителем|
|GIVING_RECIPIENT_IN_PO|Получено адресатом|
|GIVING_SENDER_IN_PO|Получено отправителем|
|GIVING_COMMON|Получено|
|GIVING_RECIPIENT_REMOTE|Электронное письмо вручено|
|GIVING_RECIPIENT_POSTMAN|Получено адресатом|
|GIVING_SENDER_POSTMAN|Получено отправителем|
|GIVING_RECIPIENT_COURIER|Получено адресатом|
|GIVING_RECIPIENT_CONTROL|Получено адресатом|
|GIVING_RECIPIENT_CONTROL_POSTMAN|Получено адресатом|
|GIVING_RECIPIENT_CONTROL_COURIER|Получено адресатом|
|GIVING_SENDER_COURIER|Получено отправителем|
|RETURNING_BY_EXPIRED_STORING|Отправлено обратно отправителю|
|RETURNING_BY_SENDER_REQUEST|Отправлено обратно отправителю|
|RETURNING_BY_RECEPIENT_ABSENCE|Отправлено обратно отправителю|
|RETURNING_BY_RECEPIENT_REJECT|Отправлено обратно отправителю|
|RETURNING_BY_RECEPIENT_DEATH|Отправлено обратно отправителю|
|RETURNING_BY_UNREADABLE_ADDRESS|Отправлено обратно отправителю|
|RETURNING_BY_CUSTOM|Отправлено обратно отправителю|
|RETURNING_BY_UNKNOWN_RECEPIENT|Отправлено обратно отправителю|
|RETURNING_BY_OTHER_REASONS|Отправлено обратно отправителю|
|RETURNING_BY_WRONG_ADRESS|Отправлено обратно отправителю|
|DELIVERING_BY_CLIENT_REQUEST|Перенаправлено на другой адрес|
|DELIVERING_TO_NEW_ADDRESS|Перенаправлено на новый адрес|
|DELIVERING_BY_ROUTER|Перенаправлено на новый адрес|
|LOST|Вручение не состоялось|
|CONFISCATED|Вручение не состоялось|
|SKIPPING_BY_ERROR|Вручение не состоялось|
|SKIPPING_BY_CUSTOM|Вручение не состоялось|
|UNDELIVERED|Вручение не состоялось|
|POSTE_RESTANTE_STORING|Хранение|
|STORING_IN_BOX|Хранение|
|TEMPORAL_STORING|Хранение|
|ADDITIONAL_STORING|Хранение|
|CUSTOM_HOLDING|Передано в таможенный орган|
|CUSTOM_DUTY_RECEIVED|Таможенные платежи поступили|
|UNASSIGNED|Временное хранение|
|UNCLAIMED|Временное хранение|
|PROHIBITED|Временное хранение|
|SORTING|Сортировка|
|SENT|Покинуло место приема|
|ARRIVED|Прибыло в место вручения|
|DELIVERED_TO_SORTING|Прибыло в сортировочный центр|
|SORTED|Покинуло сортировочный центр|
|DELIVERED_TO_EXCHANGE_HUB|Прибыло в место международного обмена|
|PROCESSED_BY_EXCHANGE_HUB|Покинуло место международного обмена|
|DELIVERED_TO_HUB|Прибыло в место транзита|
|LEAVED_HUB|Покинуло место транзита|
|DELIVERED_TO_PO|Ожидает адресата в почтомате|
|DELIVERED_HYBRID|Прибыло в центр гибридной печати|
|EXPIRED_PO_STORING|Истекает срок хранения в почтомате|
|FORWARDED|Переадресовано в почтомат|
|GET|Изъято из почтомата|
|ARRIVED_IN_RUSSIA|Прибыло на территорию России|
|ARRIVED_IN_PARCELS_CENTER|Ожидает адресата в месте вручения|
|GIVEN_TO_COURIER|Передано курьеру|
|GIVEN_FOR_REMOTE|Электронное письмо доставлено|
|GIVEN_FOR_BOXROOM|Передано в кладовую хранения|
|GIVEN_TO_POSTMAN|Передано почтальону|
|COURIER_ORDERED|Передано курьеру|
|IMPORTED|Прошло регистрацию|
|EXPORTED|Пересекло границу|
|ACCEPTED_BY_CUSTOM|Принято таможней|
|FAILED_BY_TEMPORAL_ABSENCE_OF_RECEPIENT|Неудачная попытка вручения|
|FAILED_BY_DELAYING_REQUEST|Неудачная попытка вручения|
|FAILED_BY_UNFILLED_ADDRESS|Неудачная попытка вручения|
|FAILED_BY_INVALID_ADDRESS|Неудачная попытка вручения|
|FAILED_BY_RECEPIENT_LEAVING|Неудачная попытка вручения|
|FAILED_BY_RECEPINT_REJECT|Неудачная попытка вручения|
|UNOVERCAMING_FAIL|Неудачная попытка вручения|
|FAILED_WITH_ANOTHER_REASON|Неудачная попытка вручения|
|WAITING_RECEPIENT_IN_OFFICE|Неудачная попытка вручения|
|RECEPIENT_NOT_FOUND|Неудачная попытка вручения|
|TECHNICALLY_FAILED|Неудачная попытка вручения|
|FAILED_BY_EXPIRATION_TIME|Неудачная попытка вручения|
|REGISTERED|Регистрация отправки|
|LEGALIZED|Выпущено таможней|
|CUSTOM_LEGALIZED|Выпущено таможней|
|CANCELED_LEGLIZATION|Возвращено таможней|
|PROCESSED_BY_CUSTOM|Осмотрено таможней|
|REJECTED_BY_CUSTOM|Отказано таможней в выпуске|
|PASSED_WITH_CUSTOM_NOTIFY|Направлено с таможенным уведомлением|
|PASSED_WITH_CUSTOM_TAX|Направлено с обязательной уплатой таможенных платежей|
|DELIGATED|Передано на временное хранение|
|DESTROYED|Уничтожение|
|ACCOUNTED|Передано в собственность почты|
|LOSS_REGISTERED|Зарегистрирована утрата|
|DM_REGISTERED|Регистрация|
|DM_DELIVERED|Доставка|
|DM_ABSENCE_POSTBOX|Недоставка|
|Т|Недоставка|
|DM_WRONG_POSTOFFICE_INDEX|Недоставка|
|DM_WRONG_ADDRESS|Недоставка|
|OPENED|Срок хранения истек, отправление вскрыто|
|CANCELED_BY_SENDER_DEFAULT|Предыдущая операция отменена по требованию отправителя|
|CANCELED_BY_SENDER|Предыдущая операция отменена по требованию отправителя|
|CANCELED_BY_OPERATOR|Предыдущая операция отменена|
|ID_ASSIGNED|Зарегистрировано, еще не отправлено|
