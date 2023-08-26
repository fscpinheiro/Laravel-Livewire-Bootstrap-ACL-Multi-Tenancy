<?php

namespace App\Http\Livewire;

use App\Models\App;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class AppTable2 extends PowerGridComponent
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
        return App::query();
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
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('nome')
            ->addColumn('icone')
            ->addColumn('iconeapp', function(App $model){
                $file_target = '/storage/icone_uploads/' . $model->icone;
                $avatar = '<div class="avatar me-2"><img src="'.$file_target.'" alt="Avatar" class="rounded-circle"></div>';

                if (!empty($model->icone)) {
                    return $avatar;
                } else {
                    $nome = $model->nome;
                    preg_match_all('/\p{Lu}/u', $nome, $matches);
                    $iniciais = implode('', array_slice($matches[0], 0, 2));
                    return '<div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">'.$iniciais.'</span></div>';
                }
            })
            ->addColumn('descricao')
            ->addColumn('modelo')
            ->addColumn('created_at_formatted', fn (App $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }
    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    */
    public function columns(): array
    {
        return [
            Column::make('Icone', 'iconeapp'),
            Column::make('Nome', 'nome')
                ->sortable()
                ->searchable(),
            Column::make('Modelo', 'modelo')
                ->sortable()
                ->searchable(),
            Column::make('Criado', 'created_at_formatted', 'created_at')
                ->sortable(),
            Column::make('Descricao', 'descricao')
                ->sortable()
                ->searchable(),            
            

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
            Filter::inputText('nome')->operators(['contains']),
            Filter::inputText('icone')->operators(['contains']),
            Filter::inputText('modelo')->operators(['contains']),
            Filter::datetimepicker('created_at'),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */
    public function actions(): array
    {
       return [
            Button::make('edit', '<i class="ti ti-pencil ti-md"></i>')
                ->class(' rounded border-0 btn-outline-primary')
                ->emit('editApp',  ['id' => 'id']),
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('btn-outline-danger rounded border-0')
                ->emit('deleteApp', ['id'=>'id']),
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
                'updateAppTable',
               
            ]);
    }

    public function updateAppTable(){
        $this->fillData();
    }  
    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid App Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($app) => $app->id === 1)
                ->hide(),
        ];
    }
    */
}
