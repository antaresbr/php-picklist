<?php declare(strict_types=1);

use Antares\Picklist\Picklist;
use Antares\PickList\PicklistCollection;
use PHPUnit\Framework\TestCase;

final class PicklistsTest extends TestCase
{
    public function testPicklists()
    {
        $this->assertInstanceOf(PicklistCollection::class, picklists());

        $this->assertInstanceOf(Picklist::class, picklists('fruits'));
        $this->assertInstanceOf(Picklist::class, picklists('other/fruits'));
        $this->assertInstanceOf(Picklist::class, picklists()->get('brands'));

        $this->assertEquals(count(picklists()), 3);
        $this->assertEquals(picklists('fruits')->getLabel(2), 'banana');
        $this->assertEquals(picklists('other/fruits')->getLabel(2), 'orange');
        $this->assertEquals(picklists()->get('brands')->getLabel('c'), 'BMW');

        picklists()->delete('other/fruits');
        $this->assertEquals(count(picklists()), 2);
        $this->assertEquals(picklists('fruits')->getLabel(2), 'banana');
        $this->assertEquals(picklists('brands')->getLabel('c'), 'BMW');
    }
}
