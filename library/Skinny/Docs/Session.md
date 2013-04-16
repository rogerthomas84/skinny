# \Skinny\Session #

## Usage ##

```php
// Retrieving an instance
$session = \Skinny\Session::getInstance();

// Setting a value in the session
$session->set("name", "Joe Bloggs");

// Removing a single value from the session based on the key
$session->get("name");

// Removing a single key from the session
$session->remove("name");

// Removing all keys from the session
$session->removeAll();

// Destroying the session entirely (Optionally regenerate the session_id)
$session->destroy($regenerate = false);

```
