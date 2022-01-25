<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CardScheme;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Issn;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Language;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Locale;
use Symfony\Component\Validator\Constraints\Luhn;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @deprecated This class will be removed.
 *
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class ConstraintsPlugin extends AbstractPlugin
{
    /**
     * @var int
     */
    public const MAXIMUM_LENGTH_CONSTRAINT = 255;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getMandatoryConstraints()
    {
        return [
            $this->createConstraintRequired(),
            $this->createConstraintNotBlank(),
            $this->createConstraintLength(['max' => static::MAXIMUM_LENGTH_CONSTRAINT]),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getRequiredConstraints()
    {
        return [
            $this->createConstraintRequired(),
            $this->createConstraintNotBlank(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    public function createConstraintNotBlank($options = null)
    {
        return new NotBlank($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Symfony\Component\Validator\Constraints\Blank
     */
    public function createConstraintBlank()
    {
        return new Blank();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotNull
     */
    public function createConstraintNotNull($options = null)
    {
        return new NotNull($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\IsNull
     */
    public function createConstraintNull($options = null)
    {
        return new IsNull($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\IsTrue
     */
    public function createConstraintTrue($options = null)
    {
        return new IsTrue($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\IsFalse
     */
    public function createConstraintFalse($options = null)
    {
        return new IsFalse($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Type
     */
    public function createConstraintType($options = null)
    {
        return new Type($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Email
     */
    public function createConstraintEmail($options = null)
    {
        return new Email($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    public function createConstraintLength($options = null)
    {
        return new Length($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Url
     */
    public function createConstraintUrl($options = null)
    {
        return new Url($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    public function createConstraintRegex($options = null)
    {
        return new Regex($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Ip
     */
    public function createConstraintIp($options = null)
    {
        return new Ip($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Uuid
     */
    public function createConstraintUuid($options = null)
    {
        return new Uuid($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Range
     */
    public function createConstraintRange($options = null)
    {
        return new Range($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\EqualTo
     */
    public function createConstraintEqualTo($options = null)
    {
        return new EqualTo($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotEqualTo
     */
    public function createConstraintNotEqualTo($options = null)
    {
        return new NotEqualTo($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\IdenticalTo
     */
    public function createConstraintIdenticalTo($options = null)
    {
        return new IdenticalTo($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotIdenticalTo
     */
    public function createConstraintNotIdenticalTo($options = null)
    {
        return new NotIdenticalTo($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\LessThan
     */
    public function createConstraintLessThan($options = null)
    {
        return new LessThan($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\LessThanOrEqual
     */
    public function createConstraintLessThanOrEqual($options = null)
    {
        return new LessThanOrEqual($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\GreaterThan
     */
    public function createConstraintGreaterThan($options = null)
    {
        return new GreaterThan($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\GreaterThanOrEqual
     */
    public function createConstraintGreaterThanOrEqual($options = null)
    {
        return new GreaterThanOrEqual($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Date
     */
    public function createConstraintDate($options = null)
    {
        return new Date($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\DateTime
     */
    public function createConstraintDateTime($options = null)
    {
        return new DateTime($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Time
     */
    public function createConstraintTime($options = null)
    {
        return new Time($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Choice
     */
    public function createConstraintChoice($options = null)
    {
        return new Choice($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    public function createConstraintCollection($options = null)
    {
        return new Collection($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Count
     */
    public function createConstraintCount($options = null)
    {
        return new Count($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Language
     */
    public function createConstraintLanguage($options = null)
    {
        return new Language($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Locale
     */
    public function createConstraintLocale($options = null)
    {
        return new Locale($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Country
     */
    public function createConstraintCountry($options = null)
    {
        return new Country($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\File
     */
    public function createConstraintFile($options = null)
    {
        return new File($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Image
     */
    public function createConstraintImage($options = null)
    {
        return new Image($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\CardScheme
     */
    public function createConstraintCardScheme($options = null)
    {
        return new CardScheme($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Currency
     */
    public function createConstraintCurrency($options = null)
    {
        return new Currency($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Luhn
     */
    public function createConstraintLuhn($options = null)
    {
        return new Luhn($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Iban
     */
    public function createConstraintIban($options = null)
    {
        return new Iban($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Isbn
     */
    public function createConstraintIsbn($options = null)
    {
        return new Isbn($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Issn
     */
    public function createConstraintIssn($options = null)
    {
        return new Issn($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Callback
     */
    public function createConstraintCallback($options = null)
    {
        return new Callback($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Expression
     */
    public function createConstraintExpression($options = null)
    {
        return new Expression($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\All
     */
    public function createConstraintAll($options = null)
    {
        return new All($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Security\Core\Validator\Constraints\UserPassword
     */
    public function createConstraintUserPassword($options = null)
    {
        return new UserPassword($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Valid
     */
    public function createConstraintValid($options = null)
    {
        return new Valid($options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\Validator\Constraints\Required
     */
    public function createConstraintRequired($options = null)
    {
        return new Required($options);
    }
}
