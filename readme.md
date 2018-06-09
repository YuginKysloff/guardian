[![Build Status](https://travis-ci.org/rennokki/guardian.svg?branch=master)](https://travis-ci.org/rennokki/guardian)
[![Latest Stable Version](https://poser.pugx.org/rennokki/guardian/v/stable)](https://packagist.org/packages/rennokki/guardian)
[![Total Downloads](https://poser.pugx.org/rennokki/guardian/downloads)](https://packagist.org/packages/rennokki/guardian)
[![Monthly Downloads](https://poser.pugx.org/rennokki/guardian/d/monthly)](https://packagist.org/packages/rennokki/guardian)
[![License](https://poser.pugx.org/rennokki/guardian/license)](https://packagist.org/packages/rennokki/guardian)


# Eloquent Guardian
Eloquent Guardian is your big beast that prevents anyone from accessing restricted content. It is like a bodyGUARD(ian), but it's less harmful.

 Also, you don't need to pay, it will work for you for free.
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

You can allow, disallow, prohibit or unprohibit permissions.

Permissions can be:

* String Type:
```php
$user->allow('edit-posts');
$user->can('edit-posts');
```

* Global Type:
```php
$user->allow('edit', App\Post::class);
$user->can('edit', App\Post::class);
```

* Global Specific Type:
```php
$user->allow('edit', App\Post::class, 'post_id_here');
$user->can('edit', App\Post::class, 'post_id_here');
```

Note: The following methods accept 3 parameters, from which the first is mandatory and the following two are optional.

* `hasPermission()`
* `getPermission()`
* `can()`
* `cannot()`
* `cant()`
* `allow()`
* `disallow()`
* `deletePermission()`
* `prohibit()`
* `unprohibit()`

```php
$user = User::find(1);

// Relationships.
$user->permissions(); 
$user->allowedPermissions();
$user->prohibitedPermissions();

// Check if the user can.
$this->can('edit-articles');
$this->can('edit', App\Post::class);
$this->can('edit', App\Post::class, 'post_id_here');

// Check if the user cannot.
$this->cannot('edit-articles');
$this->cannot('edit', App\Post::class);
$this->cannot('edit', App\Post::class, 'post_id_here');

// An alias of the cannot() method.
$this->cant('edit-articles');
$this->cant('edit', App\Post::class);
$this->cant('edit', App\Post::class, 'post_id_here');

// Allow a permission.
// If there is an active record, it will automatically set its prohibited status to false.
// If you have prohibited the permission earlier and you then call allow(), then the can() will return true.
$this->allow('edit-articles');
$this->allow('edit', App\Post::class);
$this->allow('edit', App\Post::class, 'post_id_here');

// If you allow a permission on a global one.
$this->allow('edit', App\Post::class);

// When you will check against any id of the class, you will get true.
$this->can('edit', App\Post::class, 'post_id_here'); // true

// Disallow a permission. This will delete the permission.
// Use prohibit() to keep it but to not delete it.
// If the user does not have an active record of the permission, it will create the permission with is_prohibited to 1 and return it.
$this->disallow('edit-articles');
$this->disallow('edit', App\Post::class);
$this->disallow('edit', App\Post::class, 'post_id_here');

// This is an alias of the disallow() method.
$this->deletePermission('edit-articles');
$this->deletePermission('edit', App\Post::class);
$this->deletePermission('edit', App\Post::class, 'post_id_here');

// Use prohibit/unprohibit to keep it but not delete the record.
// Note: If the user does not have an active record of the permission, it will create one with is_prohibited to 1 and reurn it.
$this->prohibit('edit-articles');
$this->prohibit('edit', App\Post::class);
$this->prohibit('edit', App\Post::class, 'post_id_here');
$this->unprohibit('edit-articles');
$this->unprohibit('edit', App\Post::class);
$this->unprohibit('edit', App\Post::class, 'post_id_here');

```
