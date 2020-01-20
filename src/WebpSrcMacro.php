<?php

namespace ADT;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Nette\Http\Url;
use Nette\Utils\Strings;

class WebpSrcMacro
{

	public static function install(Compiler $compiler)
	{
		$set = new MacroSet($compiler);
		$set->addMacro('webpSrc', null, null, function (MacroNode $node, PhpWriter $writer) {

			if (isset($node->htmlNode->attrs['src'])) {
				throw new CompileException('It is not possible to combine src with n:webpSrc.');
			}

			return $writer->write(self::class . "::renderSrc(%node.word);");

		});
	}

	public static function renderSrc($originalSrc)
	{
		$originalUrl = new Url($originalSrc);
		if (Strings::endsWith($originalUrl->path, ".webp")) {
			echo ' src="'.$originalSrc.'"';
			return;
		}

		$originalPathParts = [];
		$originalPathParts[] = $_SERVER['DOCUMENT_ROOT'];
		if (!Strings::startsWith($originalUrl->path, "/")) {
			$requestUrl = new Url($_SERVER['REQUEST_URI']);
			if ($requestUrl->path !== "/") {
				$originalPathParts[] = Strings::trim($requestUrl->path, "/");
			}
		}
		$originalPathParts[] = Strings::trim($originalUrl->path, "/");
		$originalPath = implode("/", $originalPathParts);
		$webpPath = preg_replace("/\.[^\/.]+$/", ".webp", $originalPath);

		if (file_exists($webpPath)) {
			$webpUrl = clone $originalUrl;
			$webpUrl->path = preg_replace("/\.[^\/.]+$/", ".webp", $originalUrl->path);
			$webpSrc = (string)$webpUrl;
			echo ' src="'.$webpSrc.'" onerror="this.onerror=null; this.src=\''.$originalSrc.'\'"';
		} else {
			echo ' src="'.$originalSrc.'"';
		}
	}

}

