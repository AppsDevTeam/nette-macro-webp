<?php

namespace ADT;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Nette\Http\Request;
use Nette\Http\Url;
use Nette\Utils\Strings;

class WebpSrcMacro
{

	/**
	 * @var Request
	 */
	private $request;
	
	/** @var bool */
	public $acceptWebp = NULL;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public static function install(Compiler $compiler)
	{
		$set = new MacroSet($compiler);
		$set->addMacro('webpSrc', null, null, function (MacroNode $node, PhpWriter $writer) {

			if (isset($node->htmlNode->attrs['src'])) {
				throw new CompileException('It is not possible to combine src with n:webpSrc.');
			}

			return $writer->write('$this->global->webpSrcMacroService->renderSrc(%node.word);');

		});
	}

	private function isClientAcceptingWebp()
	{
		if ($this->acceptWebp === NULL) {
			$this->acceptWebp = strpos($this->request->getHeader('Accept'), 'image/webp') !== false;
		}
		return $this->acceptWebp;
	}

	public function renderSrc($originalSrc)
	{
		if (!$this->isClientAcceptingWebp()) {
			echo ' src="'.$originalSrc.'"';
			return;
		}

		$originalUrl = new Url($originalSrc);
		if (Strings::endsWith($originalUrl->path, ".webp")) {
			echo ' src="'.$originalSrc.'"';
			return;
		}

		$webpUrl = clone $originalUrl;
		$webpUrl->path = preg_replace("/\.[^\/.]+$/", ".webp", $originalUrl->path);
		$webpSrc = (string)$webpUrl;
		echo ' src="'.$webpSrc.'"';
	}

}

