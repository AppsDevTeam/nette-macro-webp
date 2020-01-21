Nette Macro Webp
=========

Installation
---------

`$ composer require adt/nette-macro-webp:@dev`

To your `config.neon` add:

```neon
latte:
	macros:
		- App\WebpSrcMacro::install
```

Usage
---------

Latte:

```latte
<img n:webpSrc="myImage.webp">
```

