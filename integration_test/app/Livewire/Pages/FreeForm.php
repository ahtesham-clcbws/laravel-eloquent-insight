<?php

namespace App\Livewire\Pages;

use App\Models\Corporate;
use App\Models\District;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class FreeForm extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Collection $districts;
    public $selectedDistrict = '';
    public $sortKey = 'city';
    public $sortDirection = 'asc';  // desc
    public $entriesPerPage = 25;

    public $entiresArray = [5, 10, 15, 25, 50, 100, 0];
    public $search = '';

    public function mount()
    {
        $districts = Corporate::select('district_id')->where('signup_approved', 1)->whereNotNull('signup_at')->where('status', 1)->groupBy('district_id')->pluck('district_id');
        $this->districts = District::whereIn('id', $districts)->get();
    }

    public function dehydrate()
    {
        $this->js("console.log('dehydrate')");
        $this->js('interestedForInit()');
    }

    public function render()
    {
        // Fetch paginated institutes in render() and pass them to the view
        $institutesQuery = Corporate::whereHas('district')
            ->where('is_approved', 1)
            ->where('signup_approved', 1)
            ->whereNotNull('signup_at')
            ->where('status', 1)
            ->orderBy(function ($query) {
                $query->select('name')
                    ->from('districts')
                    ->whereColumn('districts.id', 'corporates.district_id');
            }, $this->sortDirection);
        if ($this->search) {
            // $institutesQuery->where('city', 'like', '%' . $this->search . '%');
            $institutesQuery->where('name', 'like', '%' . $this->search . '%');
            $institutesQuery->orWhere('institute_name', 'like', '%' . $this->search . '%');
            $institutesQuery->orWhere('address', 'like', '%' . $this->search . '%');
            $institutesQuery->orWhere('phone', 'like', '%' . $this->search . '%');
        }
        if ($this->selectedDistrict) {
            $institutesQuery->where('district_id', $this->selectedDistrict);
        }
        $institutesQuery->orderBy($this->sortKey, $this->sortDirection);
        if ($this->entriesPerPage == 0) {
            $institutes = $institutesQuery->get();
        } else {
            $institutes = $institutesQuery->paginate($this->entriesPerPage);
        }

        // return print_r($this->institutes);

        $mainInstitutes = [
            (object) [
                'district' => (object) [
                    'name' => 'Uttar Pradesh'
                ],
                'attachment' => '/logos/logo-square-2.png',
                'name' => 'SQS Foundation',
                'institute_name' => 'SQS Foundation',
                'phone' => '9336171302 (WhatsApp Only)',
                'message' => 'Monday - Friday (10:00 am - 10:00 pm)',
                'address' => 'Kanpur Nagar, Uttar Pradesh',
                'available_button' => false
            ],
            (object) [
                'district' => (object) [
                    'name' => 'Uttar Pradesh'
                ],
                'attachment' => '/weblies-logo.png',
                'name' => 'Weblies Equations',
                'institute_name' => 'Weblies Equations PVT. LTD',
                'phone' => '9389696641 (WhatsApp Only)',
                'message' => 'Monday - Friday (10:00 am - 07:00 pm)',
                'address' => 'Kanpur Nagar, Uttar Pradesh',
                'available_button' => true
            ]
        ];

        return view('livewire.pages.free-form', [
            'mainInstitutes' => $mainInstitutes,
            'institutes' => $institutes
        ]);
    }

    // public function updated($property){
    //     $this->js("console.log('updated')");
    //     $this->js('interestedForInit()');
    // }
}
