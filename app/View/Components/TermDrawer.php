<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class TermDrawer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $taxonomy,
        public $taxonomyName
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('backend.pages.posts.partials.term-drawer');
    }
}
