Nette Macro Webp
=========

Installation
---------

`$ composer require adt/nette-macro-webp:@dev`

To your `config.neon` add:

```neon
extensions:
	webpSrcMacroExtension: ADT\WebpSrcMacroExtension
```

Usage
---------

###### Latte

```latte
<img n:webpSrc="myImage.jpg">
```

Output
---------

If browser send header `Accept: image/webp`:

```html
<img src="myImage.webp">
```

If do not:

```html
<img src="myImage.jpg">
```

