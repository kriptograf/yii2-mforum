yii2 forum
==========
forum module extension for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist kriptograf/yii2-mforum "*"
```

or add

```
"kriptograf/yii2-mforum": "*"
```

to the require section of your `composer.json` file.

Configure
---------

Add following lines to your main configuration file:

```php
'modules' => [
    ...
    'forum' => [
        'class' => 'kriptograf\mforum\Module',
        ]
    ]
    ... 
 ]
 
```

Update database schema
----------------------

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@vendor/kriptograf/mforum/migrations
```


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= Url::toRoute(['/forum']); ?>```