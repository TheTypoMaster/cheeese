<?php

namespace Main\CommunityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MainCommunityBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
