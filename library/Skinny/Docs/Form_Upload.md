# \Skinny\Form\Upload #

## Usage ##

```php
// Setup the class:
$class = new Upload();

// Specify field name to receive from the form
$class->setFormFieldName('upload_file');

// Set the folder to store the upload
$class->setTargetFolder("/tmp");

// Set an array of \Skinny\Validate\* to validate against
$class->setValidators(
    array(
        new \Skinny\Validate\File\Image()
    )
);

// Received?
if (!$class->receive()) {
    // No
    print_r($class->getError());
} else {
    // Yes it was! success
    print_r($class->getFileLocation());
    print_r($class->getFileName());
}
```
