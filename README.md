# Laravel test helpers

## Checking Authorization

You can check authorization with the ChecksAuthorization Trait

```
    use ChecksAuthorization;

    ...

    $this->assertRequestDenies(new Request($get, $post));
    $this->assertRequestGrants(new Request($get, $post));
```


## User mocking

You can fake a currently authenticated usser and set its Rights:

```
public $fakeUserModel = \App\User::class;

...

$this->mockUser(array $rights);
```

$rights is an array with all the right keys of this user.
You should set the fakeUserModel on the test class to resolve the user (which is \App\User by default)
