<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Orm\Zed\FooBar\Persistence\SpyFooBarQuery;

class FooBar
{
    /**
     * @param \Orm\Zed\FooBar\Persistence\SpyFooBarQuery $fooBar
     *
     * @return void
     */
    public function fooBarMethod(SpyFooBarQuery $fooBar)
    {
        $foo = $fooBar;
    }
}
-----
<?php

namespace Pyz\Zed\FooBar\Business\FooBar;

use Orm\Zed\FooBar\Persistence\SpyFooBarQuery;

class FooBar
{
    /**
     * @param \Orm\Zed\FooBar\Persistence\SpyFooBarQuery $fooBarQuery
     *
     * @return void
     */
    public function fooBarMethod(SpyFooBarQuery $fooBarQuery)
    {
        $foo = $fooBarQuery;
    }
}
