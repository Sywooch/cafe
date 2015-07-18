<?php

return [
    // точка реализации
    'pos_id' => 'Код',
    'pos_title' => 'Название точки реализации',
    'pos_address' => 'Адрес',
    'pos_timetable' => 'График работы',
    //
    // товар
    'product_id' => 'Код',
    'product_title' => 'Название товара',
    'product_icon' => 'Иконка товара',
    'product_quantity' => 'Количество',
    'product_unit' => 'Единица измерения',
    'product_min_quantity' => 'Минимальное количество',
    'product_unit_price' => 'Цена за единицу',
    //
    // пользователь системы
    'Sysusers'=>'Пользователи',
    'sysuser_id' => '№',
    'sysuser_fullname' => 'Фамилия, имя, отчество',
    'sysuser_login' => 'Имя пользователя',
    'sysuser_password' => 'Пароль',
    'sysuser_role' => 'Роль',
    'sysuser_telephone' => 'Телефон',
    'sysuser_token' => 'Секретный ключ для входа',
    'admin'=>'Администратор',
    'seller'=>'Продавец',
    'Create Sysuser'=>'Создать пользователя',
    'Update Sysuser:'=>'Изменить пользователя',
    'Sysuser'=>'Пользователь',
    'sysuser_password1'=>'Пароль',
    'sysuser_password2'=>'Повторите пароль',
    'Type_password_to_update'=>'Наберите пароль, чтобы изменить его',
    'sysuser_active'=>'Разрешить вход',
    
    'Create'=>'Создать',
    'Update'=>'Изменить',
    'Are you sure you want to delete this item?'=>"Вы действительно желаете удалить эту запись?",
    'Delete'=>'Удалить',
    
    'Pos'=>'Точка реализации',
    'Seller Salary'=>'Оклад',
    'Seller Commission Fee'=>'Комиссионные,%',
    'seller_id'=>'№',
    'Sellers'=>'Продавцы',
    'Create Seller'=>'Добавить продавца',
    'Pos-list'=>'Точки реализации',
    'Create Pos'=>'Добавить точку реализации',
    'Update Pos: '=>'Изменить точку реализации',
    'Sysuser-list'=>'Пользователи',
    'Logout ({login})'=>'Выход ({login})',
    'Login'=>'Войти',
    'Update Seller: '=>'Изменить продавца: ',
    'Products'=>'Товары',
    'Create Product'=>'Добавить товар',
    'Update Product: '=>'Изменить товар: ',
    
    'Packaging'=>'Фасовка',
    'Packagings'=>'Фасовки',
    'Create Packaging'=>'Создать фасовку',
    'Packaging ID'=>'Код',
    'Packaging Icon'=>'Иконка',
    'Packaging Title'=>'Название',
    'Packaging Price'=>'Цена',
    'packaging_icon'=>'Иконка',
    'Update Packaging: '=>'Изменить фасовку: ',
    
    'Products in packaging'=>'Состав',
    'Product'=>'Товар',
    'Quantity'=>'Количество',
    'Price'=>'Цена',
    'Select products ...'=>'Выберите товары',
    'Add product to packaging'=>'Добавить в состав',
    'Product quantity must be a number'=>'Количество должно быть числом, например 7.12 или 2',
    'Select a product'=>'Выберите товар',
    'Packaging Category'=>'Категория',
    'Categories'=>'Категории',
    'Create Category'=>'Создать категорию',
    'Category ID'=>'№',
    'Category Title'=>'Название',
    'Packaging is additional'=>'Дополнительная',
    'yes'=>'Да',
    'no'=>'Нет',
    'Pos-product-list {pos_title}'=>'Запасы в точке реализации "{pos_title}"',
    'Pos-product-list'=>'Запасы',
    'pos_product_quantity'=>'Количество',
    'pos_product_min_quantity'=>'Мин.количество',
    'Supply-needed'=>'Нужна поставка',
    'Update Category: '=>'Изменить категорию',
    'Pos-product-supply'=>'План поставки',
    'Pos-product-supply {pos_title}'=>'План поставки: {pos_title}',
    'Product quantity'=>'Товара на складе',
    'Product quantity available'=>'Доступно на складе',
    'Supplied quantity'=>'Будет поставлено',
    'Supply.pos_product_quantity'=>'Кол-во в точке',
    'Supply.pos_product_min_quantity'=>'Мин.кол-во в точке',
    'Pos-product-supply-print {pos_title}'=>'Печать поставок: {pos_title}',
    'Pos-product-supply-print'=>'Печать поставок',
    'Category Skin'=>'Тема оформления',
    
    'Order ID'=>'Код',
    'Pos ID'=>'Точка реализации',
    'Seller ID'=>'Продавец',
    'Sysuser ID'=>'Пользователь',
    'Order Datetime'=>'Дата и время заказа',
    'Order Day Sequence Number'=>'Номер',
    'Order Total'=>'Сумма',
    'Order Discount'=>'Скидка',
    'Order Payment Type'=>'Способ оплаты',
    'Order Hash'=>'Контрольная сумма',
    'Orders'=>'Заказы',
    'Order {id}'=>'Заказ {id}',
    'Order Items'=>'Состав заказа',
    'Posselector'=>'Выбор точки реализации',
    'Sell'=>'Продажа',
    'Discounts'=>'Скидки',
    
    'Discount ID'=>'Код скидки',
    'Discount Title'=>'Название скидки',
    'Discount Description'=>'Примечания',
    'Discount Rule'=>'Настройка',
    'Discount Auto'=>'Предлагать автоматически',
    'Discount Type'=>'Тип скидки',
    'Discount Type Simple'=>'Простая формула',
    'Create Discount'=>'Создать скидку',
    'Update Discount: '=>'Изменить скидку: ',
    'Duplicate Order'=>'Заказ можно добавить только один раз',
    'Processing the order'=>'Обработка заказа',
    'Category Ordering'=>'Номер по порядку',
    'Seller Commission'=>'Комиссионные',
    'has_pos_position'=>'Имеет рабочее место',
    'assign'=>'Назначить',
    'Order Total Min'=>'Сумма от',
    'Order Total Max'=>'Сумма до',
    'Order Datetime Min'=>'Дата от',
    'Order Datetime Max'=>'Дата до',
    'find'=>'Найти',
    'foundOrdersTotal'=>'Выручка <b>{order_total_sum} {currency}</b>.',
    'foundOrdersCount'=>'Всего заказов <b>{order_num}</b>.',
    'foundOrdersAvg'=>'Средний чек <b>{order_total_avg} {currency}</b>.',
    'today'=>'Сегодня',
    'yesterday'=>'Вчера',
    'thisweek'=>'Эта неделя',
    'lastweek'=>'Прошлая неделя',
    'thismonth'=>'Этот месяц',
    'lastmonth'=>'Прошлый месяц',
    'Order Datetime Set'=>'Выбрать период',
    'pos_printer_url'=>'Адрес принтера(URL)',
    'pos_printer_template'=>'Шаблон чека',
    'Order report'=>'Отчёт о заказах',
    'Order report flter'=>'Настройки',
    'Reports'=>'Отчёты',
    'Choose report type'=>'Отчёты',
    'Seller Comission'=>'Комиссионные',
    'Order Count'=>'Заказов',
    'Order Average'=>'Средняя сумма',
    'Packaging ordering'=>'Номер по порядку',
    'Packaging is visible'=>'Разрешить продажу',
    'All POSs'=>'Все точки продаж',
    'All sellers'=>'Все продавцы',
    'Pos-packaging {pos_title}'=>'Цены в точке "{pos_title}"',
    'packaging_id'=>'Код',
    'Pos Packaging Price'=>'Цена в точке',
    'rememberMe'=>'Запомнить меня',
    'Login'=>'Войти на сайт',
    'Pos-packaging-prices'=>'Цены в точке реализации',
    'Personnel'=>'Сотрудники',
    'no_condition'=>'<!-- -->',//Нет условия',
    'order_total'=>'Сумма заказа',
    'packaging_title'=>'Название фасовки',
    'packaging_price'=>'Цена фасовки',
    'condition_attribute'=>'Если',
    'contains'=>'содержит',
    'search_attribute'=>'то найти атрибут',
    'exists'=>'существует',
    'discount_value'=>'и применить к нему скидку',
    'search_attribute_condition'=>"который ",
    'paytype'=>'Спос.оплаты',
    'ProductReport'=>'Расход товара',
    'total_packaging_product_quantity'=>'Расход',
    'PackagingReport'=>'Популярность фасовок',
    'packaging_number'=>'Продано, шт',
    'PosIncomeReport'=>'Выручка по точкам реализации',
    'totalIncome'=>'Выручка',
    'SellerIncomeReport'=>'Выручка по продавцам',
    'HourlyIncomeReport'=>'Выручка и количество заказов по часам',
    'Hour'=>'Час',
    'WeekdailyIncomeReport'=>'Выручка и прибыль по дням недели',
    'DailyIncomeReport'=>'Выручка и прибыль по дням',
    
    'Sun'=>'Вс',
    'Mon'=>'Пн',
    'Tue'=>'Вт',
    'Wed'=>'Ср',
    'Thu'=>"Чт",
    'Fri'=>"Пт",
    'Sat'=>"Сб",
    'Income'=>'Выручка',
    'Profit'=>'Прибыль',
    'Return payment'=>'Возврат сегодняшнего заказа',
    'Return'=>'Вернуть',
    'Find order'=>'Найти заказ',
    'OrderDaySequenceNumber'=>'Номер заказа',
    'Choose packaging to return'=>'Выберите, что именно возвращает покупатель',
    'items'=>'шт.',
    'order_notes'=>'Примечания',
    'There is not POS attached to seller'=>'Продавцу не назначена точка реализации.',
    'n_orders'=>'Заказов',
    'Reorder Packagings'=>'Упорядочить фасовки',
    'Drag to Reorder Packagings'=>'Перетаскивайте строки, чтобы упорядочить фасовки',
    'Filter'=>'Поиск по названию',
    'All categories'=>'Все категории',
    'Category Icon'=>'Иконка категории',
    'category_icon_file'=>'Иконка категории',
    'category_icon_delete'=>'Удалить иконку',
    'pos_packaging_visible'=>'Показывать',
    'Similar reports'=>'Похожие отчёты',
    'Time interval'=>'Интервал времени',
    'pos_worktime_start'=>'Время начала работы',
    'pos_worktime_finish'=>'Время окончания работы',
    'Seller Wage'=>'Почасовая оплата ('.\Yii::$app->params['currency'].')',
    'seller_worktime_start'=>'Время начала работы ( если по индивидуальному графику )',
    'seller_worktime_finish'=>'Время окончания работы ( если по индивидуальному графику )',
    'pos_sellers'=>'Продавцы (время, действие, имя)',
    'date_format'=>'d.m.Y H:i:s',
    'Workingtime'=>'Время работы, ч',
    'Wage'=>'Почасовая оплата',
    'Customers'=>'Клиенты',
    'Customer ID'=>'Код',
    'Customer Mobile'=>'Мобильный телефон',
    'Customer Name'=>'Имя клиента',
    'Customer Notes'=>'Примечания',
    'customerId'=>'Клиент',
    'CustomerIncomeReport'=>'Клиенты',
    'OneCustomerReport'=>'Информация о клиенте',
    
    'Subsystems'=>'Другие системы',
    'Subsystem ID'=>'Код',
    'Subsystem Title'=>'Название',
    'Subsystem Url'=>'URL',
    'Subsystem Api Key'=>'Секретный ключ',
    'More'=>'Ещё',
    'Create Subsystem'=>'Подключиться к другой системе',
    'Update Subsystem:'=>'Изменить подключение к системе',
    'Subsystem_reports'=>'Отчёты из другой системы',
    'Orderreport'=>'Заказы',
    'Pages'=>'Страницы',
    'Sellerreport'=>'Продавцы',
    'Customers found'=>'Найдено клиентов',
    'Packagingreport'=>'Популярность фасовок',
    'Orderview'=>'Заказ',
    'datetime_format'=>'d.m.Y H:i:s',
    
    'Discount Type Simple'=>'Правило',
    'Discount Type Nth'=>'Каждый n-й бесплатно',
    'eachNth_period'=>'Каждая ',
    'eachNth_category'=>'Из категории ',
    'Discounted items available'=>'Доступно {discounts_available} шт. в категории {category_title}'
];
