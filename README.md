# Slim Views

Simple extension for Twig and Slim.

Shamelessly based on [Slim-Views](https://github.com/codeguy/Slim-Views) by [Josh Lockhart](https://github.com/codeguy) and [Andrew Smith](https://github.com/silentworks).

## How to Install

#### using [Composer](http://getcomposer.org/)

Create a composer.json file in your project root:

```json
{
    "require": {
        "voilab/slim-extensions": "0.1.*"
    }
}
```

Then run the following composer command:

```bash
$ php composer.phar install
```

#### Twig

```php
$view->parserExtensions = array(
    new \Voilab\Slim\Views\TwigExtension(),
);
```

__rootUri__

Inside your Twig template you would write:

    {{ rootUri() }}

## Authors

[Alexandre Ravey](http://www.voilab.org)

## License

MIT Public License
