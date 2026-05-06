<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .customTable td,
        .customTable th {
            padding: 2px;
        }

        .ck-content.ck-editor__editable {
            min-height: 200px !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <div class="boxShadow">

                <form class="row mt-2 gap-3" wire:submit="save">

                    <div class="row row-cols-3">
                        <div class="row">
                            <label>Name</label>
                            <input class="form-control" wire:model="name" />
                        </div>
                        <div class="row">
                            <label>Title</label>
                            <input class="form-control" wire:model="title" />
                        </div>
                        <div class="row">
                            <label>Slug</label>
                            <input class="form-control" value="{{ $page->slug }}" readonly />
                        </div>
                    </div>
                    <div>
                        <div wire:ignore>
                            <textarea class="form-control" id="content" name="content" style="min-height: 300px;" wire:model="content"
                                rows="10">
                                {!! $page->content !!}
                            </textarea>
                        </div>

                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading wire:target="save">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>&nbsp;
                                Saving Page ...
                            </span>
                            <span wire:loading.remove wire:target="save">Save Page</span>
                        </button>
                    </div>
                </form>


            </div>

        </div>
    </div>
</div>
@push('custom-scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor.create(document.querySelector('#content'), {

            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('content', editor.getData());
                })
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
