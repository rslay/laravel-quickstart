# Laravel Homestead Quickstart

A base project that uses Laravel 8 with Passport for use as an API backend!

**[View the API documentation Online!](https://documenter.getpostman.com/view/13272092/TVejiW5G)**

Using Laravel Homestead in Vagrant, this entire configuration is straightforward.

If you already have an environment set up - either with Docker, natively on your local machine, or on a remote machine - **then you don't need to use this entire repo, only the Laravel folder!**

> **NOTE:** This is for development environments only!
>
> Only the Laravel folder in this directory contains the project code. Deploy that to production, not everything in this repo.

## Setup

### Step 1 - Vagrant Setup

(_Skip this step if you already have an environment to run Laravel in_)

- Install [Vagrant](https://www.vagrantup.com/downloads)
    - _On Linux_, use your package manager, e.g. `sudo apt install virtualbox`
- Run `vagrant box add laravel/homestead`
- Clone this repo into a desired folder (`git clone https://github.com/rslay/laravel-quickstart`)
- Copy `Homestead.yaml.example` to `Homestead.yaml`, then in that new file...
- Modify `C:\Users\<ENTER USERNAME HERE>\Desktop\homestead\laravel` to match the path to the `laravel` folder on your machine
    - Change `\` to `/` if you are on Linux and use the appropriate path instead (use the `pwd` command while in the `laravel-quickstart` folder)
- Run `vagrant up && vagrant ssh` while in the `homestead` folder (**IMPORTANT:** must be done in a terminal run **as Administrator on Windows**)

If all went well, you should now be in a terminal in the VM running Homestead. Go to step 2 below and enter the commands.

### Step 2 - Laravel Setup

Lets configure the local environment:

```bash
cd /home/vagrant/code
composer install
yarn
yarn add vue-template-compiler
cp .env.example .env
php artisan key:generate
php artisan passport:install --force
php artisan migrate
```

Open up the Laravel folder in your favorite IDE and get started coding!

You can open a shell to the machine with `vagrant ssh` anytime.

> **NOTE:** Do not use `npm`! Run `yarn run watch` instead, `npm` currently has an issue in vagrant with shared folders.

## Features

### Includes Example Passport User Notification REST API

The example routes, controllers, and models form an API with:

- **Users** (register/login to get an access token, view info)
- **Notifications** (CRUD)
- **Yelp External API** (external server request on behalf of user)
  - To set this up, you must set the value `YELP_API_KEY` in your `.env` with the value from the [Yelp Manage App](https://www.yelp.com/developers/v3/manage_app) page, which is free and only requires you make an account.

#### [View the API documentation Online!](https://documenter.getpostman.com/view/13272092/TVejiW5G)

The importable **Postman JSON collection contains 12 different requests, [click here to download the Postman file](laravel/postman.json)**. It is present at the base of the laravel project folder.

You can import this to your postman by going to **Import** at the top left of the Postman desktop app.

### User Seeder/Faker

Create as many test users as needed, like so:

```bash
vagrant@homestead:~$ cd code
vagrant@homestead:~/code$ php artisan tinker
>>> User::factory()->count(100)->create()
```

> **NOTE:** All the user passwords are "password".

You will see all the newly created user rows.

You can try out these user accounts by logging into them, using the `/api/user/login` endpoint. Refer to the previous REST API section for more details.
