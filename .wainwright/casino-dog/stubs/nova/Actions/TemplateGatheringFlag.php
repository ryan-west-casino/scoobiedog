<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Heading;


class TemplateGatheringFlag extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if(auth()->user()->is_admin != '1') {
            return Action::danger('You are not authorized.');
        }
        if ($models->count() > 1) {
           

                foreach($models as $selectModel) {
                    $selectModel = $models->first();
                    $exists = \Wainwright\CasinoDog\Models\GameTemplateQueued::where('gid', $selectModel->gid)->first();
                    if($exists) {
                        return Action::danger("Gid ${$exists} seems to exist already.");
                    }

                    \Wainwright\CasinoDog\Models\GameTemplateQueued::insert([
                        'gid' => $selectModel->gid,
                        'slug' => $selectModel->slug,
                        'completed' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
        } else {
            $selectModel = $models->first();
            $exists = \Wainwright\CasinoDog\Models\GameTemplateQueued::where('gid', $selectModel->gid)->first();
            if($exists) {
                return Action::danger('This gid seems to exist already.');
            }
            \Wainwright\CasinoDog\Models\GameTemplateQueued::insert([
                'gid' => $selectModel->gid,
                'slug' => $selectModel->slug,
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }

        return Action::message('Added game(s) to data gathering queue.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {

    }

}

