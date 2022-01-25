suites:
    Presentation:
        path: Presentation
        class_name: AvailabilityPresentationTester
        modules:
            enabled:
                - Asserts
                - \PyzTest\Shared\Testify\Helper\Environment
                - \SprykerTest\Shared\Propel\Helper\ConnectionHelper
                - \SprykerTest\Shared\Testify\Helper\LocatorHelper:
                    projectNamespaces: ['Pyz']
                - \SprykerTest\Shared\Testify\Helper\DependencyHelper
                - \SprykerTest\Shared\Config\Helper\ConfigInit
                - \PyzTest\Yves\Checkout\Helper\CheckoutHelper
                - \PyzTest\Yves\Customer\Helper\CustomerHelper
                - \SprykerTest\Shared\Propel\Helper\PropelInstallHelper
                - WebDriver:
                    url: ''
                    browser: chrome
                    window_size: 1920x1080
                    host: 0.0.0.0
                    restart: false
                - \SprykerTest\Shared\Testify\Helper\DataCleanupHelper
                - \SprykerTest\Shared\Application\Helper\YvesHelper
                - \SprykerTest\Shared\Application\Helper\ZedHelper
                - \SprykerTest\Zed\SalesOrderThreshold\Helper\SalesOrderThresholdHelper
-----
suites:
    Controller:
        path: Controller
        class_name: AvailabilityControllerTester
        modules:
            enabled:
                - \SprykerTest\Shared\Propel\Helper\ConnectionHelper
                -
                    \SprykerTest\Shared\Testify\Helper\LocatorHelper:
                        projectNamespaces:
                            - Pyz
                - \SprykerTest\Shared\Testify\Helper\DependencyHelper
                - \SprykerTest\Shared\Config\Helper\ConfigInit
                - \PyzTest\Yves\Checkout\Helper\CheckoutHelper
                - \PyzTest\Yves\Customer\Helper\CustomerHelper
                - \SprykerTest\Shared\Propel\Helper\PropelInstallHelper
                - \SprykerTest\Shared\Testify\Helper\DataCleanupHelper
                - \SprykerTest\Shared\Application\Helper\YvesHelper
                - \SprykerTest\Shared\Application\Helper\ZedHelper
                - \SprykerTest\Zed\SalesOrderThreshold\Helper\SalesOrderThresholdHelper
                - \SprykerTest\Shared\Testify\Helper\Environment
                - \SprykerTest\Shared\Testify\Helper\ConfigHelper
                - \PyzTest\Yves\Testify\Helper\BootstrapHelper
