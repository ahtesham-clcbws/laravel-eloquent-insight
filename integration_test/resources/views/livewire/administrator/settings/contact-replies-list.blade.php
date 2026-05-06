<div class="h-100 px-3 pb-3">
    <style>
        .datatablecl td p {
            margin-bottom: 0px;
        }

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
        <h3>
            Contact Replies List:
        </h3>
        <a class="btn btn-success btn-sm" href="{{ route('admin.contactEnquiryReply', $contact->id) }}">Mail again</a>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <!-- datatablecl -->
            <div class="boxShadow">

                <div class="p-1">
                    <table class="table w-100 table-bordered customTable">
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
                                <td colspan="3">{{ $contact->reason_contact }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Message</th>
                                <td colspan="3">{{ $contact->message }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered datatablecl" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Message</th>
                                <th>Dated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contact->replyMails as $reply)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{!! $reply->message !!}</td>
                                <td>{{ $reply->created_at->format('d-M-Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>