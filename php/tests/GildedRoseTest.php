<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame('foo', $items[0]->name);
    }

    public function testNoNegativeValues(): void
    {
        $items = [new Item('Camera', 50, 2)];
        $gildedRose = new GildedRose($items);

        for ($day = 1; $day < 3; $day++) {
            $gildedRose->updateQuality();
        }

        $this->assertGreaterThanOrEqual(
            '0',
            $items[0]->quality,
            'Quality should never be negative'
        );
    }

    public function testAgedBrie(): void
    {
        $items = [new Item('Aged Brie', 50, 2)];
        $gildedRose = new GildedRose($items);

        for ($day = 1; $day < 3; $day++) {
            $previousQuality = $items[0]->quality;
            $gildedRose->updateQuality();
            $this->assertSame(
                $previousQuality + 1,
                $items[0]->quality,
                'Aged Brie quality should increase'
            );
        }
    }

    public function testMaxQuality(): void
    {
        $items = [new Item('Aged Brie', 50, 48)];
        $gildedRose = new GildedRose($items);

        for ($day = 1; $day < 3; $day++) {
            $gildedRose->updateQuality();
        }
        $this->assertLessThanOrEqual(
            50,
            $items[0]->quality,
            'Quality should never be greater than 50'
        );
    }

    public function testSulfurasQuality(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 0, 80)];
        $gildedRose = new GildedRose($items);

        for ($day = 1; $day < 3; $day++) {
            $gildedRose->updateQuality();
            $this->assertSame(
                80,
                $items[0]->quality,
                'Sulfuras quality should never change'
            );
        }
    }

    public function testBackstagePass(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 25),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 25),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 25),
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();
        $this->assertSame(
            26,
            $items[0]->quality,
            'Backstage pass quality should increase by 1 when there are more than 10 days'
        );
        $this->assertSame(
            27,
            $items[1]->quality,
            'Backstage pass quality should increase by 2 when there are 10 days or less'
        );
        $this->assertSame(
            28,
            $items[2]->quality,
            'Backstage pass quality should increase by 3 when there are 5 days or less'
        );
    }

    public function testConjured(): void
    {
        $items = [new Item('Conjured Mana Cake', 15, 40)];
        $gildedRose = new GildedRose($items);

        for ($day = 1; $day <= 3; $day++) {
            $gildedRose->updateQuality();
        }
        $this->assertSame(
            34,
            $items[0]->quality,
            'Conjured quality should decrease by 2'
        );
    }
}
