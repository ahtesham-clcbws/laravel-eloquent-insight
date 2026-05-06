<div class="row px-3">
    <div class="col-lg-12">
        <div class="m-t-15 m-2">
            <div class="row justify-content-space-between py-2">
                <div class="col-md-6 col">
                    <h2>Policy Pages</h2>
                </div>
            </div>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table-bordered datatablecl table">
                            <thead>
                                <tr>
                                    <th class="text-start">S.No</th>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policyPages as $page)
                                    <tr>
                                        <td class="text-start"><b>{{ $loop->index + 1 }}</b></td>
                                        <td>{{ $page->name }}</td>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->slug }}</td>
                                        <td class="items-end text-end">
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('admin.policy-page-update', $page->id) }}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
