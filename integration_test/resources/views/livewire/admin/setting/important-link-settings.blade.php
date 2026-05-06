<div class="row px-3">
    <div class="col-lg-12">
        <div class="m-t-15 m-2">
            <form class="row row-cols-5 py-2" wire:submit='saveLink'>
                <div class="col">
                    <h2>Important Links</h2>
                </div>
                <div class="col">
                    <input class="form-control" type="file" wire:model='form.image' accept=".jpg, .jpeg, .png" />
                    @error('form.image')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col">
                    <input class="form-control" wire:model='form.title' placeholder="Title" />
                    @error('form.title')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col">
                    <input class="form-control" wire:model='form.url' placeholder="Website URL" />
                    @error('form.url')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col">
                    <button class="btn btn-primary w-100" type="submit" wire:loading.attr='disabled'
                        wire:target='saveLink'>Save</button>
                </div>
            </form>

            <div class="card rounded">
                {{-- <div class="card-header">
                    <div class="d-flex gap-4 py-2" style="justify-content: space-between;">
                        <div>
                            <select class="form-control">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div>
                            <input class="form-control" type="search" placeholder="search ..."
                                wire:model.live='search' />
                        </div>
                    </div>
                </div> --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-bordered table-sm w-100">
                            <thead>
                                <tr>
                                    <th class="text-start">#</th>
                                    <th>Title</th>
                                    <th>Url</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($important_links as $important_link)
                                    <tr>
                                        <td class="text-start">
                                            <img class="img-fluid" src="{{ '/storage/' . $important_link->image }}"
                                                style="max-width: 35px;" />
                                        </td>
                                        <td>{{ $important_link->title }}</td>
                                        <td>{{ $important_link->url }}</td>
                                        <td class="ps-4">
                                            <livewire:admin.setting.important-link-row :important_link="$important_link"
                                                :index="$loop->iteration" />
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-link" wire:click='editLink({{ $important_link->id }})'>Edit</button>
                                            <button class="btn btn-link text-danger" wire:click='deleteLink({{ $important_link->id }})' wire:confirm="Are you sure you want to delete this link?">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer pb-0">{{ $important_links->links() }}</div> --}}

            </div>

        </div>
    </div>
</div>
