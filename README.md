# CRUD Articles with Comments, Notifications and FOSUserBundle

This project is a SPA where you can interactive with other users and create articles for reflect your opinion or any stuff

### Introduction

I tried to show my knowledge with `Symfony`. I share my code to the people who wants to learn, compare and consult my code.

```
If you see any error, please add comment in the code
```
### bookstores and framework that I use in the project

I have used Symfony v3.0.* with PHP v5.0.9, Doctrine, Twig, Bootstrap, Jquery and whit this bundle: FOSUserBundle.

`you can find more information about this bundle here` [FOSUserBundle](http://symfony.com/doc/current/bundles/FOSUserBundle/index.html)

### How Install this project in your computer
Clone this git repository with this comand
 
 `git clone https://github.com/VGamezz19/VicFace-Symfony.git`

After that, check if you have install `composer` in your computer. If you don't have it, please install it 

`you can find information about  how to install composer in this link` [Composer](https://getcomposer.org/download/)

When you are alredy install composer, and repository are cloned in your computer. Open Terminal and put this comand inside the project

`composer install`


When composer is finished, it will ask you for your BBDD.
 
 foto BBDD
 
 If you unknow this information, you can insert information in project

`/app/config/parameters.yml`


foto parameters

By last, put in your Terminal this comand -->
`php bin/console doctrine:schema:update --force`


