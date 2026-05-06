<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <h3 style="padding-top: 10px;padding-left: 10px;">
        Popup Settings:
    </h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <div class="container boxShadow">
                <form wire:submit="save" class="row g-3">
                    @if ($photo || $image)
                    <div class="col-md-6">
                        <img src="{{ $photo ? $photo->temporaryUrl() : $image }}" class="w-100 mb-2">
                    </div>
                    @endif

                    <div class="col-md-6">
                        <div class="">
                            <div
                                class="w-100"
                                x-data="{ uploading: false, progress: 0 }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-cancel="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <!-- File Input -->
                                <input type="file" class="form-control" wire:model="photo" accept="image/*">

                                <!-- Progress Bar -->
                                <div x-show="uploading">
                                    <progress max="100" x-bind:value="progress"></progress>
                                </div>
                            </div>

                            @error('photo') <span class="error text-danger">{{ $message }}</span> @enderror

                        </div>
                        <div class="mt-3">
                            <select class="form-select" wire:model="status">
                                <option value="" selected>Popup Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-success" type="submit">Save Setting</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>