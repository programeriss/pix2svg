# Convert image to the SVG format

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package provides an easy to work with class to convert image's to svg.

## Installation

The package can be installed via composer:
``` bash
composer require programeriss/pix2svg
```

## Usage

Converting a image to the svg is easy.

```php
$pix2svg = new Programeriss\Pix2svg\Pix2svg();
$svg = $pix2svg->convert($pathToImage);
```

If the path you pass to `convert` has the extensions `jpg`, `jpeg`, `gif`, or `png` the image will be returned in the svg format.
Otherwise the output will be a error.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email programeriss@gmail.com instead of using the issue tracker.

## Credits

- [Aidas Eglinskas](https://github.com/programeriss)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
