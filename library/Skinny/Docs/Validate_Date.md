# \Skinny\Validate\Date #

## Usage ##

```php
// Retrieving an instance, giving a format
$validator = new \Skinny\Validate\Date('Y/m/d');

// Checking
if ($validator->isValid($userInputDate)) {
    // Valid
} else {
    // Not Valid
}
```
