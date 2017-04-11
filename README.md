ImageOrientationFix
===================

This repository contains a php class that fix images orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

[![Latest Stable Version](https://poser.pugx.org/rdavaillaud/image-orientation-fix/v/stable.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fix) [![Total Downloads](https://poser.pugx.org/rdavaillaud/image-orientation-fix/downloads.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fix) [![Latest Unstable Version](https://poser.pugx.org/rdavaillaud/image-orientation-fix/v/unstable.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fix) [![License](https://poser.pugx.org/rdavaillaud/image-orientation-fix/license.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fix) [![Build Status](https://travis-ci.org/rdavaillaud/ImageOrientationFix.svg?branch=master)](https://travis-ci.org/rdavaillaud/ImageOrientationFix)

## How to use

```php
$iof = new ImageOrientationFix();
$iof->fix('foo.jpg');
```

or to create a new file from the image.

```php
$iof = new ImageOrientationFix();
$iof->fix('foo.jpg', 'destination.jpg');
```

## Credits

Thanks to [jellybellydev](https://github.com/jellybellydev) for the [initial library](https://github.com/jellybellydev/ImageOrientationFix)
Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
