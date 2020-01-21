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

###### Latte

```latte
<img n:webpSrc="myImage.jpg">
```

Output
---------

If `myImage.webp` exists:

```html
<img src="myImage.webp" onerror="this.onerror=null; this.src='myImage.jpg'">
```

If `myImage.webp` do not exists:

```html
<img src="myImage.jpg">
```

