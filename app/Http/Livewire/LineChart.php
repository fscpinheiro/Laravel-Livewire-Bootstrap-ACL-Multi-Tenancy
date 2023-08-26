<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Historico;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LineChart extends Component
{
    public $type, $series, $labels, $chartId, $title, $cliente_id, $ini_rangedate, $end_rangedate;
    protected $listeners = ['updateCharts' => 'updateCharts'];

    public function mount($type, $title=''){
        $this->type = $type;
        $this->chartId = 'chart-' . Str::random(8);
        $this->title = !empty($title) ? $title : 'Gráfico de Linha';
    }

    public function getTitle(){
        return $this->title;        
    }

    public function updateCharts($filters){

        if (isset($filters['cliente_id'])) {
            $this->cliente_id = $filters['cliente_id'];
        }else{
            $this->cliente_id = "";
        }
        if (isset($filters['ini_rangedate'])) {
            $this->ini_rangedate = $filters['ini_rangedate'];
        }else{
            $this->ini_rangedate="";
        }
        if (isset($filters['end_rangedate'])) {
            $this->end_rangedate = $filters['end_rangedate'];
        }else{
            $this->end_rangedate = "";
        }    
        $this->render();
    }

    public function dadosG(){
        $limit = 5;
        $labelLength = 11;
        if ($this->type === 'acesso') {
            $data = DB::table('historicos')
                ->join('clientes', 'historicos.cliente_id', '=', 'clientes.id')
                ->select('clientes.fantasia', DB::raw('count(*) as total'), DB::raw('WEEK(historicos.datahora) as week'))
                ->groupBy('clientes.fantasia', 'week')
                ->orderBy('week', 'asc');
            $labelColumn = 'fantasia';
        }
        if (!empty($this->cliente_id)) {
            $data = $data->where('historicos.cliente_id', '=', $this->cliente_id);
        }    
        if (!empty($this->ini_rangedate) && !empty($this->end_rangedate)) {
            $data = $data->whereBetween('historicos.datahora', [$this->ini_rangedate.' 00:00:01', $this->end_rangedate.' 23:59:59']);
        }    
        $data = $data->get();
        $series = [];
        $this->labels = [];
        
        foreach ($data as $item) {
            if (!isset($series[$item->{$labelColumn}])) {
                $series[$item->{$labelColumn}] = [];
            }
            $series[$item->{$labelColumn}][] = $item->total;
            if (!in_array($item->week, $this->labels)) {
                $this->labels[] = $item->week;
            }
        }
        $this->series = array_map(function ($name, $data) {
            return [
                'name' => $name,
                'data' => $data
            ];
        }, array_keys($series), array_values($series));
        sort($this->labels);
        $this->labels = array_map(function ($week) {
            return 'S-' . $week;
        }, $this->labels);
        $this->series = array_map(function ($name, $data) {
            return [
                'name' => $name,
                'data' => $data
            ];
        }, array_keys($series), array_values($series));
        //$this->labels = array_map(function ($month) {
        //    return date('M', mktime(0, 0, 0, $month, 10));
        //},  $this->labels);
        $this->emit('atualizaLine', [ 'chartId' => $this->chartId, 'series' => $this->series, 'labels' => $this->labels ]);
        
    }

    public function render()
    {
        $this->dadosG();
        return view('livewire.line-chart');
    }
}
