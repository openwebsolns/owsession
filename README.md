# Session Handler for PHP

This library provides a thin wrapper around PHP's `session_*` functions so that they can be used from a static context. It also provides some additional common functionality such as:

  * "flash" messages
  * de/serialization of complex objects
  * NOTICE squashing when accessing values


## Usage

Basic workflow:

    Session::init();
    Session::s('key', "Some value");

    // ...

    Session::commit(); // performed automatically at end of script


## Flash Messages

In addition to storing arbitrary objects in the session via the `Session::g` method, the Session class also provides mechanism for flash messages: strings that are saved from one request to the next.