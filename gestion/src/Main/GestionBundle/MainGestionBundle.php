<?php

namespace Main\GestionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MainGestionBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
