<?php
namespace Yuma;
/**
 * ConstantExport Trait implements getConstants() method which allows
 * to return class constant as an assosiative array
 */
Trait ConstantExport
{
	/**
	 * @return [const_name => 'value', ...]
	 */
	static function getConstants(){
	//	$refl = new \ReflectionClass(__CLASS__);
	//	return $refl->getConstants();
		return (new ReflectionClass(get_class()))->getConstants();
	}
}
