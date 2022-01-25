<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use ArrayObject;

class FooBar
{
    /**
     * @param ArrayObject $fooBar
     *
     * @return void
     */
    public function fooBarMethod(ArrayObject $fooBar)
    {
        $foo = $fooBar;
    }
}
-----
<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use ArrayObject;

class FooBar
{
    /**
     * @param ArrayObject $fooBar
     *
     * @return void
     */
    public function fooBarMethod(ArrayObject $fooBar)
    {
        $foo = $fooBar;
    }
}
