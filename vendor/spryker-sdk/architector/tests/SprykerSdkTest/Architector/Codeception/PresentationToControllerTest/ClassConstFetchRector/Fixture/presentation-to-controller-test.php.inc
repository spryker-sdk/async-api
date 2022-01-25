<?php

namespace PyzTest\Yves\Availability\Presentation;

use PyzTest\Yves\Availability\AvailabilityPresentationTester;
use PyzTest\Yves\Availability\SomeOtherUse;

class AvailabilityAddToCartCest
{
    /**
     * @param \PyzTest\Yves\Availability\AvailabilityPresentationTester $i
     *
     * @return void
     */
    public function testMethod(AvailabilityPresentationTester $i)
    {
        $i->amOnPage(AvailabilityPresentationTester::class);
        $i->seeInSource('Something');
    }
}
-----
<?php

namespace PyzTest\Yves\Availability\Presentation;

use PyzTest\Yves\Availability\AvailabilityPresentationTester;
use PyzTest\Yves\Availability\SomeOtherUse;

class AvailabilityAddToCartCest
{
    /**
     * @param \PyzTest\Yves\Availability\AvailabilityPresentationTester $i
     *
     * @return void
     */
    public function testMethod(AvailabilityPresentationTester $i)
    {
        $i->amOnPage(\PyzTest\Yves\Availability\AvailabilityControllerTester::class);
        $i->seeInSource('Something');
    }
}
