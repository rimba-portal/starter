<?php

declare(strict_types=1);

namespace App\Trees\Process\Builders;

use Illuminate\Database\Eloquent\Builder;

class WorkflowBuilder extends Builder
{
    public function byKey(string $key): self
    {
        return $this->where('key', $key);
    }

    public function withNodes(): self
    {
        return $this->with('nodes');
    }

    public function withFullGraph(): self
    {
        return $this->with(['nodes', 'edges']);
    }
}
