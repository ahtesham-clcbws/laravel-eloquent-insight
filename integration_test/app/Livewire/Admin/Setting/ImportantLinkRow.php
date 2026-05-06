<?php

namespace App\Livewire\Admin\Setting;

use App\Models\ImportantLink;
use Livewire\Component;

class ImportantLinkRow extends Component
{
    public $index;
    public $important_link;
    public $status;

    public function mount(ImportantLink $important_link, $index)
    {
        $this->index = $index;
        $this->important_link = $important_link;
        $this->status = $important_link->status;
    }
    public function render()
    {
        return view('livewire.admin.setting.important-link-row');
    }
    public function changeStatus()
    {
        try {
            $important_link = ImportantLink::withoutGlobalScope('active')->find($this->important_link->id);
            if (!$important_link) {
                $this->js("alert('Important Link not found.')");
                return false;
            }
            $important_link->status = !$important_link->status;
            $important_link->save();

            $this->important_link = $important_link;
            $this->status = $this->important_link->status;

            $this->js("success('Status Updated successfully.')");
            return true;
        } catch (\Throwable $th) {
            $this->js("error('" . $th->getMessage() . "')");
            return false;
        }
    }
}
