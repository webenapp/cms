<?php

namespace Statamic\Widgets;

class Note extends Widget
{
    /**
     * The HTML that should be shown in the widget
     *
     * @return \Illuminate\View\View
     */
    public function html()
    {
        $classes = $this->config('classes', 'w-full');
        $title = $this->config('title', __('Your Notes'));

        return view('statamic::widgets.note', compact('classes', 'title'));
    }
}
