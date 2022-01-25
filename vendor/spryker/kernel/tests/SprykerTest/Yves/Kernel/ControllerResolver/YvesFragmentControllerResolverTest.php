<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\ControllerResolver;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group ControllerResolver
 * @group YvesFragmentControllerResolverTest
 * Add your own group annotations below this line
 */
class YvesFragmentControllerResolverTest extends Unit
{
    /**
     * @dataProvider getController
     *
     * @param string $controller
     * @param string $expectedServiceName
     *
     * @return void
     */
    public function testCreateController(string $controller, string $expectedServiceName): void
    {
        $request = $this->getRequest($controller);
        $controllerResolver = $this->getFragmentControllerProvider($request);

        $result = $controllerResolver->getController($request);

        $this->assertSame($expectedServiceName, $request->attributes->get('_controller'));
        $this->assertIsCallable($result);
    }

    /**
     * @return array
     */
    public function getController(): array
    {
        return [
            ['index/index/index', static::class . '::indexAction'],
            ['/index/index/index', static::class . '::indexAction'],
            ['Index/Index/Index', static::class . '::indexAction'],
            ['/Index/Index/Index', static::class . '::indexAction'],
            ['foo-bar/baz-bat/zip-zap', static::class . '::zipZapAction'],
        ];
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return void
     */
    public function __call(string $name, array $arguments = []): void
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver
     */
    protected function getFragmentControllerProvider(Request $request): YvesFragmentControllerResolver
    {
        $controllerResolverMock = $this->getMockBuilder(YvesFragmentControllerResolver::class)
            ->setMethods(['resolveController', 'getCurrentRequest'])
            ->disableOriginalConstructor()
            ->getMock();

        $controllerResolverMock->method('resolveController')->willReturn($this);
        $controllerResolverMock->method('getCurrentRequest')->willReturn($request);

        return $controllerResolverMock;
    }

    /**
     * @param string $controller
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest(string $controller): Request
    {
        return new Request([], [], ['_controller' => $controller]);
    }
}
