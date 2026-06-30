<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Trees\Branding\Actions\GetHelpAction;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Repo\App\Process\Models\Workflow;
use Repo\App\Process\Support\WorkflowGraph;

class WorkflowPreview extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Share;

    protected string $view = 'filament.pages.workflow-preview';

    public ?string $mermaid = null;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         GetHelpAction::make(),
    //     ];
    // }

    public function mount(): void
    {
        // ✅ Load your workflow
        $workflow = Workflow::with(['nodes', 'edges'])
            ->where('key', 'asset_request_1781508751')
            ->firstOrFail();

        $this->mermaid = app(WorkflowGraph::class)
            ->toMermaid($workflow);
    }
}
