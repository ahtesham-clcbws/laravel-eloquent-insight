<?php

namespace App\Livewire\Website;

use App\Models\PolicyPage;
use Livewire\Component;

class PolicyPageFrontend extends Component
{
    public PolicyPage $page;
    public function mount(string $slug)
    {
        $page = PolicyPage::where('slug', $slug)->first();
        if ($page) {
            $this->page = $page;
        } else {
            return \redirect('/');
        }
    }
    public function render()
    {
        return view('livewire.website.policy-page-frontend');
    }
}
