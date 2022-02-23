Introduction
============

**Blog** -- Allows you to manage your various posts and view other different posts status. You can also "like" or "dislike" posts and manage various comments per post.

Features
------------------------------
**The following features that exist in the Blog application:**

**Managing Post**

- Create a Post
- Edit a Post
- View a Post
- List Post
- Delete a Post

**Managing Comment**

- Create a Comment
- Edit a Comment
- View a Comment
- List Comment
- Delete a Comment

Installation
------------
The way to install Blog.

####Using The Command Line:

**Github**

- Fork the repository ([here is the guide](https://help.github.com/articles/fork-a-repo/)).
- Clone to your machine 
```
git clone https://YOUR_USERNAME/blog-vuejs.git
```

Browser Support
---------------
- IE 9+
- Firefox (latest)
- Chrome (latest)
- Safari (latest)
- Opera (latest)


Plugins
---------

Packages
---------------
- [Laravel Auditing](https://packagist.org/packages/owen-it/laravel-auditing) : run composer require owen-it/laravel-auditing
- [Laravel Sentry](https://packagist.org/packages/sentry/sentry-laravel) : run composer require sentry/sentry-laravel
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) : run composer require barryvdh/laravel-debugbar --dev
- [Unique Token Generator For Laravel](https://packagist.org/packages/dirape/token) : run composer require dirape/token
- [A Simple GUID creator package for PHP](https://github.com/sudiptpa/guid) : run composer require sudiptpa/guid
- [IP to Location](https://github.com/sudiptpa/ipstack) : run composer require sudiptpa/ipstack

Change log
----------


Installation
-------------
- Run: npm install & npm run dev
- Run: migrate
- Run: php artisan jwt:secret
- Run: php artisan db:seed
- Run: ./vendor/bin/phpunit --testdox tests

Guideline Tests
----------

- Get PHPUnit version: ./vendor/bin/phpunit --version
- Run tests: ./vendor/bin/phpunit or php artisan test
- Run document tests: ./vendor/bin/phpunit --testdox tests
- Run specific method of file test: ./vendor/bin/phpunit --filter {TestMethodName} {FilePath}
- For test single **CLASS**:
    - ./vendor/bin/phpunit --filter  tests/Feature/PostTest.php
    - ./vendor/bin/phpunit --filter 'Tests\\Feature\\PostTest'
    - ./vendor/bin/phpunit --filter 'PostTest' 
- For test single **METHOD**:
    - ./vendor/bin/phpunit --filter testExample 
    - ./vendor/bin/phpunit --filter 'Tests\\Feature\\PostTest::testExample'
    - ./vendor/bin/phpunit --filter testExample PostTest tests/Feature/PostTest.php
- For run tests from **ALL** class within **NAMESPACE**:
    - ./vendor/bin/phpunit --filter 'Tests\\Feature'
- For more test info ([phpunit](https://phpunit.readthedocs.io/en/7.3/textui.html#textui-examples-testcaseclass-php))

Tests
----------

- Run and update the following test cases to ensure the requirements are achieved:
    - sudo ./vendor/bin/phpunit --testdox  tests/Feature/ExternalAPITest.php
