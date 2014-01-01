<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30/12/13
 * Time: 13:15
 */

namespace vascowhite\tests;
use vascowhite\Time\TimeValue;

require_once __DIR__ . '/../vendor/autoload.php';

class TimeValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeValue
     */
    private $testTimeValue;

    /**
     * @var \DateTime
     */
    private $testDateTime;

    /**
     * @var Int
     */
    private $testSeconds;

    public function setUp()
    {
        $this->testDateTime = new \DateTime();
        $this->testTimeValue = new TimeValue();
        $this->testSeconds = (int)$this->testDateTime->format('h') * TimeValue::SECONDS_IN_HOUR
            + (int)$this->testDateTime->format('i') * TimeValue::SECONDS_IN_MINUTE
            + (int)$this->testDateTime->format('s');
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf('Vascowhite\Time\TimeValue', $this->testTimeValue, "Could not instantiate TimeValue");
    }

    public function testCanGetSeconds()
    {
        $this->assertEquals($this->testSeconds, $this->testTimeValue->getSeconds(), "Cannot get seconds");
        $testTimeValue = new TimeValue('12:00:00');
        $this->assertEquals(43200, $testTimeValue->getSeconds(), "Cannot get seconds");
        $testTimeValue = new TimeValue('23:59:59');
        $this->assertEquals(86399, $testTimeValue->getSeconds(), "Cannot get seconds");
        $testTimeValue = new TimeValue('12:00:00');
        $this->assertEquals(43200, $testTimeValue->getSeconds(), "Cannot get seconds");
    }

    public function testCanGetTime()
    {
        $this->assertEquals($this->testDateTime->format('h:i:s'), $this->testTimeValue->getTime(), "Time string not valid");
        $timeString = '08:05:22';
        $testTimeValue = new TimeValue($timeString);
        $this->assertEquals($timeString, $testTimeValue->getTime(), "Time string not valid");
        $timeString = '23:59:59';
        $testTimeValue = new TimeValue($timeString);
        $this->assertEquals($timeString, $testTimeValue->getTime(), "Time string not valid");
    }

    public function testCanAdd()
    {
        $testTimeValue = new TimeValue('00:00:100');
        $this->assertEquals(200, $testTimeValue->add(new TimeValue('00:00:100'))->getSeconds(), "Can't add up!");
    }

    public function testCanSubtract()
    {
        $testTimeValue = new TimeValue('00:00:100');
        $this->assertEquals(0, $testTimeValue->sub(new TimeValue('00:00:100'))->getSeconds(), "Can't subtract!");

        $testTimeValue = new TimeValue('00:00:100');
        $this->assertEquals(0, $testTimeValue->sub(new TimeValue('00:00:200'))->getSeconds(), "Can't subtract!");

        $testTimeValue = new TimeValue('00:00:200');
        $this->assertEquals(100, $testTimeValue->sub(new TimeValue('00:00:100'))->getSeconds(), "Can't subtract!");
    }

    public function testAddAndSubtractReturnCorrectFormat()
    {
        $testTimeValue = new TimeValue('01:30');
        $this->assertEquals('02:00:00', $testTimeValue->add(new TimeValue('00:30'))->getTime());

        $testTimeValue = new TimeValue('01:30');
        $this->assertEquals('01:00:00', $testTimeValue->sub(new TimeValue('00:30'))->getTime());
    }

    public function testCanCompareEquals()
    {
        $testTimeValue = new TimeValue('12');
        $this->assertTrue($testTimeValue->compare(new TimeValue('12'), '='), 'Cannot compare equals.');
        $this->assertFalse($testTimeValue->compare(new TimeValue('12:01'), '='), 'Cannot compare equals.');
    }

    public function testCanCompareGreaterThan()
    {
        $testTimeValue = new TimeValue('12');
        $this->assertTrue($testTimeValue->compare(new TimeValue('12:30'), '>'), 'Cannot compare equals.');
        $this->assertFalse($testTimeValue->compare(new TimeValue('12'), '>'), 'Cannot compare equals.');
        $this->assertFalse($testTimeValue->compare(new TimeValue('11'), '>'), 'Cannot compare equals.');
    }

    public function testCanCompareLessThan()
    {
        $testTimeValue = new TimeValue('12');
        $this->assertTrue($testTimeValue->compare(new TimeValue('11:30'), '<'), 'Cannot compare equals.');
        $this->assertFalse($testTimeValue->compare(new TimeValue('12'), '<'), 'Cannot compare equals.');
        $this->assertFalse($testTimeValue->compare(new TimeValue('12:30'), '<'), 'Cannot compare equals.');
    }

    public function testCompareReturnsFalseOnWrongSymbol()
    {
        $testTimeValue = new TimeValue('12');
        $this->assertFalse($testTimeValue->compare(new TimeValue('11:30'), '*'), 'Cannot compare equals.');
    }
} 