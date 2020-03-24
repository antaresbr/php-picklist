<?php declare(strict_types=1);

use Antares\Picklist\Picklist;
use Antares\Picklist\PicklistCollection;
use PHPUnit\Framework\TestCase;

final class PicklistCollectionTest extends TestCase
{
    public function testPicklistCollection()
    {
        $plc = new PicklistCollection();

        $this->assertInstanceOf(PicklistCollection::class, $plc);

        $this->assertInstanceOf(Picklist::class, $plc->get('fruits'));
        $this->assertInstanceOf(Picklist::class, $plc->get('other/fruits'));
        $this->assertInstanceOf(Picklist::class, $plc->get('brands'));

        $this->assertEquals(count($plc), 3);
        $this->assertEquals($plc->get('fruits')->getLabel(2), 'banana');
        $this->assertEquals($plc->get('other/fruits')->getLabel(2), 'orange');
        $this->assertEquals($plc->get('brands')->getLabel('c'), 'BMW');

        $plc->delete('other/fruits');
        $this->assertEquals(count($plc), 2);
        $this->assertEquals($plc->get('fruits')->getLabel(2), 'banana');
        $this->assertEquals($plc->get('brands')->getLabel('c'), 'BMW');
    }
}
