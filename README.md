# Qr Code Generator For Laravel

<p align="center">
    <a href="https://packagist.org/packages/tuncaybahadir/quar">
        <img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/tuncaybahadir/quar.svg?logo=packagist&logoColor=f5f5f5">
    </a>&nbsp;
    <img alt="php" src="https://img.shields.io/badge/php-8.3%20--%208.4-brightgreen.svg?logo=php">&nbsp;
    <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11%20--%2012-blue.svg?logo=laravel">&nbsp;
    <a href="https://packagist.org/packages/tuncaybahadir/quar">
        <img alt="Downloads" src="https://img.shields.io/packagist/dt/tuncaybahadir/quar.svg?logo=down">
    </a>&nbsp;
    <img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg">
</p>

## Introduction
Quar is a new package that includes compatibility for Laravel 11 and Php 8.3 and higher versions of the packages provided by [Bacon/BaconQrCode] (https://github.com/Bacon/BaconQrCode) and [simplesoftwareio/simple-qrcode] (https://github.com/SimpleSoftwareIO/simple-qrcode).

## Requirements

- PHP 8.3+
- Laravel 11+

## Installing

Use Composer to install it:

```
composer require tuncaybahadir/quar
```

## Simple usage

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::generate('Quar package create qr code');

```

And use it in your blade template this way:

```html
<div>
    {{ $qr }}
</div>
```
## Simple usage Response
![Example 1](docs/images/example-1.png)


## Example of Setting Qr Code Size

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::size(61)
            ->generate('Quar package create qr code');
```
## Qr Code Size Response
![Example 2](docs/images/example-2.png)

## Example of Setting Qr Code Detection Markers

## Available Markers type

- `square`: Default marker type
- `rounded`
- `circle`

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('rounded')
            ->generate('Quar package create qr code');
```
## Qr Code Rounded Markers Response
![Example 3](docs/images/example-3.png)

## Qr Code Coloring Markers Example

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('square')
            ->eyeColor(0, 113, 6, 22, 113, 6, 22)
            ->eyeColor(1, 122, 185, 231, 122, 185, 231)
            ->eyeColor(2, 252, 184, 17)
            ->size(161)
            ->generate('Quar package create qr code');
```
## Qr Code Coloring Markers Response
![Example 4](docs/images/example-4.png)


## Qr Code Coloring Example

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color(50, 168, 82)
            ->size(161)
            ->eye('circle')
            ->generate('Quar package create qr code');
```

## Qr Code Coloring Response
![Example 5](docs/images/example-5.png)

## Qr Code and Marker Coloring Example
 
```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color(235, 12, 83)
            ->size(161)
            ->eye('rounded')
            ->eyeColor(0, 113, 6, 22, 113, 6, 22)
            ->eyeColor(1, 122, 185, 231, 122, 185, 231)
            ->eyeColor(2, 252, 184, 17)
            ->generate('Quar package create qr code');
```

## Qr Code and Marker Coloring Response 
![Example 6](docs/images/example-6.png)

## Example of Saving Qr Code as a Png File

```php
use tbQuar\Facades\Quar;
    
        $qrCodeFileName = md5(random_int(0, 9999999).date('H:i:s d.m.Y')).'_qr_code';
        $qrCodeData = 'Quar package create qr code';
        $qrCodeDirectory = storage_path('app/public/qr-code-images/');

        Quar::format('png')
            ->color(155, 155, 200)
            ->size(200)
            ->eye('rounded')
            ->generate($qrCodeData, $qrCodeDirectory.$qrCodeFileName.'.png');

        $qrCode = url('storage/qr-code-images/'.$qrCodeFileName.'.png');

```
And use it in your blade template this way:

```html
<div>
    <img src="{{ $qrCode }}" />
</div>
```
## Saving Qr Code as a File Response
![Example 7](docs/images/example-7.png)
![Example 8](docs/images/example-8.png)

## Example of Compressing and Saving a Qr Code as a Png File
```php
use tbQuar\Facades\Quar;
    
        $qrCodeFileName = md5(random_int(0, 9999999).date('H:i:s d.m.Y')).'_qr_code';
        $qrCodeData = 'Quar package create qr code';
        $qrCodeDirectory = storage_path('app/public/qr-code-images/');

        Quar::format('png')
            ->setPngCompression(50)
            ->color(155, 155, 200)
            ->size(200)
            ->eye('rounded')
            ->generate($qrCodeData, $qrCodeDirectory.$qrCodeFileName.'.png');

        $qrCode = url('storage/qr-code-images/'.$qrCodeFileName.'.png');

```

## Authors

- [Tuncay Bahadır](https://github.com/tuncaybahadir)


## Contributing

Pull requests and issues are more than welcome.