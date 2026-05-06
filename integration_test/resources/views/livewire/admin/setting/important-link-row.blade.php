<div class="form-check form-switch ms-2">
    <input class="form-check-input" id="{{ 'important_link_status_' . $important_link->id }}" type="checkbox" role="switch"
        wire:model="$status" wire:click="changeStatus()" {{ $status ? 'checked' : '' }}>
    <label class="form-check-label"
        for="{{ 'important_link_status_' . $important_link->id }}">{{ $status ? 'Active' : 'In-Active' }}</label>
</div>
