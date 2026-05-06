<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .coupons_table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #dee2e6;
        }

        .coupons_table td,
        .coupons_table th {
            padding: 2px;
            /* vertical-align: middle; */
        }

        .coupons_table .sortHead {
            cursor: pointer;
            min-width: 100px;
        }

        .headerGridBox .flex-fill {
            min-width: 100px;
        }

        .opacity-half {
            opacity: 0.5;
        }
    </style>

    <h3 style="padding-top: 10px;padding-left: 10px;">
        Contact List:
    </h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <!-- datatablecl -->
            <div class="boxShadow">

                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        @if (count($selectedRows))
                        <div class="flex-fill"><button class="btn btn-danger" wire:click="deleteSelected">Delete Selected</button></div>
                        @endif
                        <div class="flex-fill">
                            <select class="form-select" id="showResutsPerPage" wire:model.live="perPage">
                                <option value="10">10 Results</option>
                                <option value="20">20 Results</option>
                                <option value="30">30 Results</option>
                                <option value="50">50 Results</option>
                                <option value="100">100 Results</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="flex-fill">
                            <input class="form-control" type="search" id="searchQuery" wire:model.live="searchQuery" placeholder="Search here ..." />
                        </div>
                    </div>
                </div>

                <table class="table table-bordered coupons_table" style="width:100%">
                    <thead class="thead-light">
                        <tr class="">
                            <th>
                                <input type="checkbox" id="selectAll" wire:model.live="selectAll">
                                <label for="selectAll" class="sr-only">Select All</label>
                            </th>
                            <th>#</th>
                            <th class="sortHead" data-type="name">
                                <span>Name</span>
                            </th>
                            <th class="sortHead" data-type="email">
                                <span>Email</span>
                            </th>
                            <th class="sortHead" data-type="mobile">
                                <span>Mobile</span>
                            </th>
                            <th class="sortHead" data-type="reason">
                                <span>Reason</span>
                            </th>
                            <th class="sortHead" data-type="city">
                                <span>City</span>
                            </th>
                            <th class="sortHead" data-type="message">
                                <span>Message</span>
                            </th>
                            <th data-type="created_at">
                                <span>Date Time</span>
                            </th>

                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($contactsList as $contact)
                        <tr style="<?= $contact->status ? 'background-color: #2180614d;':'' ?>">
                            <td> <input type="checkbox" class="selectSingle" value="{{ $contact->id }}" wire:model.live="selectedRows"></td>
                            <td style="font-size: 13px">{{ $loop->index+1 }}</td>
                            <td style="font-size: 13px">
                                {{ $contact->fullname }}
                            </td>
                            <td style="font-size: 13px">
                                {{ $contact->email }}
                            </td>
                            <td style="font-size: 13px">
                                {{ $contact->mobile }}
                            </td>
                            <td style="font-size: 13px">
                                {{ $contact->reason_contact }}
                            </td>
                            <td style="font-size: 13px">
                                {{ $contact->city }}
                            </td>
                            <td style="font-size: 13px; max-width: 300px !important;" class="text-wrap">
                                {{ $contact->message }}
                            </td>
                            <td style="font-size: 13px;" class="text-wrap">
                                {{ date('d-M-Y h:i A', strtotime($contact->created_at)) }}
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($contact->replyMails->count())
                                <a type='button' class='btn btn-warning btn-sm' href="{{ route('admin.contactRelpiesList', $contact->id) }}">View Replied</a>
                                @endif
                                <a type='button' class='btn btn-primary btn-sm' href="{{ route('admin.contactEnquiryReply', $contact->id) }}">Reply</a>
                                <button type="button" class="btn btn-danger btn-sm" wire:click="delete({{ $contact->id }})" wire:confirm="Are you sure you want to delete this?">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="">
                    {{ $contactsList->onEachSide(3)->links('vendor.livewire.bootstrap') }}
                </div>
            </div>

        </div>
    </div>
</div>