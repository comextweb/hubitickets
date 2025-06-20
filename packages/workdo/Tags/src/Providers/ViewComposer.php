<?php

namespace Workdo\Tags\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Workdo\Tags\Entities\Tags;

class ViewComposer extends ServiceProvider
{
    public function boot()
    {
        // for ticket selected tags
        view()->composer(['admin.chats.new-chat'], function ($view) {
            if (moduleIsActive('Tags')) {
                $view->getFactory()->startPush('ticket-tags', view('tags::tags.chat_tag'));
            }
        });

        // shows tags for ticket filtering
        view()->composer(['admin.chats.new-chat'], function ($view) {
            if (moduleIsActive('Tags')) {
                $view->with('isTags', true);
                $tags = Tags::where('created_by', creatorId())->get();
                $view->getFactory()->startPush('filter_tags', view('tags::filter.tags', compact('tags')));
            }
        });

        // show all tags tags for selection
        view()->composer(['admin.chats.new-chat-messge'], function ($view) {
            if (moduleIsActive('Tags')) {
                $ticketId = Request::segment(3);
                $view->getFactory()->startPush('tags-popup', view('tags::ticket.tag-popup', compact('ticketId')));
            }
        });

    }
    public function register()
    {
        //
    }

    public function provides()
    {
        return [];
    }
}
