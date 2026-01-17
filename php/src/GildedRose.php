<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $key => $item) {
            switch ($item->name) {
                case 'Aged Brie':
                    $this->updateAgedBrie($this->items[$key]);
                    break;
                case 'Backstage passes to a TAFKAL80ETC concert':
                    $this->updateBackstagePasses($this->items[$key]);
                    break;
                case str_contains($item->name, 'Conjured'):
                    $this->updateConjured($this->items[$key]);
                    break;
                case 'Sulfuras, Hand of Ragnaros':
                    break;
                default:
                    $this->updateNormalItem($this->items[$key]);
                    break;
            }
        }
    }

    private function updateAgedBrie($item): void
    {
        $increase = 1;

        if ($item->sellIn < 0) {
            $increase++;
        }

        $item->quality = min(50, $item->quality + $increase);
    }

    private function updateBackstagePasses($item): void
    {
        if ($item->sellIn < 0) {
            $item->quality = 0;
            return;
        }

        $increase = 1;

        if ($item->sellIn < 11) {
            $increase++;
        }

        if ($item->sellIn < 6) {
            $increase++;
        }

        $item->quality = min(50, $item->quality + $increase);
    }

    private function updateConjured($item): void
    {
        $decrease = 2;

        if ($item->sellIn < 0) {
            $decrease += 2;
        }

        $item->quality = max(0, $item->quality - $decrease);
    }

    private function updateNormalItem($item): void
    {
        $decrease = 1;

        if ($item->sellIn < 0) {
            $decrease++;
        }

        $item->quality = max(0, $item->quality - $decrease);
    }
}
