<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use App\Models\SituacaoClienteCollection;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use Illuminate\Support\Facades\Log;

final class ClienteTable3 extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    */
    public int $perPage = 10;    
    public array $perPageValues = [10, 20, 40, 80, 100];

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
        return Cliente::query();
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
            ->addColumn('razaosocial')
            ->addColumn('fantasia')
            ->addColumn('documento')
            ->addColumn('situacao', function(Cliente $cliente){
                switch($cliente->situacao){
                    case 0:
                        return 'cancelado';
                        break;
                    case 1:
                        return 'ativo';
                        break;
                    case 2:
                        return 'suspenso';
                        break;
                    default: return 'ativo';
                }
            })
            ->addColumn('label_situacao', fn ($situacao) => SituacaoClienteCollection::codes()->firstWhere('code', $situacao->code)['label'])
            ->addColumn('logo')
            ->addColumn('logotipo', function(Cliente $model){
                $file_target = '/storage/image_uploads/' . $model->logo;
                $avatar = '<div class="avatar me-2"><img src="'.$file_target.'" alt="Avatar" class="rounded-circle"></div>';
                if (!empty($model->logo)) {
                    return $avatar;
                } else {
                    $razaoSocial = $model->razaosocial;
                    preg_match_all('/\p{Lu}/u', $razaoSocial, $matches);
                    $iniciais = implode('', array_slice($matches[0], 0, 2));
                    return '<div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">'.$iniciais.'</span></div>';
                }
            })
            ->addColumn('created_at_formatted', fn (Cliente $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }
    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    */
    public function columns(): array
    {
        return [            
            Column::make('Logo', 'logotipo'),
            Column::make('Fantasia', 'fantasia')
                ->sortable()
                ->searchable(),
            Column::make('Razaosocial', 'razaosocial')
                ->sortable()
                ->searchable(),
            Column::make('Situação', 'situacao')
                ->sortable()
                ->searchable(),
            Column::make('Documento', 'documento')
                ->sortable()
                ->searchable(),
            Column::make('Criado', 'created_at_formatted', 'created_at')
                ->sortable(),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    |  Filtros
    |--------------------------------------------------------------------------
    */
    public function filters(): array
    {
        return [
            Filter::inputText('razaosocial')->operators(['contains']),
            Filter::inputText('fantasia')->operators(['contains']),
            Filter::inputText('documento')->operators(['contains']),
            Filter::select('situacao','situacao')
                ->dataSource(SituacaoClienteCollection::codes())
                ->optionValue('code')
                ->optionLabel('label'),
            Filter::datetimepicker('created_at'),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Listeners
    |--------------------------------------------------------------------------
    */
    protected function getListeners(){
        return array_merge(
            parent::getListeners(),
            [
                'rowActionEvent',
                'updateClienteTable',
               
            ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Update Table outside
    |--------------------------------------------------------------------------
    */
    public function updateClienteTable(){
        $this->fillData();
    }  

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    */
    public function actions(): array
    {
       return [
            Button::make('info', '<i class="ti ti-pencil ti-md"></i>')
                ->class('rounded border-0 btn-outline-primary')
                ->emit('editClient', ['id' => 'id']),
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('rounded border-0 btn-outline-danger')
                ->emit('deleteClient', ['id' => 'id']),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    */
    public function actionRules(): array
    {
        return [
            Rule::rows()
                ->when(fn(Cliente $cliente) => $cliente->situacao != 1)
                ->setAttribute('class', 'bg-light'),
        ];
    }
    
}
