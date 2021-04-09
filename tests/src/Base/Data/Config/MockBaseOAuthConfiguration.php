<?php
/**
 * Created by PhpStorm.
 * User: aaflalo
 * Date: 18-06-01
 * Time: 14:28.
 */

namespace ZEROSPAM\Framework\SDK\Test\Base\Data\Config;

use ZEROSPAM\Framework\SDK\Config\OAuth\BaseOAuthConfiguration;
use ZEROSPAM\Framework\SDK\Test\Base\Data\Provider\TestProvider;

/**
 * Class MockOAuthConfiguration
 *
 * Fake configuration with a TestProvider
 *
 * @package ZEROSPAM\Framework\SDK\Test\Base\Data\Config
 */
class MockBaseOAuthConfiguration extends BaseOAuthConfiguration
{
    /**
     * Class to use for the provider.
     *
     * @return string
     */
    protected function providerClass(): string
    {
        return TestProvider::class;
    }
}
