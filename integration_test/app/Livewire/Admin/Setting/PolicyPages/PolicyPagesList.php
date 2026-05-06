<?php

namespace App\Livewire\Admin\Setting\PolicyPages;

use App\Models\PolicyPage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class PolicyPagesList extends Component
{
    public function render()
    {
        $policyPages = PolicyPage::all();
        return view('livewire.admin.setting.policy-pages.policy-pages-list', [
            'policyPages' => $policyPages
        ]);
    }
}
