<?php

namespace App\Livewire\Forms;

use App\Models\ImportantLink;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ImportantLinkForm extends Form
{
    public ?ImportantLink $important_link = null;

    #[Validate('required')]
    #[Validate('max:255')]
    public $title;

    #[Validate('required')]
    #[Validate('url')]
    public $url;

    #[Validate('required|image|mimes:jpeg,png,jpg|max:2048')]
    public $image;

    public $image_url;

    public function setData(ImportantLink $important_link)
    {
        $this->important_link = $important_link;

        $this->title = $important_link->title;
        $this->url = $important_link->url;
        $this->image_url = $important_link->image;
    }

    public function save()
    {
        if ($this->important_link) {
            $this->validateOnly('title');
            $this->validateOnly('url');

            $this->important_link->title = $this->title;
            $this->important_link->url = $this->url;

            if ($this->image) {
                $imageUrl = $this->image->store('important_links', 'public');
                $this->important_link->image = $imageUrl;
            }

            return $this->important_link->save();
        }

        $this->validate();
        $important_link = new ImportantLink();
        $important_link->title = $this->title;
        $important_link->url = $this->url;

        if ($this->image) {
            $imageUrl = $this->image->store('important_links', 'public');
            $important_link->image = $imageUrl;
        }

        return $important_link->save();
    }
}
