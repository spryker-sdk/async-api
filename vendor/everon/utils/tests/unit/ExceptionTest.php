<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Tests\Unit;

use Everon\Component\Utils\Exception\AbstractException;
use Everon\Component\Utils\TestCase\MockeryTest;
use Everon\Component\Utils\Tests\Unit\Doubles\ExceptionMultipleParamsStub;
use Everon\Component\Utils\Tests\Unit\Doubles\ExceptionSingleParamStub;

class ExceptionTest extends MockeryTest
{

    /**
     * @expectedException \Everon\Component\Utils\Tests\Unit\Doubles\ExceptionSingleParamStub
     * @expectedExceptionMessage Lorem ipsum: "%s"
     *
     * @throws ExceptionSingleParamStub
     *
     * @return void
     */
    public function test_exception_message()
    {
        throw new ExceptionSingleParamStub();
    }

    /**
     * @expectedException \Everon\Component\Utils\Tests\Unit\Doubles\ExceptionSingleParamStub
     * @expectedExceptionMessage Lorem ipsum: "foobar"
     *
     * @throws ExceptionSingleParamStub
     *
     * @return void
     */
    public function test_exception_message_with_param()
    {
        throw new ExceptionSingleParamStub('foobar');
    }

    /**
     * @expectedException \Everon\Component\Utils\Tests\Unit\Doubles\ExceptionMultipleParamsStub
     * @expectedExceptionMessage Multiple Lorem ipsum: "foobar" in "fuzz" for "123"
     *
     * @throws ExceptionMultipleParamsStub
     *
     * @return void
     */
    public function test_exception_message_with_multiple_params()
    {
        throw new ExceptionMultipleParamsStub(['foobar', 'fuzz', 123]);
    }

    /**
     * @expectedException \Everon\Component\Utils\Tests\Unit\Doubles\ExceptionMultipleParamsStub
     * @expectedExceptionMessage Lorem ipsum: "foobar"
     *
     * @throws ExceptionMultipleParamsStub
     *
     * @return void
     */
    public function test_exception_message_with_exception_should_overwrite_message()
    {
        $AnotherException = new ExceptionSingleParamStub('foobar');
        throw new ExceptionMultipleParamsStub($AnotherException);
    }

    public function test_getErrorMessageFromException()
    {
        $AnotherException = new ExceptionSingleParamStub('foobar');
        $message = AbstractException::getErrorMessageFromException($AnotherException);

        $this->assertEquals('{Everon\Component\Utils\Tests\Unit\Doubles\ExceptionSingleParamStub} Lorem ipsum: "foobar"', $message);
    }

    public function test_get_to_string()
    {
        $AnotherException = new ExceptionSingleParamStub('foobar');
        $message = (string) $AnotherException;

        $this->assertEquals('{Everon\Component\Utils\Tests\Unit\Doubles\ExceptionSingleParamStub} Lorem ipsum: "foobar"', $message);
    }

}
