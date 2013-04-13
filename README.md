Skinny PHP Library
==================

Skinny provides a slim library for common PHP applications.

This is currently a work in progress, but gradually there will be different classes and documentation added.

# Class Overview #

## Skinny Session Management ###

### `/Skinny/Session` ###
Controls the basic Session functionality that's needed for applications of any size. 
The primary goal of this class is to provide a simplistic interface to interact with session data.


### `/Skinny/Storage` ###
Provides a Session Namespace approach to storing data for a users session.


### `/Skinny/Auth` ###
This class extends the basic functionality of the `\Skinny\Storage` to provide a basic method of
storing and retrieving a users authentication status. Calling `login()` and `logout()` provides
the core of the class
