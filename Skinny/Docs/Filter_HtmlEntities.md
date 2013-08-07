# \Skinny\Filter\HtmlEntities #

## Usage ##

### Filtering A Value ###

```php
// Use a value
$userInput = 'Some form "input"';

// Retrieving an instance
$filter = new \Skinny\Filter\HtmlEntities();

$finalValue = $cleanser->filter($userInput);
```
