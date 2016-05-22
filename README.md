AbcProcessControlBundle
=======================

A symfony bundle that provides process control.

Build Status: [![Build Status](https://travis-ci.org/aboutcoders/process-control-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/process-control-bundle)

## Installation

Add the AbcProcessControlBundle to your `composer.json` file

```json
{
    "require": {
        "aboutcoders/process-control-bundle": "~1.0"
    }
}
```

Then include the bundle in the AppKernel.php class

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Abc\Bundle\ProcessControlBundle\AbcProcessControlBundle(),
        // ...
    );
}
```

## Usage

The AbcProcessControlBundle registers the service `abc.process_control.controller` in the service container. This controller is a PCNTL controller that listents to the `SIGTERM` event. So if this event is notified to the process running the symfony application the controller will indicate this:

```php

    $controller = $container->get('abc.process_control.controller');
    
    if($controller->doExit()){
        // true if SIGTERM was sent
    }
```

## ToDo:

- Add option to configure the events for the PCNTLController
- Add option to register custom controllers in the service container via tags