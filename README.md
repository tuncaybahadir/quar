# Qr Code Generator For Laravel

<p align="center">
    <a href="https://packagist.org/packages/tuncaybahadir/quar"><img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/tuncaybahadir/quar.svg?logo=packagist&logoColor=f5f5f5"></a>&nbsp;
    <img alt="php" src="https://img.shields.io/badge/php-8.2%20--%208.3%20--%208.4%20--%208.5-brightgreen.svg?logo=php">&nbsp;
    <img alt="Laravel" src="https://img.shields.io/badge/Laravel-10%20--11%20--%2012-blue.svg?logo=laravel">&nbsp;
    <a href="https://packagist.org/packages/tuncaybahadir/quar"><img alt="Downloads" src="https://img.shields.io/packagist/dt/tuncaybahadir/quar.svg?logo=down"></a>&nbsp;
    <img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg">
</p>

## Introduction
To generate Qr Code with Laravel, Quar references packages provided by [Bacon/BaconQrCode] (https://github.com/Bacon/BaconQrCode) and [simplesoftwareio/simple-qrcode] (https://github.com/SimpleSoftwareIO/simple-qrcode). The Quar package is a new qr code generation package with compatibility for Laravel 10, Laravel 11, Laravel 12 and Php 8.2, Php 8.3, Php 8.4, Php 8.5.

***
<a href="https://www.buymeacoffee.com/tuncaybahadir" target="_blank">![Example 17](docs/images/coffe.png)</a>
***

## Minimum Requirements

- PHP 8.2+
- Laravel 10+

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

***

## Example of Setting Qr Code Size

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::size(61)
            ->generate('Quar package create qr code');
```
## Qr Code Size Response
![Example 2](docs/images/example-2.png)

***

## Example of Setting Qr Code Detection Markers

## Available Markers Type

- `square`: Default Marker Type
- `rounded`
- `circle`
- `ring`

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('rounded')
            ->generate('Quar package create qr code');
```
## Qr Code Markers Response
![Example 12](docs/images/example-12.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![Example 3](docs/images/example-3.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
![Example 13](docs/images/example-13.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![Example 21](docs/images/example-21.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

***

## Example of Setting Qr Code Body Pattern Style

## Available Body Pattern

- `square`: Default Pattern
- `dot`
- `round`
- `star`
- `vertigo`

`Attention : The maximum smoothness value for square, dot and round designs is 0.9 and the maximum smoothness value for star and vertigo designs is 0.5.`

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::style('dot', 0.9)
            ->generate('Quar package create qr code');
```
## Qr Code Body Pattern Style Response
![Example 14](docs/images/example-24.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![Example 15](docs/images/example-25.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
![Example 16](docs/images/example-26.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![Example 22](docs/images/example-22.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
![Example 23](docs/images/example-23.png)

***

## Example of Setting Qr Code Gradient Coloring

## Available Gradient Type

- `vertical`
- `horizontal`
- `diagonal`
- `inverse_diagonal`
- `radial`

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('rounded')
            ->size(161)
            ->gradient(20, 192, 241 , 164, 29, 52 , 'vertical')
            ->generate('Quar package create qr code');
```
## Qr Code Gradient Coloring Response
![Example 9](docs/images/example-9.png)

***

## Qr Code Coloring Markers Example 1

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('square')
            ->eyeColor(0, 113, 6, 22, 113, 6, 22)
            ->eyeColor(1, 122, 185, 231, 122, 185, 231)
            ->eyeColor(2, 252, 184, 17)
            ->size(161)
            ->generate('Quar package create qr code');
```
## Qr Code Coloring Markers Response 1
![Example 4](docs/images/example-4.png)

***

## Qr Code Hex Code With Coloring Markers Example 2

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::eye('square')
            ->eyeColorFromHex('0', '#710616', '#710616')
            ->eyeColorFromHex('1', '#7ab9e7', '#7ab9e7')
            ->eyeColorFromHex('2', '#fcb811', '#fcb811')
            ->size(161)
            ->generate('Quar package create qr code');
```
## Qr Code Hex Code With Coloring Markers Response 2
![Example 17](docs/images/example-17.png)

***

## Qr Code Coloring Example 1

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color(50, 168, 82)
            ->size(161)
            ->eye('circle')
            ->generate('Quar package create qr code');
```

## Qr Code Coloring Response 1
![Example 5](docs/images/example-5.png)

***

## Qr Code Hex Code With Coloring Example 2

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color('#32a852')
            ->size(161)
            ->eye('circle')
            ->generate('Quar package create qr code');
```

## Qr Code Hex Code With Coloring Response 2
![Example 18](docs/images/example-18.png)

***

## Example Of Coloring Qr Code Background With Hex Code 1

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color('#710616')
            ->backgroundColor('#7ab9e7')
            ->size(261)
            ->eye('circle')
            ->generate('Quar package create qr code');
```

## Example Of Coloring Qr Code Background With Hex Code 1
![Example 19](docs/images/example-19.png)

***

## Example Of Coloring The Background Of Qr Code 2

```php
use tbQuar\Facades\Quar;
    
$qr = Quar::color(113, 6, 22)
            ->backgroundColor(122, 185, 231)
            ->size(261)
            ->eye('circle')
            ->generate('Quar package create qr code');
```

## Sample Result Of Coloring The Background Of Qr Code Response 2
![Example 20](docs/images/example-20.png)

***

## Qr Code And Markers Coloring Example

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

***

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
![Example 7](docs/images/example-7.png)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;![Example 8](docs/images/example-8.png)

***

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

***

## Example of Adding Logo on QR Code

`Attention !!! : Due to a bug in the BaconQrCode package, you must set the margin value to 1 or higher during logo insertion. Otherwise the qr code is generated incorrectly.`

Example 1:
```php
use tbQuar\Facades\Quar;
    
        $qr = Quar::format('png')
            ->margin(1)
            ->merge(public_path('php.png'), .2, true)
            ->size(400)
            ->generate('Quar package create qr code');
            
            return view('test', [
                'qrCode' => base64_encode($qr),
            ]);

```
Example 2:
```php
use tbQuar\Facades\Quar;
    
        $qr = Quar::format('png')
            ->margin(1)
            ->eye('rounded')
            ->merge(public_path('php.png'), .3, true)
            ->size(200)
            ->gradient(100, 20, 5 , 7, 9, 12 , 'VERTICAL')
            ->generate('Quar package create qr code');
            
            return view('test', [
                'qrCode' => base64_encode($qr),
            ]);

```

And use it in your blade template this way:

```html
<div>
    <img src="data:image/png;base64,{{ $qrCode }}" />
</div>
```
## Adding Logo on Qr Code Sample Code Result 1:
![Example 10](docs/images/example-10.png)

## Adding Logo on Qr Code Sample Code Result 2:
![Example 11](docs/images/example-11.png)

## Example of Adding Logo on QR Code with Laravel Conditionable Trait class

Example :
```php
use tbQuar\Facades\Quar;

        $qr = Quar::format('png')
            ->margin(1)
            ->when(($logo = config('app.company.logo')) && file_exists($logo))
            ->merge($logo, .25, absolute: true)
            ->size(400)
            ->generate('Quar package create qr code', $path);

            return view('test', [
                'qrCode' => base64_encode($qr),
            ]);
```

## Adding Text Around a QR Code

Example :

```php
use tbQuar\Facades\Quar;

        $qr = Quar::size(300)
            ->withText('Quar Code Package')
            ->generate('Quar package create qr code');
```
Example Result :

![Example 27](docs/images/example-27.png)

## Customizing the Text

Example :

```php
use tbQuar\Facades\Quar;

        $qr = Quar::size(300)
            ->withText('Quar Code Package')
            ->configureText(function($text) {
                $text->setTextColor('#54ac6e')
                    ->setFontSize(12)
                    ->setPosition('top-right')
                    ->setPadding(25)
                    ->setBackgroundColor('#151521')
                    //->setFont('/path/to/font.ttf')
                    ->setBackgroundOpacity(0.1);
            })
            ->generate('Quar package create qr code');
```

Example Result :

![Example 28](docs/images/example-28.png)

## Available Text Positions

- `top`,
- `bottom` : Default Position,
- `left`,
- `right`,
- `top-left`,
- `top-right`,
- `bottom-left`,
- `bottom-right`,


Example :

```php
use tbQuar\Facades\Quar;

        $qr = Quar::size(300)
            ->withText('🇹🇷 Türkiye 🇹🇷')
            ->configureText(function($text) {
                $text->setTextColor('#ce2026')
                    ->setFontSize(30)
                    ->setPosition('top')
                    ->setBackgroundOpacity(0.1);
            })
            ->generate('Quar package create qr code');
```

Example Result :

![Example 29](docs/images/example-29.png)

## Authors

- [Tuncay Bahadır](https://github.com/tuncaybahadir)

***

## Contributing

Pull requests and issues are more than welcome.