<?php

namespace Wame\Geolocate\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;


class GeolocateAutocompleteFormExtension extends CompilerExtension
{
	public function afterCompile(ClassType $class)
	{
		parent::afterCompile($class);
        
		$initialize = $class->methods['initialize'];
		$initialize->addBody('\Wame\Geolocate\Components\GeolocateAutocompleteFormControl::register(?);', ['addGeolocateAutocomplete']);
	}

}