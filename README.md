# Demands for classes
Design and implementation of the university platform in the microservices architecture supporting the demands for classes

Current implementation is just a small piece of this platform. Thanks to fragmentation to two applications (WebService, DemandService) we can easily add new applications, e.g., ScheduleService which can be responsible for creating new schedules based on created demands.

## Built With
* Docker - services are separate containers which communicate with each other over HTTP protocol
* TypeScript, Angular 7 - used to build WebService
* Symfony, PHP, Behat, PHPSpec - used to build DemandService
* PostgreSQL - database
