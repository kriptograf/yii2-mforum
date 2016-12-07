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
    ],
    //depends extensions
    'markdown' => [
        'class' => 'kartik\markdown\Module',
    ],
    'attachments' => [
    	'class' => nemmo\attachments\Module::className(),
    	'tempPath' => '@app/uploads/temp',
    	'storePath' => '@app/uploads/store',
    	'rules' => [ // Rules according to the FileValidator
    		'maxFiles' => 10, // Allow to upload maximum 3 files, default to 3
    		'mimeTypes' => 'image/png', // Only png images
    		'maxSize' => 1024 * 1024 // 1 MB
    	],
    	'tableName' => '{{%attachments}}' // Optional, default to 'attach_file'
    ]
    ... 
 ]
 
```

Add sender information to common/params.php

```
'forumEmailSender'=>'info@yousite.com',
```

Update database schema
----------------------

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@vendor/kriptograf/yii2-mforum/migrations
$ php yii migrate/up --migrationPath=@vendor/nemmo/yii2-attachments/src/migrations
```


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= Url::toRoute(['/forum']); ?>```