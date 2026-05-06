<div class="row px-3">
    <div class="col-lg-12">
        <div class="m-2 m-t-15">
            <div class="row justify-content-space-between py-2">
                <div class="col-md-6 col">
                    <h2>States Settings</h2>
                </div>
            </div>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-bordered datatablecl">
                            <thead>
                                <tr>
                                    <th class="text-start">S.No</th>
                                    <th>Name</th>
                                    <th>Districts</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allStates as $state)
                                <livewire:admin.setting.state-row :state="$state" :index="$loop->iteration" />
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>