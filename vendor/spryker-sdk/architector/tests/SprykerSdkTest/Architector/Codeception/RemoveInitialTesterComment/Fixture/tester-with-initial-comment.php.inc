<?php

namespace Utils\Rector\Tests\Codeception\RemoveInitialTesterComment\Fixture;

class TesterWithInitialCommentTester
{
    use _generated\AuthRestApiTesterActions;

    /**
     * Define custom actions here
     */
}
-----
<?php

namespace Utils\Rector\Tests\Codeception\RemoveInitialTesterComment\Fixture;

class TesterWithInitialCommentTester
{
    use _generated\AuthRestApiTesterActions;
}
