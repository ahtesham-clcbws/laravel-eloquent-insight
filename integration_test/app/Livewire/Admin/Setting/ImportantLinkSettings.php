<?php

namespace App\Livewire\Admin\Setting;

use App\Livewire\Forms\ImportantLinkForm;
use App\Models\ImportantLink;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('administrator.layouts.master')]
class ImportantLinkSettings extends Component
{
    use WithFileUploads;

    public $length = 2;
    public $search = '';
    public ImportantLinkForm $form;

    public function render()
    {
        $important_links = ImportantLink::orderByDesc('id')
            ->when($this->search, function ($q) {
                $searchString = '%' . $this->search . '%';
                return $q
                    ->where('title', 'LIKE', $searchString)
                    ->orWhere('url', 'LIKE', $searchString);
            })
            ->get();
            // ->paginate($this->length);
        return view('livewire.admin.setting.important-link-settings', [
            'important_links' => $important_links
        ]);
    }

    public function saveLink()
    {
        try {
            $this->form->save();
            $this->form->reset();
            $this->js('window.location.reload()');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editLink($id)
    {
        try {
            $important_link = ImportantLink::find($id);
            $this->form->setData($important_link);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteLink($id)
    {
        try {
            $important_link = ImportantLink::find($id);
            if ($important_link) {
                $important_link->delete();
                $this->js('toastr.success("Link deleted successfully.")');
            } else {
                $this->js('toastr.error("Link not found.")');
            }
        } catch (\Throwable $th) {
            $this->js('toastr.error("Failed to delete link.")');
        }
    }
}
