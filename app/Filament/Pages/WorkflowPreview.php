<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Trees\Process\Models\Workflow;
use App\Trees\Process\Support\WorkflowGraph;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use App\Filament\Actions\GetHelpAction;

class WorkflowPreview extends Page
{

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Share;
    protected string $view = 'filament.pages.workflow-preview';

    public ?string $mermaid = null;
    protected function getHeaderActions(): array
    {
        return [
           \App\Trees\Branding\Actions\GetHelpAction::make(),
        ];
    }
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
