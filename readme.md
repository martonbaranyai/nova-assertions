# Nova Assertions

Work in progress. Not functional yet.

### Installation

```
composer require dillingham/nova-assertions
```
```php
use NovaTesting\NovaAssertions;

class UserTest extends TestCase
{
    use NovaAssertions;

    public function testNova()
    {
        $this->be(factory(User::class)->create());

        $this->novaIndex('users');
            ->assertFieldExists('email');
            ->assertCanUpdate()
            ->assertCannotDelete();
    }
}
```

### Authentication
Log in a user that **[has access to Nova](https://nova.laravel.com/docs/2.0/installation.html#authorizing-nova)**
```php
$this->be(factory(User::class)->create());
```

### Requests

Request Nova's results with one of the following:

| Method | Description |
| - | - |
| ->novaIndex($resource) | todo |
| ->novaDetail($resource, $id) | todo |
| ->novaCreate($resource) | todo |
| ->novaEdit($resource, $id) | todo |

# Assert Http
You can call other **[http response methods](https://laravel.com/docs/5.8/http-tests#available-assertions)** also:

```php
$this->novaIndex('users')
    ->assertOk();
    ->assertUnauthorized();
    ->assertStatus(200);
    ->assertSessionHas()
    ->assertJson([
        //
    ]);
```

# Assert Fields

Assert columns or form fields with the following:

#### assertFieldExists
todo
```php
$response->assertFieldExists();
```
#### assertFieldMissing
todo
```php
$response->assertFieldMissing();
```
#### assertFieldEquals
todo
```php
$response->assertFieldEquals();
```
#### assertFieldDoesntEquals
todo
```php
$response->assertFieldDoesntEquals();
```

# Assert Authorization

The following assert against the auth user & **[Nova's use of policies](https://nova.laravel.com/docs/2.0/resources/authorization.html#authorization)**

```php
$response->assertCanDelete();
```
```php
$response->assertCannotDelete();
```
```php
$response->assertCanForceDelete();
```
```php
$response->assertCannotForceDelete();
```
```php
$response->assertCanRestore();
```
```php
$response->assertCannotRestore();
```
```php
$response->assertCanUpdate();
```
```php
$response->assertCannotUpdate();
```
```php
$response->assertCanView();
```
```php
$response->assertCannotView();
```