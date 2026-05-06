<x-slot:title>{{ $page->title }}</x-slot:title>
<div>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container py-5 pb-4 text-center">
            <h2 class="text-white" style="font-size:32px">{{ $page->title }}</h2>
        </div>
    </div>


    <div class="container py-5">
        <div class="row">
            <div class="col prose max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
