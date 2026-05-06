<?php

namespace App\Livewire\Pages;

use App\Models\ImportantLink;
use Livewire\Component;

class ImportantLinksWebsitePage extends Component
{
    public function render()
    {
        $important_links = ImportantLink::orderByDesc('id')->where('status', true)->get();
        return view('livewire.pages.important-links-website-page', [
            'important_links'=>$important_links
        ]);
    }
}
