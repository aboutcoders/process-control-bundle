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

The AbcProcessControlBundle registers a service with the name `abc.process_control.controller` in the service container. This service is by default initialized with a PCNTL implementation, that listents to the `SIGTERM` event and thus indicates to exist, when this signal was sent.

```php

    $controller = $container->get('abc.process_control.controller');
    
    if($controller->doExit()){
        // true if SIGTERM was sent
    }
```

## Configuration

Registration of the service `abc.process_control.controller` is enabled by default. You can disable that with the following configuration:

```yaml
abc_process_control:
    register_controller: false
```

__Note: When registration is disabled the service `abc.process_control.controller` does not exist within the service container.__