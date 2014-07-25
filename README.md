Symfony Job Bundle
==========================

A symfony bundle that allows asynchronous processing of jobs.

## Configuration

Add the bundle:

``` json
{
    "require": {
        "aboutcoders/job-bundle": "dev-master"
    }
}
```

Enable the bundles in the kernel:

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Sonata\NotificationBundle\SonataNotificationBundle(),
        new YZ\SupervisorBundle\YZSupervisorBundle()
        new Abc\Bundle\JobBundle\AbcJobBundle(),
        // ...
    );
}
```

Follow the installation and configuration instructions of the third party bundles:

* [SonataNotificationBundle](http://sonata-project.org/bundles/notification/master/doc/index.html)
* [YZSupervisorBundle](https://github.com/yzalis/SupervisorBundle)

Configure the bundle

``` yaml
# app/config/config.yml
abc_job:
  db_driver: orm
  job_logs_dir: "%kernel.logs_dir%"
```

Add a new doctrine mapping types

``` yaml
# app/config/config.yml
doctrine:
    dbal:
        types:
            status: Abc\Bundle\JobBundle\Doctrine\Types\StatusType
            serializable: Abc\Bundle\JobBundle\Doctrine\Types\SerializableType
```


## Usage

### Request execution of a background job

```php
// retrieve the job manager from the service container
$manager = $container->get('abc.job.manager');

$ticket = $manager->addJob('mailer', $parameters);
```

The first parameter specifies the type of the job whereas the second parameter must implement the interface \Serializable.

Whenever you add a new job you will get a ticket that you can use to retrieve information about the job.

### Request information about a background job

#### Logs

Retrieve logs of a job:

```php
$logString = $manager->getLogs($ticket)
```

#### Status

Retrieve current status of a job:

```php
$status = $manager->getStatus($ticket)
```

The following status values are defined:

```php
const REQUESTED  = 1;
const PROCESSING = 2;
const SLEEPING   = 3;
const PROCESSED  = 4;
const CANCELLED  = 5;
const ERROR      = 6;
```

#### Reports

Retrieve a report of a job:

```php
$report = $manager->getReport($ticket)

Please take a look at the api documentation (Abc\Bundle\JobBundle\Job\Report\ReportInterface) to get a overview of the information available in a report.

### Defining a new background job

In order to register a new job, you have to take these two steps:

- Create an executable class
- Register the executable in the service container

The executable class must implement the interface Executable, which defines only one method execute. This method receives a Job as argument.

```php
class Mailer implements Executable
{
    protected $mailer;

    /**
     * @param \Foo\Bar\Mailer $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Job $job)
    {
        $parameters = $job->getParameters();

		$message=$this->mailer->createMessage();
		$message->setTo($params['to']);
		
		...
		
		$this->mailer->send($message);
    }
}
```

The last step is to register the executable in the service container. This must be done by specifying a custom tag 'abc.job.listener' with a type.

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="abc.foobar.mailer" class="Abc\Foobar\Mailer">
            <tag name="abc.job.listener" type="mailer" level="debug" />
        </service>
    </services>
</container>
```

#### Logging

You have access to a standard PSR logger during the execution of a job.

```php
$job->getContext()->get('logger')->debug('A debug message');
```

This logger collects information of a single job execution and it's child jobs.

#### Child jobs

Within your current job you can request the execution of another job (child job).

```php
// request execution of another job
$ticket = $job->addChildJob('AnotherJob', $parameters);
```

Whenever a child job terminates the parent job will be called back. Within your current job you can get information about the child job that is calling back.

```php
// check if job was called back by a child job
if($job->isCallback())
{
    // get the job that is calling back
    $childJob = $job->getCallback();
}
```

You have also access to all other child jobs, that were created.

```php
// request execution of another job
if($job->hasChildJobs())
{
    $childJobs = $job->getChildJobs();
}
```

Please take a look at the event Abc\Bundle\JobBundle\Event\JobEvent to get an overview of the functions that are available in the execution context of a job.

## Adding custom parameters to the execution context

During execution of a job you have access to an execution context. This acts like a container where services, objects or parameters can be registered.

```php
$job->getContext();
```

Before every execution of a job an event with the name 'abc.job.prepare' containing an instance of Abc\Bundle\JobBundle\Event\JobEvent is sent to all registered listeners. Registered listeners hereby have the option to register additional parameters.

To hook into this event and add your own custom parameters, you have to create a service that will act as an event listener on that event.

```yml
# app/config/config.yml
services:
    kernel.listener.your_listener_name:
        class: Acme\DemoBundle\EventListener\MyJobListener
        tags:
            - { name: abc.job.event_listener, event: abc.job.prepare, method: onPrepare }
```

And within this service you can now add custom parameters to the context

```php

namespace Acme\DemoBundle\EventListener\MyJobListener;

use Abc\Bundle\JobBundle\Event\JobEvent;

class MyJobListener
{
    public function onPrepare(JobEvent $job)
    {
        $parameter = new Path/To/Custom/Parameter();

        $job->getContext()->set('custom', $parameter);
    }
}
```