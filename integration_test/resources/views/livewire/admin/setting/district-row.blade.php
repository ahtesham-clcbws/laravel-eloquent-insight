<tr>
    <td class="text-start"><b>{{ $index.'' }}</b></td>
    <td>{{ $district->name }}</td>
    <td>{{ $district->state_name }}</td>
    <td class="ps-4">
        <div class="ms-2 form-check form-switch">
            <input class="form-check-input" wire:model="$isActive" wire:click="changeStatus()" type="checkbox" role="switch" id="{{ 'district_status_'.$district->id }}" {{ $isActive ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ 'district_status_'.$district->id }}">{{ $isActive ? 'Active' : 'In-Active' }}</label>
        </div>
    </td>
</tr>