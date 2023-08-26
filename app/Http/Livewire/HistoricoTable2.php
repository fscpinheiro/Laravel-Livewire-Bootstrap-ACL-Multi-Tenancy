<?php

namespace App\Http\Livewire;

use App\Models\Historico;
use App\Models\App;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class HistoricoTable2 extends PowerGridComponent
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
        return Historico::query();
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
            ->addColumn('cliente_id', fn(Historico $cliente) => $cliente->cliente->fantasia)
            ->addColumn('user_id', fn(Historico $user) => $user->user->name)
            ->addColumn('app_id', fn(Historico $app) => $app->app->nome)
            ->addColumn('data', fn (Historico $model) => Carbon::parse($model->datahora)->format('d/m/Y H:i:s'))
            ->addColumn('ip')
            ->addColumn('acao')
            ->addColumn('browser')
            ->addColumn('so');
    }
    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    */
    public function columns(): array{
        return [
            Column::make('Usuário', 'user_id')
                ->sortable()
                ->searchable(),

            Column::make('Cliente', 'cliente_id')
                ->sortable()
                ->searchable(),            

            Column::make('App', 'app_id')
                ->sortable()
                ->searchable(),

            Column::make('Data', 'data')
                ->searchable()
                ->sortable(),

            Column::make('IP', 'ip')
                ->sortable()
                ->searchable(),

            Column::make('AÇÃO', 'acao')
                ->sortable()
                ->searchable(),

            Column::make('Navegador', 'browser')
                ->sortable()
                ->searchable(),

            Column::make('SO', 'so')
                ->sortable()
                ->searchable(),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */
    public function filters(): array{
        return [
            Filter::select('cliente_id','cliente_id')
                ->dataSource(Cliente::all())
                ->optionValue('id')
                ->optionLabel('fantasia'),
            Filter::select('user_id','user_id')
                ->dataSource(User::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('app_id','app_id')
                ->dataSource(App::all())
                ->optionValue('id')
                ->optionLabel('nome'),
            Filter::datepicker('data'),
            Filter::inputText('ip')->operators(['contains']),
            Filter::inputText('acao')->operators(['contains']),
            Filter::inputText('browser')->operators(['contains']),
            Filter::inputText('so')->operators(['contains']),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    */
    public function actions(): array {
        return [
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('btn-outline-danger rounded border-0')
                ->emit('deleteHistorico', ['id'=>'id']),
               
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
                'updateHTable',
               
            ]);
    }
   
    public function updateHTable(){
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
                ->when(fn($historico) => $historico->id === 1)
                ->hide(),
        ];
    }
    */
}
