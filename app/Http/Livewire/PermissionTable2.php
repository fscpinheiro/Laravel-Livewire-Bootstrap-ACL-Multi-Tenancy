<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use App\Models\App;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class PermissionTable2 extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    public int $perPage = 10;    
    public array $perPageValues = [10, 20, 40, 80, 100];

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    */
    public function setUp(): array{
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped('#A6ACCD')
                ->csvSeparator('|')
                ->csvDelimiter("'")
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
                Header::make()->showSearchInput()->showToggleColumns(),
                Footer::make()
                    ->showPerPage($this->perPage, $this->perPageValues)
                    ->showRecordCount(mode: 'full'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    */
    public function datasource(): Builder{
        return Permission::query();
    }
    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    */
    public function relationSearch(): array{
        return [];
    }
    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    */
    public function addColumns(): PowerGridColumns{
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('app_id', fn(Permission $app) => $app->app->nome)
            ->addColumn('nome')
            ->addColumn('descricao')
            ->addColumn('created_at_formatted', fn (Permission $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }
    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    */
    public function columns(): array{
        return [
            Column::make('App', 'app_id')
                ->sortable()
                ->searchable(),

            Column::make('Nome', 'nome')
                ->sortable()
                ->searchable(),

            Column::make('Descricao', 'descricao')
                ->sortable()
                ->searchable(),

            Column::make('Criado', 'created_at_formatted', 'created_at')
                ->sortable(),

        ];
    }
     /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */
    public function filters(): array
    {
        return [
            Filter::select('app_id','app_id')
                ->dataSource(App::all())
                ->optionValue('id')
                ->optionLabel('nome'),
            Filter::inputText('nome')->operators(['contains']),
            Filter::datetimepicker('created_at'),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    */
    public function actions(): array{
        return [
            Button::make('edit', '<i class="ti ti-pencil ti-md"></i>')
               ->class(' rounded border-0 btn-outline-primary')
               ->emit('editPermission',  ['id' => 'id']),
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('btn-outline-danger rounded border-0')
                ->emit('deletePermission', ['id'=>'id']),
               
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Listeners
    |--------------------------------------------------------------------------
    */
    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'rowActionEvent',
                'updatePermissionTable',
               
            ]);
    }

    public function updatePermissionTable(){
        $this->fillData();
    } 
     
    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($permission) => $permission->id === 1)
                ->hide(),
        ];
    }
    */
}
