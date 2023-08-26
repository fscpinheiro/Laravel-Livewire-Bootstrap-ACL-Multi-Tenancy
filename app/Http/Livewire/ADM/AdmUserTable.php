<?php

namespace App\Http\Livewire\ADM;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use App\Models\UserSituacaoCollection;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class AdmUserTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;
    public int $perPage = 10;    
    public array $perPageValues = [10, 20, 40, 80, 100];
    public $cliente_id;
    //SETUP
    public function setUp(): array{
        $this->showCheckBox();
        $this->cliente_id = Auth::user()->cliente_id;
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

    //Datasource
    public function datasource(): Builder{
        //return User::query();
        return User::where('cliente_id', $this->cliente_id);
    }

    //Relationship Search
    public function relationSearch(): array{
        return [];
    }

    //Add Column
    public function addColumns(): PowerGridColumns{
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('cliente_id', fn(User $cliente) => $cliente->cliente->fantasia)
            ->addColumn('role_id', fn(User $role) => $role->role->nome)
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn('situacao', function (User $model){
                switch ($model->situacao) {
                    case 0:
                        return 'cancelado';
                        break;
                    case 1:
                        return 'ativo';
                        break;
                    case 2:
                        return 'bloqueado';
                        break;
                    default: return 'ativo';
                }
            })
            ->addColumn('label_situacao', fn ($situacao) => UserSituacaoCollection::codes()->firstWhere('code', $situacao->code)['label'])
            ->addColumn('perfil')
            ->addColumn('fotoperfil', function(User $model){
                $file_target = '/storage/perfil_uploads/' . $model->perfil;
                $avatar = '<div class="avatar me-2"><img src="'.$file_target.'" alt="Avatar" class="rounded-circle"></div>';

                if (!empty($model->perfil)) {
                    return $avatar;
                } else {
                    $nome = $model->name;
                    preg_match_all('/\p{Lu}/u', $nome, $matches);
                    $iniciais = implode('', array_slice($matches[0], 0, 2));
                    return '<div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">'.$iniciais.'</span></div>';
                }
            })
            ->addColumn('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    //Include Columns
    public function columns(): array{
        return [
            Column::make('Perfil', 'fotoperfil'),
            Column::make('Nome', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Classe', 'role_id')
                ->sortable()
                ->searchable(),           
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Situação', 'situacao')
                ->sortable()
                ->searchable(),
            Column::make('Criado', 'created_at_formatted', 'created_at')
                ->sortable(),

        ];
    }

    //Filtros
    public function filters(): array{
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::select('role_id','role_id')
                ->dataSource(Role::all())
                ->optionValue('id')
                ->optionLabel('nome'),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::select('situacao','situacao')
                ->dataSource(UserSituacaoCollection::codes())
                ->optionValue('code')
                ->optionLabel('label'),
            Filter::datetimepicker('created_at'),
        ];
    }

    // Actions Method
    public function actions(): array{
       return [
            Button::make('edit', '<i class="ti ti-pencil ti-md"></i>')
                ->class(' rounded border-0 btn-outline-primary')
                ->emit('ADMeditUser',  ['id' => 'id']),
             Button::make('delete', '<i class="ti ti-trash ti-md"></i>')
                 ->class('btn-outline-danger rounded border-0')
                 ->emit('ADMdeleteUser', ['id'=>'id']),
        ];
    }

    //Listeners
    protected function getListeners(){
        return array_merge(
            parent::getListeners(),
            [
                'rowActionEvent',
                'updateADMUserTable',
               
            ]);
    }
    
    public function updateADMUserTable(){
        $this->fillData();
    } 

}
