<?php

namespace App\Entity;

use App\Entity\Leaf;

class Branch
{
    private int $length;
    private array $leaves;
    private array $nextBranches;

    public function __construct(int $length = 0)
    {
        $this->length = $length;
        $this->leaves = [];
        $this->nextBranches = [];
    }

    public function grow(): self
    {
        if ($this->length < 8) {
            $this->length++;
        }

        if ($this->length >= 8 && empty($this->nextBranches)) {
            $this->split();
        }

        if (!empty($this->nextBranches)) {
            foreach ($this->nextBranches as $branch) {
                $branch->grow();
            }
        }

        $this->updateLeaves();

        return $this;
    }

    public function updateLeaves(): self
    {
        foreach ($this->leaves as $leaf) {
            if ($leaf->getSize() >= 5) {
                $this->removeLeaf($leaf);
            }
        }

        if (rand(0, 1)) {
            $this->addLeaf(new Leaf(rand(1, $this->length - 1)));
        }

        foreach ($this->leaves as $leaf) {
            $leaf->grow();
        }

        return $this;
    }

    public function split(): void
    {
        [$leftBranch, $rightBranch] = [new Branch(), new Branch()];

        $this->nextBranches = [$leftBranch, $rightBranch];
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLeaves(): array
    {
        return $this->leaves;
    }

    public function setLeaves(array $leaves): self
    {
        $this->leaves = $leaves;

        return $this;
    }

    public function addLeaf(Leaf $leaf): self
    {
        $this->leaves[] = $leaf;

        return $this;
    }

    public function removeLeaf(Leaf $leaf): self
    {
        $key = array_search($leaf, $this->leaves, true);
        if (null !== $key) {
            array_splice($this->leaves, $key, 1);
        }

        return $this;
    }

    public function getNextBranches(): array
    {
        return $this->nextBranches;
    }

    public function setNextBranches(array $nextBranches): self
    {
        $this->nextBranches = $nextBranches;

        return $this;
    }

    public function __serialize()
    {
        return [
            'length' => $this->length,
            'leaves' => $this->leaves,
            'nextBranches' => $this->nextBranches,
        ];
    }

    public function __unserialize(array $data)
    {
        $this->length = $data['length'];
        $this->leaves = $data['leaves'];
        $this->nextBranches = $data['nextBranches'];
    }

    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'leaves' => array_map(fn (Leaf $leaf) => $leaf->toArray(), $this->leaves),
            'nextBranches' => array_map(fn (Branch $branch) => $branch->toArray(), $this->nextBranches),
        ];
    }
}
