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

## Configuration

Registration of the service `abc.process_control.controller` is enabled by default. However, since PCNTL is only available in PHP CLI environment you can disable registration of the PCNTL controller with the following configuration:

```yaml
abc_process_control:
    register_controller: false
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

- Add option to register custom controllers in the service container via tags