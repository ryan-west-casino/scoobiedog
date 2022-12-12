<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;

class GameTemplateNova extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Wainwright\CasinoDog\Models\GameTemplate::class;
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

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Game ID', 'gid')->sortable(),
            Text::make('Data', 'game_data')->hideFromIndex(),
            Text::make('State', 'game_state')->hideFromIndex(),
            Text::make('Credit', 'credit')->hideFromIndex(),
            Text::make('Debit', 'debit')->hideFromIndex(),
            Text::make('Type', 'game_type')->sortable(),


        ];
    }


    public function get_game_data()
    {
        return '<small>' . substr($this->game_data, 0, 35). '</small>';
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
