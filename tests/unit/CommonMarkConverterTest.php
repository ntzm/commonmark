<?php

namespace League\CommonMark\Tests\Unit;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Extension\CommonMarkCoreExtension;
use PHPUnit\Framework\TestCase;

class CommonMarkConverterTest extends TestCase
{
    public function testEmptyConstructor()
    {
        $converter = new CommonMarkConverter();
        $expectedEnvironment = Environment::createCommonMarkEnvironment();

        $environment = $this->getEnvironmentFromConverter($converter);

        $this->assertCount(1, $environment->getExtensions());
        $this->assertInstanceOf(CommonMarkCoreExtension::class, $environment->getExtensions()[0]);
        $this->assertEquals($expectedEnvironment->getConfig(), $environment->getConfig());
    }

    public function testConfigOnlyConstructor()
    {
        $config = ['foo' => 'bar'];
        $converter = new CommonMarkConverter($config);

        $environment = $this->getEnvironmentFromConverter($converter);

        $this->assertCount(1, $environment->getExtensions());
        $this->assertInstanceOf(CommonMarkCoreExtension::class, $environment->getExtensions()[0]);
        $this->assertArrayHasKey('foo', $environment->getConfig());
    }

    public function testEnvironmentAndConfigConstructor()
    {
        $config = ['foo' => 'bar'];
        $mockEnvironment = $this->createMock(ConfigurableEnvironmentInterface::class);
        $mockEnvironment->expects($this->once())
            ->method('mergeConfig')
            ->with($config);

        $converter = new CommonMarkConverter($config, $mockEnvironment);

        $environment = $this->getEnvironmentFromConverter($converter);

        $this->assertSame($mockEnvironment, $environment);
    }

    /**
     * @param Converter $converter
     *
     * @return EnvironmentInterface
     */
    private function getEnvironmentFromConverter(Converter $converter)
    {
        /** @var DocParser $docParser */
        $docParser = $this->readAttribute($converter, 'docParser');

        return $docParser->getEnvironment();
    }
}
