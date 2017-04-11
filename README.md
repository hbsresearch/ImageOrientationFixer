ImageOrientationFixer
===================

This repository contains a php class that fix images orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

[![Latest Stable Version](https://poser.pugx.org/rdavaillaud/image-orientation-fixer/v/stable.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fixer) [![Total Downloads](https://poser.pugx.org/rdavaillaud/image-orientation-fixer/downloads.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fixer) [![Latest Unstable Version](https://poser.pugx.org/rdavaillaud/image-orientation-fixer/v/unstable.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fixer) [![License](https://poser.pugx.org/rdavaillaud/image-orientation-fixer/license.png)](https://packagist.org/packages/rdavaillaud/image-orientation-fixer) [![Build Status](https://travis-ci.org/rdavaillaud/ImageOrientationFixer.svg?branch=master)](https://travis-ci.org/rdavaillaud/ImageOrientationFixer)

## How to use

```php
$iof = new ImageOrientationFixer();
$iof->fix('foo.jpg');
```

or to create a new file from the image.

```php
$iof = new ImageOrientationFixer();
$iof->fix('foo.jpg', 'destination.jpg');
```

## Credits

Thanks to [jellybellydev](https://github.com/jellybellydev) for the [initial library](https://github.com/jellybellydev/ImageOrientationFix)
Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
