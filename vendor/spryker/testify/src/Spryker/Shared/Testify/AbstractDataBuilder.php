<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify;

use Faker\Factory;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Testify\Exception\DependencyNotDefinedException;
use Spryker\Shared\Testify\Exception\FieldNotDefinedException;
use Spryker\Shared\Testify\Exception\RuleNotDefinedException;

abstract class AbstractDataBuilder
{
    /**
     * @var \Faker\Generator
     */
    protected static $faker;

    /**
     * @var array<string, string>
     */
    protected $defaultRules = [];

    /**
     * @var array<string, string>
     */
    protected $rules = [];

    /**
     * @var array<string, string>
     */
    protected $dependencies = [];

    /**
     * @var array
     */
    protected $nestedBuilders = [];

    /**
     * @var array<string, mixed>
     */
    protected $seedData = [];

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function getTransfer();

    /**
     * @param string $builder
     *
     * @throws \Exception
     *
     * @return \Spryker\Shared\Testify\AbstractDataBuilder
     */
    abstract protected function locateDataBuilder($builder);

    /**
     * @param array<string, mixed> $seed
     */
    public function __construct($seed = [])
    {
        $this->seedData = $seed;
        $this->rules = $this->defaultRules;

        if (static::$faker === null) {
            static::$faker = Factory::create();
        }
    }

    /**
     * Removes all rules
     *
     * @return $this
     */
    public function makeEmpty()
    {
        $this->rules = [];

        return $this;
    }

    /**
     * @return $this
     */
    public function resetData()
    {
        $this->seedData = [];

        return $this;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return $this
     */
    public function seed(array $seed = [])
    {
        $this->seedData += $seed;

        return $this;
    }

    /**
     * @param array|string $rules
     *
     * @throws \Spryker\Shared\Testify\Exception\RuleNotDefinedException
     *
     * @return $this
     */
    public function with($rules)
    {
        if (!is_array($rules)) {
            $rules = [];
        }
        foreach ($rules as $rule) {
            if (!isset($this->defaultRules[$rule])) {
                throw new RuleNotDefinedException(sprintf('No rule for "%s" defined', $rule));
            }
            $this->rules[$rule] = $this->defaultRules[$rule];
        }

        return $this;
    }

    /**
     * @param array|string $rules
     *
     * @return $this
     */
    public function except($rules)
    {
        if (!is_array($rules)) {
            $rules = [];
        }
        foreach ($rules as $rule) {
            unset($this->rules[$rule]);
        }

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function build()
    {
        $transfer = $this->getTransfer();
        $transfer->fromArray($this->generateFields());
        $this->seedData = array_merge($this->getScalarValues($transfer), $this->seedData);

        $this->generateDependencies($transfer);
        $transfer->fromArray($this->seedData, true);

        return $transfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array
     */
    private function getScalarValues(AbstractTransfer $transfer)
    {
        return array_filter($transfer->toArray(false), 'is_scalar');
    }

    /**
     * @return array
     */
    protected function generateFields()
    {
        $data = [];
        foreach ($this->rules as $field => $rule) {
            $data[$field] = $this->generateFromRule($rule);
        }

        return $data;
    }

    /**
     * @param string $field
     * @param array $override
     * @param bool $randomize
     *
     * @throws \Spryker\Shared\Testify\Exception\FieldNotDefinedException
     *
     * @return void
     */
    protected function buildDependency($field, $override = [], $randomize = false)
    {
        if (!isset($this->dependencies[$field])) {
            throw new FieldNotDefinedException(sprintf('Field "%s" not defined in dependencies list', $field));
        }
        $builder = $this->locateDataBuilder($this->dependencies[$field]);
        $builder->seed($override);
        $this->addDependencyBuilder($field, $builder, $randomize);
    }

    /**
     * @param string $field
     * @param \Spryker\Shared\Testify\AbstractDataBuilder $builder
     * @param bool $randomize
     *
     * @return void
     */
    protected function addDependencyBuilder($field, $builder, $randomize)
    {
        $this->nestedBuilders[] = [$field, $builder, $randomize];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @throws \Spryker\Shared\Testify\Exception\DependencyNotDefinedException
     *
     * @return void
     */
    protected function generateDependencies(AbstractTransfer $transfer)
    {
        foreach ($this->nestedBuilders as $builderInfo) {
            [$name, $dependencyBuilder, $randomize] = $builderInfo;

            // add currently generated values
            if (!$randomize) {
                $dependencyBuilder->seed($this->seedData);
            }
            $nestedTransfer = $dependencyBuilder->build();

            // reuse generated values from nested objects
            if (!$randomize) {
                $this->seedData = array_merge($this->seedData, $dependencyBuilder->getSeedData());
            }

            if (method_exists($transfer, 'add' . $name)) {
                /** @var callable $callable */
                $callable = [$transfer, 'add' . $name];
                call_user_func($callable, $nestedTransfer);

                continue;
            }

            if (method_exists($transfer, 'set' . $name)) {
                /** @var callable $callable */
                $callable = [$transfer, 'set' . $name];
                call_user_func($callable, $nestedTransfer);

                continue;
            }

            throw new DependencyNotDefinedException(sprintf('Dependency "%s" not defined in "%s"', $name, static::class));
        }
    }

    /**
     * @SuppressWarning(PHPMD.EvalSniff)
     *
     * @param string $rule
     *
     * @return string|bool
     */
    protected function generateFromRule($rule)
    {
        if (strpos($rule, '=') === 0) {
            return substr($rule, 1);
        }

        // @codingStandardsIgnoreStart
        return eval("return static::\$faker->$rule;");
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return array<string, mixed>
     */
    public function getSeedData()
    {
        return $this->seedData;
    }
}
