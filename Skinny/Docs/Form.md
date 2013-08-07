# \Skinny\Form #

## Usage ##

```php
// Setup the class:
$form = new \Skinny\Form();

// Set required field (Validates NotEmpty)
$form->addElement('personName', true);

// Set additional validators
$form->addElement('emailAddress', true, array(new \Skinny\Validate\EmailAddress()));

// Check the post is valid
$form->isValid($request->getPost());

```
