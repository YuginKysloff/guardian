[![Build Status](https://travis-ci.org/rennokki/guardian.svg?branch=master)](https://travis-ci.org/rennokki/guardian)
[![codecov](https://codecov.io/gh/rennokki/guardian/branch/master/graph/badge.svg)](https://codecov.io/gh/rennokki/guardian/branch/master)
[![StyleCI](https://github.styleci.io/repos/136514812/shield?branch=master)](https://github.styleci.io/repos/136514812)
[![Latest Stable Version](https://poser.pugx.org/rennokki/guardian/v/stable)](https://packagist.org/packages/rennokki/guardian)
[![Total Downloads](https://poser.pugx.org/rennokki/guardian/downloads)](https://packagist.org/packages/rennokki/guardian)
[![Monthly Downloads](https://poser.pugx.org/rennokki/guardian/d/monthly)](https://packagist.org/packages/rennokki/guardian)
[![License](https://poser.pugx.org/rennokki/guardian/license)](https://packagist.org/packages/rennokki/guardian)

[![PayPal](https://img.shields.io/badge/PayPal-donate-blue.svg)](https://paypal.me/rennokki)

# Eloquent Guardian
Eloquent Guardian is a simple permissions system for your users.

# Why using Guardian?
It's simple. It has to be simple. Don't bother using gates or anything of that complicate stuff. You can store permissions, you can track them and you can check your users when you need to: either it's from the model or within a middleware.

# Installation
Install the package:
```bash
$ composer require rennokki/guardian
```

If your Laravel version does not support package discovery, add this line in the `providers` array in your `config/app.php` file:
```php
Rennokki\Guardian\GuardianServiceProvider::class,
```

Publish the config file & migration files:
```bash
$ php artisan vendor:publish
```

Migrate the database:
```bash
$ php artisan migrate
```

Add the `HasPermissions` trait to your Eloquent model:
```php
use Rennokki\Guardian\Traits\HasPermissions;

class User extends Model {
    use HasPermissions;
    ...
}
```

# Types of permissions
* String Type is just a string, it's not related to any model. It is good for permissions that holds accessing abilities or features.
```php
$user->allow('access.dashboard');
```

* Global Type is related to a model, but not to a specific one. It can control any model with any ID if set.
```php
$user->allow('edit', Post::class);
```

* Global Specific Type is related to a specific model. It cannot control any other model than this specific one.
```php
$user->allow('edit', App\Post::class, 'post_id_here');
```

# Checking
You can check permissions within the model using `can()`, `cannot()` or `cant()`.
```php
$user->can('access.dashboard');
$user->cannot('sell.products');
$user->cant('sell.products'); // alias to cannot()
```

# Allowing and Unprohibiting permissions
Allowing or Unprohibiting produces a grant access to that permission.
```php
$user->allow('cloning');
$user->unprohibit('cloning'); // same thing
```

# Disallowing and Prohibiting permissions
Disallowing or Prohibiting permissions can be done whenever. The result will always be the same: a denied access.
```php
$user->disallow('commenting');
$user->prohibit('commenting'); // produces the same thing.
```

# Global Type over Specific Type
Let's say you have a `Post` class and the user is only allowed to edit or delete only his own posts. Using this way, whenever you check for a Global Type, it will return false, but not if you check for Specific Type.
```php
$user->allow('edit', Post::class, 'his_post_id');
$user->allow('delete', Post::class, 'his_post_id');

$user->can('edit', Post::class); // false
$user->can('edit', Post::class, 'his_post_id'); // true
```

Now let's say you have chat rooms. And you want to give an user the permission to see any chat room, but not a specific one.
```php
$user->allow('view', ChatRoom::class);
$user->disallow('view', ChatRoom::class, 'this_id_is_hidden');

$user->can('view', ChatRoom::class); // true
$user->cannot('view', ChatRoom::class, 'this_id_is_hidden'); // true
```
Make sure you check for Specific Types **before** the Global Types. Otherwise, you will give access to a hidden chat room that shouldn't be accessible for that user.

# Relationships
```php
$user->permissions();
$user->allowedPermissions();
$user->prohibitedPermissions();
```

# Middleware
You can use the methods within the model as-is, or you can use a middleware to filter permissions. For this, you should add the middleware to your `$routeMiddleware` array from `app\Http\Kernel.php`

```php
'guardian' => \Rennokki\Guardian\Middleware\CheckPermission::class,
```

After this, you can use it in your routes to filter permissions automatically and throw specific exceptions when something occurs.

* String Middleware
```php
Route::get('/admin', 'AdminController@ControlPanel')->middleware('guardian:access.adashboard');
```

* Global Type
```php
Route::post('/admin/products', 'AdminController@CreateProduct')->middleware('guardian:create,App\Product');
```

* Global Specific Type
```php
Route::patch('/admin/{post_id}', 'AdminController@EditPost')->middleware('guardian:edit,App\Post,post_id');
```

**Note: Instead of putting a specific Post ID, you have just to indicate where the ID of that model will be placed in the route URL.**

* `Rennokki\Guardian\Exceptions\PermissionException`, if the authenticated user doesn't have permissions.
* `Rennokki\Guardian\Exceptions\RouteException`, if the passed route parameter is non-existent.

You can access `permission()`, `modelType()` and `modelIdPlaceholder()` methods within the exception to handle your exception further, at this point.
