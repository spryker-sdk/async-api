<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Orm\Zed\FooBar\Persistence\SpyFooBar;

class FooBar
{
    /**
     * @param \Orm\Zed\FooBar\Persistence\SpyFooBar $fooBar
     *
     * @return void
     */
    public function fooBarMethod(SpyFooBar $fooBar)
    {
        $foo = $fooBar;
    }
}
-----
<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Orm\Zed\FooBar\Persistence\SpyFooBar;

class FooBar
{
    /**
     * @param \Orm\Zed\FooBar\Persistence\SpyFooBar $fooBarEntity
     *
     * @return void
     */
    public function fooBarMethod(SpyFooBar $fooBarEntity)
    {
        $foo = $fooBarEntity;
    }
}
