Skinny PHP Library
==================

Skinny provides a slim library for common PHP applications.

This is currently a work in progress, but gradually there will be different classes and documentation added.

# Classes #

## Routing ##

### `/Skinny/Router` ###
This class provides a basic, yet effective router for applications. See the documentation for details.
* [Read More](library/Skinny/Docs/Router.md)

## Authentication ##

### `/Skinny/Auth` ###
This class extends the basic functionality of the `\Skinny\Storage` to provide a basic method of
storing and retrieving a users authentication status. Calling `login()` and `logout()` provides
the core of the class
* [Read More](library/Skinny/Docs/Auth.md)

## Form Validation ##

### `/Skinny/Form` ###
This class provides a simple way of performing form validation at the controller level
* [Read More](library/Skinny/Docs/Form.md)

### `/Skinny/Form/Upload` ###
This class provides a simple method to upload files to a given folder on upload from a form.
* [Read More](library/Skinny/Docs/Form_Upload.md)

## Session Management ##

### `/Skinny/Session` ###
Controls the basic Session functionality that's needed for applications of any size. 
The primary goal of this class is to provide a simplistic interface to interact with session data.
* [Read More](library/Skinny/Docs/Session.md)

### `/Skinny/Storage` ###
Provides a Session Namespace approach to storing data for a users session.
* [Read More](library/Skinny/Docs/Storage.md)

## Validators ##

### `/Skinny/Validate/Date` ###
Validates a date is valid according to a format
* [Read More](library/Skinny/Docs/Validate_Date.md)

### `/Skinny/Validate/EmailAddress` ###
Validates an email address is a valid format
* [Read More](library/Skinny/Docs/Validate_EmailAddress.md)

### `/Skinny/Validate/File/Image` ###
Validates a given file location is an image
* [Read More](library/Skinny/Docs/Validate_File_Image.md)

### `/Skinny/Validate/NotEmpty` ###
Validates a given value isn't empty
* [Read More](library/Skinny/Docs/Validate_NotEmpty.md)

## Filters ##

### `/Skinny/Filter/HtmlEntities` ###
This class provides a simple way of using HTML Entities (Originally from Zend Framework)
* [Read More](library/Skinny/Docs/Filter_HtmlEntities.md)

### `/Skinny/Filter/ImageSize` ###
This class extends the basic functionality to interact and manipulate an image size.
* [Read More](library/Skinny/Docs/Filter_ImageSize.md)
