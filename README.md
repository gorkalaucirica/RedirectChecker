# Redirect Checker

> Automated URL redirection checker

<p align="center">
    <img src="https://raw.githubusercontent.com/gorkalaucirica/RedirectChecker/master/etc/docs/gifs/example.gif">
</p>

## Features

* Automate URL redirections tests for your application
* Add origin and destination urls to ensure redirections are working properly
* Seamless integration with your existing project
* Simple YAML file to define tests 

## Installation and usage

1. Install this component using Composer

```bash
$ composer require gorkalaucirica/redirect-checker
```

2. Create your yaml file

```yaml
# tests/redirections/example.yml
https://google.com/services: https://www.google.com/services/
http://google.es/preferences: https://www.google.es/preferences
http://support.google.com: https://support.google.com/
```

3. Run the command

```bash
$ vendor/bin/redirect-checker yaml tests/redirections/example.yml
```

## Licensing Options
[![License](https://poser.pugx.org/gorkalaucirica/redirect-checker/license.svg)](https://github.com/gorkalaucirica/RedirectChecker/blob/master/LICENSE)
