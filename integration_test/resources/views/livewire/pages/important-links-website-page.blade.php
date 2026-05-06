<div>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container py-5 pb-4 text-center">
            <h3 class="text-white" style="font-size:50px; font-weight:900;">
                Important Links
            </h3>
        </div>
    </div>
    <style>
        .small {
            font-size: 80%;
        }

        .important-links-table a {
            color: inherit;
            font-weight: 500;
        }
    </style>
    <div class="container py-5">

        <div class="table-responsive">
            <table class="table-bordered important-links-table table" style="line-height: 1.4;">
                <tbody>
                    @foreach ($important_links as $important_link)
                        <tr valign="middle">
                            <td class="text-start">
                                <a href="{{ $important_link->url }}" target="_blank">
                                    <img class="img-fluid" src="{{ '/storage/' . $important_link->image }}"
                                        style="height: 35px;" />
                                </a>
                            </td>
                            <td valign="middle">
                                <div style="display: flex;align-items: center;height: 35px;">
                                    <a href="{{ $important_link->url }}" target="_blank">
                                        {{ $important_link->title }}
                                    </a>
                                </div>
                            </td>
                            <td valign="middle">
                                <div style="display: flex;align-items: center;height: 35px;">
                                    <a href="{{ $important_link->url }}" target="_blank">
                                        {{ $important_link->url }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
