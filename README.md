[![Build Status](https://travis-ci.org/rogerthomas84/skinny.png)](http://travis-ci.org/rogerthomas84/skinny)

Skinny PHP Library
==================

Skinny provides a slim library for common PHP applications.

It's become more mature recently, and is used in some rather large applications.

# Core Functionality #


## Routing ##

### `\Skinny\Router` ###
A basic, yet effective router for applications.


## Authentication ##

### `\Skinny\Auth` ###
Extending the basic functionality of `\Skinny\Storage` this provides a basic method of
storing and retrieving a users authentication status. Calling `login()` and `logout()` provides
the core of the class

### `\Skinny\Auth\Service` ###
By extending `\Skinny\Auth\AdapterAbstract` you can easily create simplified authentication for your
applications.


## Form ##

### `\Skinny\Form` ###
This class provides a simple way of performing form validation at the controller level, without the
bloat of a full form building library.

### `\Skinny\Form\Upload` ###
Make uploads a breeze by using this simple method to upload files to a given folder


## Session & Storage ##

### `\Skinny\Session` ###
Controls the basic Session functionality that's needed for applications of any size. 
The primary goal of this class is to provide a simplistic interface to interact with session data.

### `\Skinny\Storage` ###
Provides a Session Namespace approach to storing data for a users session.


## Validators ##

### `\Skinny\Validate\Date` ###
Validates a date is valid according to a format

### `\Skinny\Validate\EmailAddress` ###
Validates an email address is a valid format

### `\Skinny\Validate\File\Image` ###
Validates a given file location is an image

### `\Skinny\Validate\NotEmpty` ###
Validates a given value isn't empty

### `\Skinny\Validate\StringLength` ###
Validates a given string is a set value, or between set values

### `\Skinny\Validate\TwoKeysAreEqual` ###
Validates a given string is the same as another field (when used as part of `\Skinny\Form`)

### `\Skinny\Validate\AlphaNumeric` ###
Validates whether a string is alphanumeric, and optionally you can specify whether to allow spaces.


## Cache ##

### `\Skinny\Cache\MemcacheService` ###
Provides a better way to interact with Memcache


## Filters ##

### `\Skinny\Filter\HtmlEntities` ###
This class provides a simple way of using HTML Entities (Originally from Zend Framework)

### `\Skinny\Filter\ImageSize` ###
This class extends the basic functionality to interact and manipulate an image size.
