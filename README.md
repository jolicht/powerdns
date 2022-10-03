# Powerdns Api Client

Library to use PowerDNS-Api.

## Installation

Use [Composer](https://getcomposer.org/) to install the bundle. 

```shell
composer require jolicht/powerdns-bundle
```
## Usage

See Integration tests in [.tests/Integration](./tests/Integration) for usage examples

## Metrics and scripts

### Integration Test Configuration

Copy `phpunit.xml.dist` to `phpunit.xml` and configure `powerdns_base_uri` 
and `powerdns_api_key` as environment variables, for example:

```xml
<php>
    <env name="powerdns_base_uri" value="http://your-power-dns-host:8082/api/v1/servers/localhost/" />
    <env name="powerdns_api_key" value="your_powerdns_api_key" />
</php>
```

### Run scripts to run tests, generate metrics and fix styles

* `composer test`: run unit tests
* `composer coverage`: run unit tests and generate code coverage analysis
* `composer integration`: run integration tests
* `composer psalm`: run static code analysis
* `composer fix-style`: fix code style errors
* `composer check-style`: check for code style errors
* `composer metrics`: run `composer coverage`, `composer psalm` and `composer check-style`
