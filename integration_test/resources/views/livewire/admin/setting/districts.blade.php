<div class="row px-3">
    <div class="col-lg-12">
        <div class="m-2 m-t-15">
            <div class="row justify-content-space-between py-2">
                <div class="col-md-6 col">
                    <h2>District Settings</h2>
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
                                    <th>State</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allDisctricts as $district)
                                <livewire:admin.setting.district-row :district="$district" :index="$loop->iteration" />
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>