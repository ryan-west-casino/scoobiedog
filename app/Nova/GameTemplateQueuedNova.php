<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Boolean;

class GameTemplateQueuedNova extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Wainwright\CasinoDog\Models\GameTemplateQueued::class;
    public static $name = 'Saved Gameplates';
    public static $group = 'Puppeteer';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'gid';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'gid',
    ];
    public static $defaultSort = 'created_at'; // Update to your default column

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (static::$defaultSort && empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(static::$defaultSort);
        }
        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            Text::make('Game ID', 'gid')->sortable(),
            Text::make('Slug', 'slug'),
            Text::make('Count',
            function () {
                return $this->count_current();
            })->asHtml()->hideWhenCreating()->hideWhenUpdating(),
            Boolean::make('State', 'completed'),
            DateTime::make('Created at', 'created_at')->default(time())->hideWhenUpdating()->hideWhenCreating()->readonly()->sortable(),
            DateTime::make('Updated at', 'updated_at')->hideFromIndex()->default(time())->hideWhenUpdating()->hideWhenCreating()->readonly()->sortable(),
        ];
    }

    public function count_current()
    {
        return '<small>' . (\Wainwright\CasinoDog\Models\GameTemplate::where('gid', $this->slug)->count()). '</small>';
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
