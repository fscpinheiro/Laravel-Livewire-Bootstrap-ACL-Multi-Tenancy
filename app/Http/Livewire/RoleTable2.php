<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\Cliente;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class RoleTable2 extends PowerGridComponent
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
        return Role::query();
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
            ->addColumn('cliente_id', fn(Role $cliente) => $cliente->cliente->fantasia)
            ->addColumn('nome')
            ->addColumn('cor')
            ->addColumn('corview', function(Role $model) {
                return '<div style="display: inline-block;  vertical-align: middle; width: 20px; height: 20px; border-radius: 50%; background-color: '.$model->cor.'; margin-right: 6px;"></div>'.$model->cor;
            })
            ->addColumn('descricao')
            ->addColumn('created_at_formatted', fn (Role $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }
    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    */
    public function columns(): array{
        return [
            Column::make('Cor', 'corview'),
            Column::make('Cliente', 'cliente_id')
                ->sortable()
                ->searchable(),
            Column::make('Classe', 'nome')
                ->sortable()
                ->searchable(),  
            Column::make('Descrição','descricao')
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
            Filter::select('cliente_id','cliente_id')
                ->dataSource(Cliente::all())
                ->optionValue('id')
                ->optionLabel('fantasia'),
                Filter::inputText('descricao')->operators(['contains']),    
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
               ->emit('editRole',  ['id' => 'id']),
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('btn-outline-danger rounded border-0')
                ->emit('deleteRole', ['id'=>'id']),
               
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
                'updateRoleTable',
               
            ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Update Table outside
    |--------------------------------------------------------------------------
    */
    public function updateRoleTable(){
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
                ->when(fn($role) => $role->id === 1)
                ->hide(),
        ];
    }
    */
}
