<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Repo\App\Process\Models\Workflow;
use Repo\App\Process\Models\WorkflowEdge;
use Repo\App\Process\Models\WorkflowNode;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create workflow
        $workflow = Workflow::create([
            'name' => 'Asset Request Workflow',
            'key' => 'asset_request',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NODES
        |--------------------------------------------------------------------------
        */

        $start = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Start',
            'type' => 'start',
        ]);

        $submit = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Form Submission',
            'type' => 'process',
            'config' => [
                'assignment' => [
                    'type' => 'job_position',
                    'value' => 'employee',
                ],
            ],
        ]);

        $costDecision = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Chargeable to Cost Center?',
            'type' => 'decision',
        ]);

        $costApproval = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Cost Center Approval',
            'type' => 'process',
            'config' => [
                'assignment' => [
                    'type' => 'job_position',
                    'value' => 'cost_center_owner',
                ],
            ],
        ]);

        $serviceApproval = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Service Provider Approval',
            'type' => 'process',
            'config' => [
                'assignment' => [
                    'type' => 'job_position',
                    'value' => 'service_provider',
                ],
            ],
        ]);

        $sourcing = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Sourcing',
            'type' => 'process',
        ]);

        $stockDecision = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'In Stock?',
            'type' => 'decision',
        ]);

        $inventory = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Inventorizing',
            'type' => 'process',
        ]);

        $preparing = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Preparing',
            'type' => 'process',
        ]);

        $fulfillment = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Fulfillment',
            'type' => 'process',
        ]);

        $delivery = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'Delivering',
            'type' => 'process',
        ]);

        $end = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'name' => 'End',
            'type' => 'end',
        ]);

        /*
        |--------------------------------------------------------------------------
        | EDGES
        |--------------------------------------------------------------------------
        */

        WorkflowEdge::insert([

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $start->id,
                'to_node_id' => $submit->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $submit->id,
                'to_node_id' => $costDecision->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costDecision->id,
                'to_node_id' => $costApproval->id,
                'label' => 'Yes',
                'condition' => json_encode([
                    'field' => 'chargeable',
                    'operator' => '=',
                    'value' => true,
                ]),
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costDecision->id,
                'to_node_id' => $serviceApproval->id,
                'label' => 'No',
                'condition' => json_encode([
                    'field' => 'chargeable',
                    'operator' => '=',
                    'value' => false,
                ]),
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costApproval->id,
                'to_node_id' => $serviceApproval->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $serviceApproval->id,
                'to_node_id' => $sourcing->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $sourcing->id,
                'to_node_id' => $stockDecision->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $stockDecision->id,
                'to_node_id' => $fulfillment->id,
                'label' => 'Yes',
                'condition' => json_encode([
                    'field' => 'in_stock',
                    'operator' => '=',
                    'value' => true,
                ]),
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $stockDecision->id,
                'to_node_id' => $inventory->id,
                'label' => 'No',
                'condition' => json_encode([
                    'field' => 'in_stock',
                    'operator' => '=',
                    'value' => false,
                ]),
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $inventory->id,
                'to_node_id' => $preparing->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $preparing->id,
                'to_node_id' => $delivery->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $fulfillment->id,
                'to_node_id' => $delivery->id,
                'label' => null,
                'condition' => null,
            ],

            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $delivery->id,
                'to_node_id' => $end->id,
                'label' => null,
                'condition' => null,
            ],
        ]);
    }
}
