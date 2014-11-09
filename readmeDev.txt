
roles: admin, seller

http://www.yiiframework.com/doc-2.0/guide-README.html

http://www.yiiframework.com/doc-2.0/yii-helpers-html.html

http://www.yiiframework.com/doc-2.0/yii-db-activerecord.html

http://www.yiiframework.com/doc-2.0/yii-db-activequeryinterface.html

http://www.yiiframework.com/doc-2.0/guide-db-dao.html

http://www.yiiframework.com/doc-2.0/yii-grid-gridview.html

http://www.yiiframework.com/doc-2.0/yii-web-view.html

http://demos.krajee.com/widget-details/select2

http://demos.krajee.com/widget-details/fileinput

http://demos.krajee.com/widget-details/active-form


// =================================================================================================
// URLs
use yii\helpers\Url;
echo Url::to(''); // текущий URL
echo Url::toRoute(['view', 'id' => 'contact']); // тот же контроллер, другой экшн
echo Url::toRoute('post/index'); // тот же модуль, другие контроллер и экшн
echo Url::toRoute('/site/index'); // абсолютный роут вне зависимости от текущего контроллера
echo Url::toRoute('hi-tech'); // URL для экшна в с регистрозависимым именем `actionHiTech` текущего контроллера
echo Url::toRoute(['/date-time/fast-forward', 'id' => 105]); // URL для регистрозависимых экшна и контроллера `DateTimeController::actionFastForward`
echo Url::to('@web'); // получаем URL из алиаса
echo Url::canonical(); // получаем canonical URL для текущей страницы
echo Url::home(); // получаем домашний URL
Url::remember(); // сохраняем URL для последующего использования
Url::previous(); // получаем ранее сохранённый URL



   <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'packaging_title')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'packaging_price')->textInput() ?>
    
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'category_id', 'category_title'),[]) ?>
    
    <?= $form->field($model, 'packaging_is_additional')->checkbox() ?>




//
order_id                   bigint(20)   (NULL)           NO      PRI     (NULL)   auto_increment  select,insert,update,references           
pos_id                     bigint(20)   (NULL)           NO      MUL     (NULL)                   select,insert,update,references           
seller_id                  bigint(20)   (NULL)           NO      MUL     (NULL)                   select,insert,update,references           
sysuser_id                 bigint(20)   (NULL)           NO      MUL     (NULL)                   select,insert,update,references           
order_datetime             datetime     (NULL)           YES     MUL     (NULL)                   select,insert,update,references           
order_day_sequence_number  int(11)      (NULL)           YES             (NULL)                   select,insert,update,references           
order_total                double       (NULL)           YES             (NULL)                   select,insert,update,references           
order_discount             double       (NULL)           YES             (NULL)                   select,insert,update,references           
order_payment_type         varchar(32)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
order_hash                 varchar(64)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           

//
order_id                bigint(20)   (NULL)           NO      PRI     (NULL)           select,insert,update,references           
packaging_id            bigint(20)   (NULL)           NO      PRI     (NULL)           select,insert,update,references           
packaging_title         varchar(32)  utf8_general_ci  YES             (NULL)           select,insert,update,references           
packaging_price         double       (NULL)           YES             (NULL)           select,insert,update,references           
order_packaging_number  int(11)      (NULL)           YES             (NULL)           select,insert,update,references           



// поставки
pos_id           bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
product_id       bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
supply_quantity  double      (NULL)     YES             (NULL)           select,insert,update,references           


// категория фасовки
+ тема категории

// запасы товаров в точке реализации
pos_id                    bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
product_id                bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
pos_product_quantity      double      (NULL)     NO              (NULL)           select,insert,update,references           
pos_product_min_quantity  double      (NULL)     NO              (NULL)           select,insert,update,references           

// состав фасовки
packaging_id                bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
product_id                  bigint(20)  (NULL)     NO      PRI     (NULL)           select,insert,update,references           
packaging_product_quantity  double      (NULL)     YES             (NULL)           select,insert,update,references           

// фасовка
packaging_id     bigint(20)     (NULL)           NO      PRI     (NULL)   auto_increment  select,insert,update,references           
packaging_icon   varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
packaging_title  varchar(32)    utf8_general_ci  YES             (NULL)                   select,insert,update,references           
packaging_price  double         (NULL)           YES             (NULL)                   select,insert,update,references           

// точка реализации
pos_id         bigint(20)     (NULL)           NO      PRI     (NULL)   auto_increment  select,insert,update,references           
pos_title      varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
pos_address    varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
pos_timetable  varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           

// товар 
product_id            bigint(20)     (NULL)           NO      PRI     (NULL)   auto_increment  select,insert,update,references           
product_title         varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
product_icon          varchar(1024)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
product_quantity      double         (NULL)           YES             (NULL)                   select,insert,update,references           
product_unit          varchar(32)    utf8_general_ci  YES             (NULL)                   select,insert,update,references           
product_min_quantity  double         (NULL)           YES             (NULL)                   select,insert,update,references           
product_unit_price    double         (NULL)           YES             (NULL)                   select,insert,update,references           

// продавец
seller_id              bigint(20)  (NULL)     NO      PRI     (NULL)   auto_increment  select,insert,update,references           
sysuser_id             bigint(20)  (NULL)     YES     MUL     (NULL)                   select,insert,update,references           
pos_id                 bigint(20)  (NULL)     YES     MUL     (NULL)                   select,insert,update,references           
seller_salary          double      (NULL)     YES             (NULL)                   select,insert,update,references           
seller_commission_fee  double      (NULL)     YES             (NULL)                   select,insert,update,references           

// пользователь
sysuser_id         bigint(20)    (NULL)           NO      PRI     (NULL)   auto_increment  select,insert,update,references           
sysuser_fullname   varchar(512)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
sysuser_login      varchar(64)   utf8_general_ci  YES             (NULL)                   select,insert,update,references           
sysuser_password   varchar(128)  utf8_general_ci  YES             (NULL)                   select,insert,update,references           
sysuser_role_mask  int(11)       (NULL)           YES             (NULL)                   select,insert,update,references           
sysuser_telephone  varchar(64)   utf8_general_ci  YES             (NULL)                   select,insert,update,references           



> Количество товара списать правильно (если несколько одинаковіх порций)
+

> Стр Заказы.новые заказы сначала
+

> В списке пользователей символ "нет места работы"
+

> Стр продажа не прятать товар, а деактивировать

>"Экспрессо" - в начало

> Счётчик заказов на стартовую страницу


> отмена чека и возврат денег

