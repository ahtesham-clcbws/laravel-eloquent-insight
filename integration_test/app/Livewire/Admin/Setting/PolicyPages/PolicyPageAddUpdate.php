<?php

namespace App\Livewire\Admin\Setting\PolicyPages;

use App\Models\PolicyPage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class PolicyPageAddUpdate extends Component
{
    public PolicyPage $page;

    #[Validate('required')]
    public $name = '';
    #[Validate('required')]
    public $title = '';
    #[Validate('required', 'unique')]
    public $slug = '';
    #[Validate('required')]
    public $content = '';

    public function mount(PolicyPage $page)
    {
        $this->page = $page;
        $this->name = $page->name;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
    }

    public function render()
    {
        return view('livewire.admin.setting.policy-pages.policy-page-add-update');
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $this->page->name = $this->name;
                $this->page->title = $this->title;
                $this->page->content = $this->content;
                $this->page->save();

                $this->js('success("Page saved successfully.")');
                return $this->redirect('/administrator/home/policy-pages');
            } catch (\Throwable $th) {
                // throw $th;
                logger('page data save error: ' . json_encode($th));
                // $this->js('console.log(' . $th->getMessage() . ')');
                $this->js('error("Failed to save page. Error: ' . $th->getMessage() . '")');
            }
        }
    }
}
