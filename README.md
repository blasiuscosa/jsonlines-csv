# JSONLines to CSV
Read a data file, process each record, and produce an output file.

The input file is in jsonlines format (http://jsonlines.org), with each record representing an ecommerce order. Each order contains data about the customer, shipping details, payment data, items purchased, and any applied discounts.

The file is available in AWS S3 at https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl

Requirements
------------

  * PHP 7.3 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][2].

Installation
------------

[Download Symfony][4] to install the `symfony` binary on your computer and run this syntax

```bash
$ cd jsonlines-csv/
$ composer install
```

There's no need to configure anything to run the application. If you have
[installed Symfony][4] binary, run this command:

```bash
$ cd jsonlines-csv/
$ symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.

Tests
-----

Execute this command to run tests:

```bash
$ cd jsonlines-csv/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download
