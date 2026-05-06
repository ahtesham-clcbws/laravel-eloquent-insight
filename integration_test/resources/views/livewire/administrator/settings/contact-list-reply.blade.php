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

    <div class="d-flex align-items-center justify-content-between" style="padding: 10px;">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.contactEnquiry') }}">Close</a>
        @if ($contact->replyMails->count())
            <a class='btn btn-danger btn-sm' type='button'
                href="{{ route('admin.contactRelpiesList', $contact->id) }}">View Replied List</a>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <!-- datatablecl -->
            <div class="boxShadow">

                <div class="p-1">
                    <table class="w-100 table-bordered customTable table">
                        <tbody>
                            <tr>
                                <th scope="row">Name</th>
                                <td>{{ $contact->fullname }}</td>
                                <th scope="row">Email</th>
                                <td>{{ $contact->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Mobile</th>
                                <td>{{ $contact->mobile }}</td>
                                <th scope="row">City</th>
                                <td>{{ $contact->city }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Contact Reason</th>
                                <td>{{ $contact->reason_contact }}</td>
                                <th scope="row">Date/Time</th>
                                <td>{{date('d-M-Y h:i A', strtotime( $contact->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Message</th>
                                <td colspan="3">{{ $contact->message }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form class="row mt-2 gap-3" wire:submit.prevent="save">
                    <div wire:ignore>
                        <textarea class="form-control" id="message" name="message" style="min-height: 300px;" wire:model="message"
                            rows="10">
                        </textarea>
                    </div>

                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button class="btn btn-primary">
                            <span wire:loading>
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>&nbsp;
                                Sending email ...
                            </span>
                            <span wire:loading.remove>Send Reply</span>
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
        ClassicEditor.create(document.querySelector('#message'), {

            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('message', editor.getData());
                })
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
