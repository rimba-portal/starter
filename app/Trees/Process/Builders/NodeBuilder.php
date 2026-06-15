<?php

namespace App\Trees\Process\Builders;

use Illuminate\Database\Eloquent\Builder;

class NodeBuilder extends Builder
{
    public function start(): self
    {
        return $this->where('type', 'start');
    }

    public function end(): self
    {
        return $this->where('type', 'end');
    }

    public function forRoles(array $roles): self
    {
        return $this->whereIn('role_name', $roles);
    }

    public function firstNodes(): self
    {
        return $this->whereDoesntHave('incomingEdges');
    }

    public function lastNodes(): self
    {
        return $this->whereDoesntHave('outgoingEdges');
    }
}