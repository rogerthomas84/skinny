# \Skinny\Auth #

## Usage ##


### Logging in a user ###

```php
// Retrieve your login form data.
$params = $_POST;

// Validate the login
if($params['user'] == 'joe' && $params['password'] == 'bloggs') {
    // Ok to log in.
    
    // Retrieve instance of \Skinny\Auth
    $auth = Skinny\Auth::getInstance();

    // Record the login
    $auth->login(
        array(
            'username' => $params['user']
            // You could store more information here.
        )
    );
    // Set the user roles in object (optional)
    $auth->setRoles(
        array(
            'user',
            'administrator',
        )
    );
    
    // User is now logged in.

}

```

### Validating a users authentication status ###

```php
// Retrieve an instance of \Skinny\Auth
$auth = Skinny\Auth::getInstance();

if ($auth->isLoggedIn()) {
    // User is logged in.
    
    // Retrieve the users information
    $userDetails = $auth->getIdentity();
    
    echo $userDetails['username'];
    // joe
    
} else {
    // User is not logged in.
}

```
