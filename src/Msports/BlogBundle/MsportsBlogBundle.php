<?php

namespace Msports\BlogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MsportsBlogBundle extends Bundle
{
	public function getParent()
    {
        return 'EDBlogBundle';
    }
}
