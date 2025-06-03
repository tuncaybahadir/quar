# Changelog

All notable changes to `quar` will be documented in this file

## 1.5.2 - 2025-06-03

### What's Changed

* In Generate.php, the value of the $encoding variable has been edited to be fetched from the Encoder class.
* README file has been updated.

## 1.5.1 - 2025-05-29

### What's Changed

* Working tests were performed with Laravel 10, Php 8.3 and Php 8.4 versions.
* Some document images have been renamed.
* README file has been updated.

## 1.5.0 - 2025-05-24

### What's Changed

* Two body pattern designs were included in the project.
* Body pattern images have been replaced with larger images.
* README file has been updated.

## 1.4.1 - 2025-05-15

### What's Changed

* Fixed a bug where assigning a style value while creating a QR Code would change the marker design.
* README file has been updated.

## 1.4.0 - 2025-05-13

### What's Changed

* Added New Marker Type Called Ring.
* README file has been updated.

## 1.3.2 - 2025-05-11

### What's Changed

* The incorrect type definition of $errorCorrection in the Generate class has been edited and phpdocs has been updated.

## 1.3.1 - 2025-05-10

### What's Changed

* README file has been updated.

## 1.3.0 - 2025-05-10

### What's Changed

* Merge method base_path usage has been fixed.
* For the use of hex colors, the hexToRgb method is included in the Generate class as private.
* color, backgroundColor methods have been adapted to use hex color codes.
* Added eyeColorFromHex method for using hex color codes in eye color paintings.
* createColor method has been converted to private method.
* hex color code usage and background coloring examples have been added.
* laravel/pint package version updated to 1.22.1
* README file has been updated.

## 1.2.4 - 2025-05-02

### What's Changed
* Examples for the body pattern have been added to the readme file and the file has been made more readable.
* Updated composer.json file description field.

## 1.2.3 - 2025-04-28

### What's Changed
* Sample scripts and result screens for the merge operation have been added to the README file.

## 1.2.2 - 2025-04-28

### What's Changed

* The createImage, setProperties, calculateCenter, calculateOverlap methods in the ImageMerge class have been made private.
* Fixed the transparent image background issue in the ImageMerge class merge method and made performance improvements specific to php 8.3 and 8.4.


## 1.2.1 - 2025-04-27

### What's Changed

* Fixed an error when triggering the merge method.

## 1.2.0 - 2025-04-26

### What's Changed

* Removed version information from composer.json.
* The missing return types and docs parts of the methods in the classes have been edited.
* Fixed data control has been added for type in the Generate class gradient method.
* README file has been updated.

## 1.0.0 - 2025-04-10

### What's Changed

* Version structure has been changed.
* laravel/pint package version updated to 1.22.
* README file has been updated.