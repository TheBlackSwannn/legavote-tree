<?php

namespace App\Entity;

use App\Entity\Branch;

class Tree
{
    private Branch $root;

    public function __construct()
    {
        $this->root = new Branch(5);
    }

    public function grow(): self
    {
        if ($this->getTreeHeight() >= 90) {
            return $this;
        }

        $this->root->grow();

        return $this;
    }

    public function getMetrics(): array
    {
        return [
            'tree_height' => $this->getTreeHeight(),
            'tree_size' => $this->getTreeSize(),
            'branch_number' => $this->getBranchCount(),
            'leaf_number' => $this->getLeafCount(),
        ];
    }

    public function getRoot(): Branch
    {
        return $this->root;
    }

    public function setRoot(Branch $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getTreeHeight(): int
    {
        $height = 0;

        $queue = [$this->root];

        while (!empty($queue)) {
            $branch = array_shift($queue);

            $height += $branch->getLength();

            if ($branch->getNextBranches()) {
                $queue[] = $branch->getNextBranches()[0];
            }
        }

        return $height;
    }

    public function getTreeSize(): int
    {
        $size = 0;

        $queue = [$this->root];

        while (!empty($queue)) {
            $branch = array_shift($queue);

            $size += $branch->getLength();

            foreach ($branch->getNextBranches() as $nextBranch) {
                $queue[] = $nextBranch;
            }
        }

        return $size;
    }

    public function getBranchCount(): int
    {
        $total = 0;

        $queue = [$this->root];

        while (!empty($queue)) {
            $branch = array_shift($queue);

            $total++;

            foreach ($branch->getNextBranches() as $nextBranch) {
                $queue[] = $nextBranch;
            }
        }

        return $total;
    }

    public function getLeafCount(): int
    {
        $total = 0;

        $queue = [$this->root];

        while (!empty($queue)) {
            $branch = array_shift($queue);

            $total += count($branch->getLeaves());

            foreach ($branch->getNextBranches() as $nextBranch) {
                $queue[] = $nextBranch;
            }
        }

        return $total;
    }

    public function __serialize()
    {
        return ['root' => $this->root];
    }

    public function __unserialize(array $data)
    {
        $this->root = $data['root'];
    }

    public function toArray(): array
    {
        return ['root' => $this->root->toArray()];
    }
}
