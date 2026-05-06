<div>
    <select id="{{ $id }}" style="min-width:240px;" wire:model="modelAttribute" multiple>
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
        @endforeach
    </select>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.39/dist/virtual-select.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.39/dist/virtual-select.min.css" rel="stylesheet">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                VirtualSelect.init({
                    ele: '#{{ $id }}',
                    multiple: true,
                    options: @json($options),
                    onChange: (selectedValues) => {
                        @this.set('selectedOptions', selectedValues);
                    }
                });
            });
        </script>
    @endpush
</div>
