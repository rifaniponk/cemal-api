# Cemal - Checklist Amalan Harian

[![Build Status](https://api.travis-ci.org/rifaniponk/cemal-api.svg)](https://travis-ci.org/rifaniponk/cemal-api)
[![Codecov branch](https://img.shields.io/codecov/c/github/rifaniponk/cemal-api.svg?style=flat-square)](https://codecov.io/github/rifaniponk/cemal-api)
[![StyleCI](https://styleci.io/repos/103468432/shield?style=flat-square)](https://styleci.io/repos/103468432)
[![Code Coverage](https://scrutinizer-ci.com/g/rifaniponk/cemal-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rifaniponk/cemal-api/?branch=master)

Cemal adalah aplikasi untuk memudahkan seorang muslim mencatat amalan hariannya. 


## Server Requirement

* PHP >= 7.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* PostgreSQL 9.5.7

## Running Up Development Environment

* Install [virtualbox 5.2](https://www.virtualbox.org/wiki/Downloads)
* Install [vagrant 2.0](https://www.vagrantup.com/downloads.html)
* Clone cemal-api project
```bash
cd ~/code
git clone git@github.com:rifaniponk/cemal-api.git
```

* Setup Homestead
```bash
cd ~
git clone https://github.com/laravel/homestead.git Homestead
cd Homestead
init.bat
```

Edit Homestead.yml

```yaml
...
provider: virtualbox
...
folders:
    - map: ~/code
      to: /home/vagrant/code
```

*) you can change `~/code` into your root project folder

Run homestead

```bash
cd ~/Homestead
vagrant up
```

Edit hosts file:
linux: `/etc/hosts`,
windows: `C:\Windows\System32\driver\etc\hosts`.

Add this line into your hosts file

```
192.168.10.10 cemal.api
```

* Install Dependencies

Enter to vagrant box then run:
```sh
cd ~/vagrant/code/cemal-api
composer install
```

* Setup Application & Database
```sh
cp .env.example .env
php artisan migrate:refresh --seed
```

* try to open http://cemal.api/v1/whoami, if you see "status 401, Unathorized" then you're ready to rock!

## Running unit test

Enter to vagrant box, then run
```bash
cd ~/vagrant/code/cemal-api
phpunit
```

## API Documentation

api documentation is here http://cemal.api/api/documentation

if you had a new endpoint or changes, you need to run this to generate updated api documentation

```bash
php artisan swagger-lume:generate
``` 