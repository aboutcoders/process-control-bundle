Process Control Bundle
======================

A symfony bundle that allows asynchronous processing of jobs.

## Installation

Add the bundle:

``` json
{
    "require": {
        "aboutcoders/job-bundle": "dev-master"
    }
}
```

Enable the bundle in the kernel:

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Abc\Bundle\ProcessControlBundle\AbcProcessControlBundle(),
        // ...
    );
}
```