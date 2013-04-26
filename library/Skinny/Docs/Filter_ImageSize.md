# \Skinny\Filter\ImageSize #

## Usage ##

### Specifying Exact Dimensions ###

```php
// Retrieving an instance
$resize = new \Skinny\Filter\ImageSize('/my/old/file.jpg');

// Specify exact dimensions
$resize->toDimensions(300, 300);

// Save the file at a new location
$resize->setOutput('/my/new/file.jpg');
```

### Specifying Maximum Height (Scale Width) ###

```php
// Retrieving an instance
$resize = new \Skinny\Filter\ImageSize('/my/old/file.jpg');

// Specify target height
$resize->toHeight(300);

// Save the file at a new location
$resize->setOutput('/my/new/file.jpg');
```

### Specifying Maximum Width (Scale Height) ###

```php
// Retrieving an instance
$resize = new \Skinny\Filter\ImageSize('/my/old/file.jpg');

// Specify target width
$resize->toWidth(300);

// Save the file at a new location
$resize->setOutput('/my/new/file.jpg');
```

### Specifying Pecentage Size Scale ###

```php
// Retrieving an instance
$resize = new \Skinny\Filter\ImageSize('/my/old/file.jpg');

// Specify target percentagge size
$resize->toPecentage(50);

// Save the file at a new location
$resize->setOutput('/my/new/file.jpg');
```
