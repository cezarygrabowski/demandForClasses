# Demands for classes
Design and implementation of the university platform in the microservices architecture supporting the demands for classes

Current implementation is just a small piece of this platform. Thanks to fragmentation to two applications (WebService, ApiService) we can easily add new applications, e.g., ScheduleService which can be responsible for creating new schedules based on created demands.

## Development Process
First step was to collect all requirements - STRQ, FEAT ,UC, TERM.
Then I could separate functional areas and create UC Diagrams for each of them. 
Because of the limited domain knowledge I haven't split ApiService yet - suggested division would be: UserService and DomainService.
Thanks to use cases that I had created, I prepared scenarios in Behat - collection of those scenarios was my test suite.
I decided to separate commands from queries (CQRS). Every command is a separate file. There is currently somewhere about 10 commands in the system - it shows how limited number of business capabilities is implemented. I think that CQRS makes it easier for a new developer to understand the domain. One only needs to understand created commands(10 files) to have the big picture - queries do not provide essential domain knowledge.
To implement CQRS in my application (ApiService) I used tactician command bus https://github.com/thephpleague/tactician.

## Built With
* Docker - services are separate containers which communicate with each other over HTTP protocol
* TypeScript, Angular 7 - used to build WebService
* Symfony, PHP, Behat, PHPSpec - used to build ApiService
* PostgreSQL - database
