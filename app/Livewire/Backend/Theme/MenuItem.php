<?php

namespace App\Livewire\Backend\Theme;

use Livewire\Component;

class MenuItem extends Component
{
    public $url;
    public $icon;
    public $label;
    public $hasSubMenu = false;
    public $subMenuItems = [];

    public function mount($url = null, $icon, $label, $hasSubMenu = false, $subMenuItems = [])
    {
        $this->url = $url;

        $this->icon = $icon;
        $this->label = $label;
        $this->hasSubMenu = $hasSubMenu;
        $this->subMenuItems = $subMenuItems;
    }

    // Helper function to handle routes and URLs
    public function getUrl()
    {
        // Check if the URL is a named route
        if (route($this->url)) {
            return route($this->url);  // Return the generated URL from named route
        }

        return $this->url;  // Return the URL if it's a direct link
    }
    
    public function render()
    {
        return view('livewire.backend.theme.menu-item');
    }
}
