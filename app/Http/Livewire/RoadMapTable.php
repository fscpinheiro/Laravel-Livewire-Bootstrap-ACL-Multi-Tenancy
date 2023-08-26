<?php

namespace App\Http\Livewire;

use App\Models\RoadMap;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};
use WireUi\Traits\Actions;

use App\Models\SituacaoRoadMapCollection;
use App\Models\CategoriaRoadMapCollection;

final class RoadMapTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public int $perPage = 10;    
    public array $perPageValues = [10, 20, 40, 80, 100];

    public function setUp(): array
    {
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

   
    public function datasource(): Builder
    {
        return RoadMap::query();
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('feature')
            ->addColumn('category', function(RoadMap $roadmap){
                switch($roadmap->category){
                    case 0: return 'DataBase'; break;
                    case 1: return 'Backend'; break;
                    case 2: return 'Frontend'; break;
                    case 3: return 'Mobile'; break;
                    case 4: return 'Testes'; break;
                    case 5: return 'Infra'; break;
                    case 6: return 'Integração'; break;
                    case 7: return 'Segurança'; break;
                }
            })
            ->addColumn('status', function(RoadMap $roadmap){
                switch($roadmap->status){
                    case 0: return 'Desenvolvimento'; break;
                    case 1: return 'Planejamento'; break;
                    case 2: return 'Concluido'; break;
                    case 3: return 'Pausado'; break;
                    case 4: return 'Cancelado'; break;
                    case 5: return 'Teste'; break;
                    case 6: return 'Revisão'; break;                    
                }
            })
            ->addColumn('version')
            //->addColumn('status_label', fn ($situacao) => SituacaoRoadMapCollection::codes()->firstWhere('code', $situacao->code)['label'])
            ->addColumn('created_at_formatted', fn (RoadMap $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->addColumn('ecdf', fn (RoadMap $model) => Carbon::parse($model->estimated_completion_date)->format('d/m/Y H:i:s'))
            ->addColumn('cdf', fn (RoadMap $model) => Carbon::parse($model->completed_date)->format('d/m/Y H:i:s'))
            ->addColumn('notes');
    }

    public function columns(): array
    {
        return [
            Column::make('Feature', 'feature')
                ->sortable()
                ->searchable(),

            Column::make('Versão','version')
                ->sortable()
                ->searchable(),

            Column::make('Categoria', 'category')
                ->sortable()
                ->searchable(),

            Column::make('Situação', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Criado', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Estimativa', 'ecdf', 'estimated_completion_date')
                ->sortable(),

            Column::make('Concluido', 'cdf', 'completed_date')
                ->sortable(),

            Column::make('Descrição', 'notes')
                ->sortable()
                ->searchable(),

                


        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('feature')->operators(['contains']),
            Filter::inputText('version')->operators(['contains']),
            Filter::select('category','category')
                ->dataSource(CategoriaRoadMapCollection::codes())
                ->optionValue('code')
                ->optionLabel('label'),  
            Filter::select('status','status')
                ->dataSource(SituacaoRoadMapCollection::codes())
                ->optionValue('code')
                ->optionLabel('label'),            
            Filter::datetimepicker('estimated_completion_date'),
            Filter::datetimepicker('completed_date'),
            Filter::datetimepicker('created_at'),
            Filter::inputText('notes')->operators(['contains']),
        ];
    }

    public function updateRoadMapTable(){
        $this->fillData();
    }  

    protected function getListeners(){
        return array_merge(
            parent::getListeners(),
            [
                'rowActionEvent',
                'updateRoadMapTable',
               
            ]);
    }

    public function actions(): array
    {
       return [
            Button::make('info', '<i class="ti ti-pencil ti-md"></i>')
                ->class('rounded border-0 btn-outline-primary')
                ->emit('editFeature', ['id' => 'id']),
            Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                ->class('rounded border-0 btn-outline-danger')
                ->emit('deleteFeature', ['id' => 'id']),
        ];
    }
   
}
