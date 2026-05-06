<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <h3 style="padding-top: 10px;padding-left: 10px;">
        Registration Settings:
    </h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <div class="container boxShadow">
                <form wire:submit="save" class="row g-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date & Time</label>
                        <input type="datetime-local" id="start_date" class="form-control" wire:model="start_date">
                        @error('start_date') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date & Time</label>
                        <input type="datetime-local" id="end_date" class="form-control" wire:model="end_date">
                        @error('end_date') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="is_enabled" class="form-label">Registration Status</label>
                        <select id="is_enabled" class="form-select" wire:model="is_enabled">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        @error('is_enabled') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary" type="submit">Save Registration Settings</button>
                    </div>
                </form>
            </div>

            <div class="container mt-4">
                <div class="alert alert-info">
                    <p class="mb-0"><strong>Note:</strong> Registration will only be active if "Registration Status" is Enabled AND (the current date is between "Start Date" and "End Date", or dates are not set).</p>
                </div>
            </div>

        </div>
    </div>
</div>
