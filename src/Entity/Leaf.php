<?php

namespace App\Entity;

class Leaf
{
    private int $size;
    private int $position;

    public function __construct(int $position = 0)
    {
        $this->size = 1;
        $this->position = $position;
    }

    public function grow(): self
    {
        $this->size++;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function __serialize()
    {
        return [
            'size' => $this->size,
            'position' => $this->position,
        ];
    }

    public function __unserialize(array $data)
    {
        $this->size = $data['size'];
        $this->position = $data['position'];
    }

    public function toString()
    {
        return sprintf('Leaf (size: %d, position: %d)', $this->size, $this->position);
    }

    public function toArray()
    {
        return [
            'size' => $this->size,
            'position' => $this->position,
        ];
    }
}
