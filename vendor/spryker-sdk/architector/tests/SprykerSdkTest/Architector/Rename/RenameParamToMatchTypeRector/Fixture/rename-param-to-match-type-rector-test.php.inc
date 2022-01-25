<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Generater\Shared\Transfer\FooBarTransfer;

class FooBar
{
    /**
     * @param \Generater\Shared\Transfer\FooBarTransfer $fooBar
     *
     * @return void
     */
    public function fooBarMethod(FooBarTransfer $fooBar)
    {
        $foo = $fooBar;
    }
}
-----
<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Generater\Shared\Transfer\FooBarTransfer;

class FooBar
{
    /**
     * @param \Generater\Shared\Transfer\FooBarTransfer $fooBarTransfer
     *
     * @return void
     */
    public function fooBarMethod(FooBarTransfer $fooBarTransfer)
    {
        $foo = $fooBarTransfer;
    }
}
