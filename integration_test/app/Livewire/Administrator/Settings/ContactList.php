<?php

namespace App\Livewire\Administrator\Settings;

use App\Models\ContactInfo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class ContactList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $dataList = [];

    public $perPage = 10;

    public $searchQuery = '';

    public bool $selectAll = false;

    public $selectedRows = [];

    public ContactInfo $contactDetails;


    public function mount()
    {
        ContactInfo::where('isNew', true)->update(['isNew' => false]);
    }

    public function updated($property)
    {
        if ($property == 'selectAll') {
            $this->selectedRows = $this->selectAll ? $this->dataList : [];
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = ContactInfo::orderBy('id', 'desc');

        if (!empty(trim($this->searchQuery))) {
            $query->where('fullname', 'LIKE', '%' . $this->searchQuery . '%');
            $query->orWhere('email', 'LIKE', '%' . $this->searchQuery . '%');
            $query->orWhere('mobile', 'LIKE', '%' . $this->searchQuery . '%');
            $query->orWhere('city', 'LIKE', '%' . $this->searchQuery . '%');
            $query->orWhere('reason_contact', 'LIKE', '%' . $this->searchQuery . '%');
            $query->orWhere('message', 'LIKE', '%' . $this->searchQuery . '%');
        }

        $contactsList = $query->paginate($this->perPage);
        $this->dataList = $contactsList->pluck('id');

        // $this->js('(new bootstrap.Modal(document.getElementById("exampleModal"))).show()');
        return view('livewire.administrator.settings.contact-list', [
            'contactsList' => $contactsList
        ]);
    }

    public function showModal($contactId)
    {
        try {
            $this->contactDetails = ContactInfo::find($contactId);
            $this->js('(new bootstrap.Modal(document.getElementById("exampleModal"))).show()');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteSelected()
    {
        ContactInfo::whereIn('id', $this->selectedRows)->delete();
        $this->js("success('Successfully delete contacts.')");
        $this->selectedRows = [];
        $this->selectAll = false;
    }
    public function delete($id)
    {
        ContactInfo::where('id', $id)->delete();
        $this->js("success('Successfully delete contact')");
    }
}
