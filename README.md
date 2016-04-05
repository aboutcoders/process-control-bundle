AbcProcessControlBundle
=======================

A symfony bundle that provides process control.

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

## ToDo:

- Add option to register custom controllers in the service container via tags