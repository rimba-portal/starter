<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Trees\Process\Models\Workflow;
use App\Trees\Process\Models\Node;
use App\Trees\Process\Models\Edge;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create workflow
        $workflow = Workflow::create([
            'name' => 'Asset Request Workflow',
            'key'  => 'asset_request',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NODES
        |--------------------------------------------------------------------------
        */

        $start = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Start',
            'type' => 'start',
        ]);

        $submit = Node::create([
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

        $costDecision = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Chargeable to Cost Center?',
            'type' => 'decision',
        ]);

        $costApproval = Node::create([
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

        $serviceApproval = Node::create([
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

        $sourcing = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Sourcing',
            'type' => 'process',
        ]);

        $stockDecision = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'In Stock?',
            'type' => 'decision',
        ]);

        $inventory = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Inventorizing',
            'type' => 'process',
        ]);

        $preparing = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Preparing',
            'type' => 'process',
        ]);

        $fulfillment = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Fulfillment',
            'type' => 'process',
        ]);

        $delivery = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'Delivering',
            'type' => 'process',
        ]);

        $end = Node::create([
            'workflow_id' => $workflow->id,
            'name' => 'End',
            'type' => 'end',
        ]);

        /*
        |--------------------------------------------------------------------------
        | EDGES
        |--------------------------------------------------------------------------
        */

        Edge::insert([

            // Start → Submit
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $start->id,
                'to_node_id' => $submit->id,
            ],

            // Submit → Cost Decision
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $submit->id,
                'to_node_id' => $costDecision->id,
            ],

            // Cost Decision → Cost Approval (YES)
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costDecision->id,
                'to_node_id' => $costApproval->id,
                'label' => 'Yes',
                'condition' => [
                    'field' => 'chargeable',
                    'operator' => '=',
                    'value' => true,
                ],
            ],

            // Cost Decision → Service Approval (NO)
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costDecision->id,
                'to_node_id' => $serviceApproval->id,
                'label' => 'No',
                'condition' => [
                    'field' => 'chargeable',
                    'operator' => '=',
                    'value' => false,
                ],
            ],

            // Cost Approval → Service Approval
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $costApproval->id,
                'to_node_id' => $serviceApproval->id,
            ],

            // Service Approval → Sourcing
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $serviceApproval->id,
                'to_node_id' => $sourcing->id,
            ],

            // Sourcing → Stock Decision
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $sourcing->id,
                'to_node_id' => $stockDecision->id,
            ],

            // Stock Decision → Fulfillment (YES)
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $stockDecision->id,
                'to_node_id' => $fulfillment->id,
                'label' => 'Yes',
                'condition' => [
                    'field' => 'in_stock',
                    'operator' => '=',
                    'value' => true,
                ],
            ],

            // Stock Decision → Inventory (NO)
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $stockDecision->id,
                'to_node_id' => $inventory->id,
                'label' => 'No',
                'condition' => [
                    'field' => 'in_stock',
                    'operator' => '=',
                    'value' => false,
                ],
            ],

            // Inventory → Preparing
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $inventory->id,
                'to_node_id' => $preparing->id,
            ],

            // Preparing → Delivery
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $preparing->id,
                'to_node_id' => $delivery->id,
            ],

            // Fulfillment → Delivery
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $fulfillment->id,
                'to_node_id' => $delivery->id,
            ],

            // Delivery → End
            [
                'workflow_id' => $workflow->id,
                'from_node_id' => $delivery->id,
                'to_node_id' => $end->id,
            ],
        ]);
    }
}