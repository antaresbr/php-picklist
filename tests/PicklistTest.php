<?php declare(strict_types=1);

use Antares\Picklist\Picklist;
use Antares\Picklist\PicklistException;
use PHPUnit\Framework\TestCase;

final class PicklistTest extends TestCase
{
    public function testPicklistDataDir()
    {
        if (!defined('PICKLIST_DATA')) {
            throw PicklistException::forNotDefinedDataDir();
        }

        $this->assertIsString(constant('PICKLIST_DATA'));
        $this->assertDirectoryExists(constant('PICKLIST_DATA'));
    }

    public function testFuitsPicklist()
    {
        $pl = new Picklist('fruits');

        $this->assertInstanceOf(Picklist::class, $pl);
        $this->assertEquals($pl->getId(), 'fruits');
        $this->assertIsArray($pl->getData());
        $this->assertEquals(count($pl), 4);
        $this->assertEquals($pl->getLabel(2), 'banana');
        $this->assertEquals($pl->getKey('grape'), 3);
    }

    public function testOtherFuitsPicklist()
    {
        $pl = new Picklist('other/fruits');

        $this->assertInstanceOf(Picklist::class, $pl);
        $this->assertEquals($pl->getId(), 'other/fruits');
        $this->assertIsArray($pl->toArray());
        $this->assertEquals(count($pl), 6);
        $this->assertEquals($pl->getLabel(2), 'orange');
        $this->assertEquals($pl->getKey('pear'), 3);
    }

    public function testBrandsPicklist()
    {
        $pl = new Picklist('brands');

        $this->assertInstanceOf(Picklist::class, $pl);
        $this->assertEquals($pl->getId(), 'brands');
        $this->assertIsArray($pl->getData());
        $this->assertEquals(count($pl), 5);
        $this->assertEquals($pl->getLabel('b'), 'Audi');
        $this->assertEquals($pl->getKey('bmw'), 'c');
    }

    public function testCitiesPicklist()
    {
        $pl = new Picklist('other/cities.usa.georgia');

        $this->assertInstanceOf(Picklist::class, $pl);
        $this->assertEquals($pl->getId(), 'other/cities.usa.georgia');
        $this->assertIsArray($pl->getData());
        $this->assertEquals(count($pl), 6);
        $this->assertEquals($pl->getLabel(1), 'Atlanta');
        $this->assertEquals($pl->getKey('Cairo'), 4);
    }
}
