<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Tests\Unit\Doubles;

use Everon\Component\Utils\Text\UnderscoreToCamel;
use Everon\Component\Utils\Text\CamelToUnderscore;

class TextStub
{

    use CamelToUnderscore;
    use UnderscoreToCamel;

    public function camelToUnderscore($text)
    {
        return $this->textCamelToUnderscore($text);
    }

    public function underscoreToCamel($text)
    {
        return $this->textUnderscoreToCamel($text);
    }

}
