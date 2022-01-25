<?php

namespace PyzTest\Yves\Availability;

use Codeception\Actor;

class AvailabilityPresentationTester extends Actor
{
    use _generated\AvailabilityPresentationTesterActions;
}
-----
<?php

namespace PyzTest\Yves\Availability;

use Codeception\Actor;

class AvailabilityControllerTester extends Actor
{
    use \PyzTest\Yves\Availability\_generated\AvailabilityControllerTesterActions;
}
