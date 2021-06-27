# qr2assistme

## What is it?

QR2AssistMe is an open source web system to control  equipments or objects for technical services companies using QR Codes help tracking them. 


QR2AssistMe uses [codeigniter](http://codeigniter.com) framework as a base for development and additionally uses other key libraries and components described bellow.


## Installation & Configuration

* **Download** the Release
* **Create** a MySQL or MariaDB database called qrassist and take note of user/password information
* **Copy** .env.default to .env
* **Edit** .env accordingly (set base URL, google client and mysql information for example)
* **Run** refersh.sh (linux) or refresh.bat (windows)
* **That's it!**  
* After that it is a normal codeigniter application to develop

## Custom Classes
There are a number of new Classes Base classes added to help ordinary tasks:
* BaseModel
* BaseView
* BaseAdminLteView
* BaseAdminLteCRUDView
* BaseEntity
* BaseTableMigration
* BaseProcedureMigration
* BaseController
* BaseAdminLteController
* BaseAdminLteCRUDController


## Licencing

QR2AssistMe available under [GNU GPLv3](https://www.gnu.org/licenses/gpl-3.0.html) licence.

## Libraies and components used

brick/math                           0.9.2     Arbitrary-precision arithmetic library
chillerlan/php-qrcode                3.4.0     A QR code generator. PHP 7.2+
chillerlan/php-settings-container    1.2.1     A container class for immutable settings objects. Not a DI container. PHP 7.2+
codeigniter4/framework               v4.1.2    The CodeIgniter framework v4
composer/ca-bundle                   1.2.10    Lets you find a path to the system CA bundle, and includes a fallback to the Mozilla CA bundle.
composer/composer                    2.1.3     Composer helps you declare, manage and install dependencies of PHP projects. It ensures you have the right stack everywhere.
composer/metadata-minifier           1.0.0     Small utility library that handles metadata minification and expansion.
composer/semver                      3.2.5     Semver library that offers utilities, version constraint parsing and validation.
composer/spdx-licenses               1.5.5     SPDX licenses list and validation library.
composer/xdebug-handler              2.0.1     Restarts a process without Xdebug.
doctrine/instantiator                1.4.0     A small, lightweight utility to instantiate objects in PHP without invoking their constructors
dominikb/composer-license-checker    2.2.0     Utility to check for licenses of dependencies and block/allow them.
facebook/graph-sdk                   5.7.0     Facebook SDK for PHP
fakerphp/faker                       v1.14.1   Faker is a PHP library that generates fake data for you.
firebase/php-jwt                     v5.2.1    A simple library to encode and decode JSON Web Tokens (JWT) in PHP. Should conform to the current spec.
google/apiclient                     v2.9.1    Client library for Google APIs
google/apiclient-services            v0.176.0  Client library for Google APIs
google/auth                          v1.15.1   Google Auth Library for PHP
guzzlehttp/guzzle                    7.3.0     Guzzle is a PHP HTTP client library
guzzlehttp/promises                  1.4.1     Guzzle promises library
guzzlehttp/psr7                      1.8.2     PSR-7 message implementation that also provides common utility methods
justinrainbow/json-schema            5.2.10    A library to validate a json schema.
kint-php/kint                        3.3       Kint - debugging tool for PHP developers
laminas/laminas-escaper              2.7.0     Securely and safely escape HTML, HTML attributes, JavaScript, CSS, and URLs
laminas/laminas-zendframework-bridge 1.2.0     Alias legacy ZF class names to Laminas Project equivalents.
mikey179/vfsstream                   v1.6.8    Virtual file system to mock the real file system in unit tests.
monolog/monolog                      2.2.0     Sends your logs to files, sockets, inboxes, databases and various web services
myclabs/deep-copy                    1.10.2    Create deep copies (clones) of your objects
nikic/php-parser                     v4.10.5   A PHP parser written in PHP
paragonie/constant_time_encoding     v2.4.0    Constant-time Implementations of RFC 4648 Encoding (Base-64, Base-32, Base-16)
paragonie/random_compat              v9.99.100 PHP 5.x polyfill for random_bytes() and random_int() from PHP 7
phar-io/manifest                     2.0.1     Component for reading phar.io manifest information from a PHP Archive (PHAR)
phar-io/version                      3.1.0     Library for handling version information and constraints
phpdocumentor/reflection-common      2.2.0     Common reflection classes used by phpdocumentor to reflect the code structure
phpdocumentor/reflection-docblock    5.2.2     With this component, a library can provide support for annotations via DocBlocks or otherwise retrieve information that is embedded in a...
phpdocumentor/type-resolver          1.4.0     A PSR-5 based resolver of Class names, Types and Structural Element Names
phpseclib/phpseclib                  3.0.8     PHP Secure Communications Library - Pure-PHP implementations of RSA, AES, SSH2, SFTP, X.509 etc.
phpspec/prophecy                     1.13.0    Highly opinionated mocking framework for PHP 5.3+
phpunit/php-code-coverage            9.2.6     Library that provides collection, processing, and rendering functionality for PHP code coverage information.
phpunit/php-file-iterator            3.0.5     FilterIterator implementation that filters files based on a list of suffixes.
phpunit/php-invoker                  3.1.1     Invoke callables with a timeout
phpunit/php-text-template            2.0.4     Simple template engine.
phpunit/php-timer                    5.0.3     Utility class for timing
phpunit/phpunit                      9.5.4     The PHP Unit Testing framework.
psr/cache                            1.0.1     Common interface for caching libraries
psr/container                        1.1.1     Common Container Interface (PHP FIG PSR-11)
psr/http-client                      1.0.1     Common interface for HTTP clients
psr/http-message                     1.0.1     Common interface for HTTP messages
psr/log                              1.1.4     Common interface for logging libraries
psr/simple-cache                     1.0.1     Common interfaces for simple caching
ralouphie/getallheaders              3.0.3     A polyfill for getallheaders.
ramsey/collection                    1.1.3     A PHP 7.2+ library for representing and manipulating collections.
ramsey/uuid                          4.1.1     A PHP library for generating and working with universally unique identifiers (UUIDs).
react/promise                        v2.8.0    A lightweight implementation of CommonJS Promises/A for PHP
sebastian/cli-parser                 1.0.1     Library for parsing CLI options
sebastian/code-unit                  1.0.8     Collection of value objects that represent the PHP code units
sebastian/code-unit-reverse-lookup   2.0.3     Looks up which function or method a line of code belongs to
sebastian/comparator                 4.0.6     Provides the functionality to compare PHP values for equality
sebastian/complexity                 2.0.2     Library for calculating the complexity of PHP code units
sebastian/diff                       4.0.4     Diff implementation
sebastian/environment                5.1.3     Provides functionality to handle HHVM/PHP environments
sebastian/exporter                   4.0.3     Provides the functionality to export PHP variables for visualization
sebastian/global-state               5.0.2     Snapshotting of global state
sebastian/lines-of-code              1.0.3     Library for counting the lines of code in PHP source code
sebastian/object-enumerator          4.0.4     Traverses array structures and object graphs to enumerate all referenced objects
sebastian/object-reflector           2.0.4     Allows reflection of object attributes, including inherited and non-public ones
sebastian/recursion-context          4.0.4     Provides functionality to recursively process PHP variables
sebastian/resource-operations        3.0.3     Provides a list of PHP built-in functions that operate on resources
sebastian/type                       2.3.1     Collection of value objects that represent the types of the PHP type system
sebastian/version                    3.0.2     Library that helps with managing the version number of Git-hosted PHP projects
seld/jsonlint                        1.8.3     JSON Linter
seld/phar-utils                      1.1.1     PHAR file format utilities, for when PHP phars you up
symfony/cache                        v5.3.0    Provides an extended PSR-6, PSR-16 (and tags) implementation
symfony/cache-contracts              v2.4.0    Generic abstractions related to caching
symfony/console                      v5.3.2    Eases the creation of beautiful and testable command line interfaces
symfony/css-selector                 v5.3.0    Converts CSS selectors to XPath expressions
symfony/deprecation-contracts        v2.4.0    A generic function and convention to trigger deprecation notices
symfony/dom-crawler                  v5.3.0    Eases DOM navigation for HTML and XML documents
symfony/filesystem                   v5.3.0    Provides basic utilities for the filesystem
symfony/finder                       v5.3.0    Finds files and directories via an intuitive fluent interface
symfony/polyfill-ctype               v1.22.1   Symfony polyfill for ctype functions
symfony/polyfill-intl-grapheme       v1.23.0   Symfony polyfill for intl's grapheme_* functions
symfony/polyfill-intl-normalizer     v1.23.0   Symfony polyfill for intl's Normalizer class and related functions
symfony/polyfill-mbstring            v1.23.0   Symfony polyfill for the Mbstring extension
symfony/polyfill-php73               v1.23.0   Symfony polyfill backporting some PHP 7.3+ features to lower PHP versions
symfony/polyfill-php80               v1.23.0   Symfony polyfill backporting some PHP 8.0+ features to lower PHP versions
symfony/process                      v5.3.2    Executes commands in sub-processes
symfony/service-contracts            v2.4.0    Generic abstractions related to writing services
symfony/string                       v5.3.2    Provides an object-oriented API to strings and deals with bytes, UTF-8 code points and grapheme clusters in a unified way
symfony/var-exporter                 v5.3.2    Allows exporting any serializable PHP data structure to plain PHP code
theseer/tokenizer                    1.2.0     A small library for converting tokenized PHP source code into XML and potentially other formats
webmozart/assert                     1.10.0    Assertions to validate method input/output with nice error messages.
