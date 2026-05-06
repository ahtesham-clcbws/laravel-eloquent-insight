<tr>
    <td class="text-start"><b>{{ $index.'' }}</b></td>
    <td>{{ $state->name }}</td>
    <td class="text-end">{{ $state->districts_all_count }}</td>
    <td class="ps-4">
        <div class="ms-2 form-check form-switch">
            <input class="form-check-input" wire:model="$isActive" wire:click="changeStatus()" type="checkbox" role="switch" id="{{ 'state_status_'.$state->id }}" {{ $state->status == 'Active' ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ 'state_status_'.$state->id }}">{{ $state->status }}</label>
        </div>
    </td>
</tr>