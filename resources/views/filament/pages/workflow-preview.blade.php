<x-filament::page>

    <div class="space-y-4">

        <div class="text-lg font-semibold">
            Workflow Preview
        </div>

        <div class="p-4 bg-white rounded shadow">
            <div class="mermaid">
                {!! $mermaid !!}
            </div>
        </div>

    </div>

    {{-- ✅ Mermaid CDN --}}
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';

        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            flowchart: {
                curve: 'basis'
            }
        });
    </script>

</x-filament::page>
