<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'type',
        'price',
        'previous_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'previous_price' => 'decimal:2',
    ];

    public function isPriceIncreased(): bool
    {
        if ($this->previous_price === null) {
            return false;
        }
        return $this->price > $this->previous_price;
    }

    public function isPriceDecreased(): bool
    {
        if ($this->previous_price === null) {
            return false;
        }
        return $this->price < $this->previous_price;
    }

    public function isPriceUnchanged(): bool
    {
        if ($this->previous_price === null) {
            return true;
        }
        return $this->price == $this->previous_price;
    }

    public function getPriceChangeStatus(): string
    {
        if ($this->isPriceIncreased()) {
            return 'up';
        }
        if ($this->isPriceDecreased()) {
            return 'down';
        }
        return 'unchanged';
    }

    public function getPriceDifference(): float
    {
        if ($this->previous_price === null) {
            return 0;
        }
        return round($this->price - $this->previous_price, 2);
    }

    public function getPriceChangePercentage(): float
    {
        if ($this->previous_price === null || $this->previous_price == 0) {
            return 0;
        }
        return round((($this->price - $this->previous_price) / $this->previous_price) * 100, 2);
    }
}
