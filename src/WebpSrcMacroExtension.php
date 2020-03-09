<?php

namespace ADT;

use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;

class WebpSrcMacroExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		
		$builder->addDefinition($this->prefix('webpSrcMacroService'))
			->setFactory(WebpSrcMacro::class);
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();



		/** @var FactoryDefinition $latteFactoryService */
		$latteFactoryService = $builder->getDefinitionByType(ILatteFactory::class);
		$latteFactoryServiceDefinition = $latteFactoryService->getResultDefinition();

		$latteFactoryServiceDefinition->addSetup('?->onCompile[] = function ($engine) { '.WebpSrcMacro::class.'::install($engine->getCompiler()); };', ['@self']);

		$latteFactoryServiceDefinition->addSetup('addProvider', ['webpSrcMacroService', '@'.$this->prefix('webpSrcMacroService')]);
	}

}
