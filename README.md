Book Review Application
========================

Developed using the PHP Symfony Framework, this application allows a user to register, add, review and rate books. An API has been included for use by external third-party developers. The FOSUserBundle, FOSRestBundle, FOSOAuthServerBundle, JMSSerializerBundle and BazingaHateoasBundle packages were used extensively.  

- Composer is required to install the vendor assets
>``
composer install
``  
>``
composer update
``

- Install Assetic Assets
>``
php app/console assetic:dump
``  

- Apply Schema to Database
>``
php app/console doctrine:schema:update --force
``  

- Run Application
>``
php app/console server:run
``  