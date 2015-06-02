<?php

namespace Main\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MainFrontBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
