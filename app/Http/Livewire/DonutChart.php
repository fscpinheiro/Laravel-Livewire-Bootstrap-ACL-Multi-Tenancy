<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Historico;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DonutChart extends Component
{
    public $type, $series, $labels, $chartId, $title, $cliente_id, $ini_rangedate, $end_rangedate;
    protected $listeners = ['updateCharts' => 'updateCharts']; 

    public function mount($type, $title=''){
        $this->type = $type;
        $this->chartId = 'chart-' . Str::random(8);
        $this->title = !empty($title) ? $title : 'Gráfico de Rosca';
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
        if ($this->type === 'cliente_id') {
            $data = DB::table('historicos')
                ->join('clientes', 'historicos.cliente_id', '=', 'clientes.id')
                ->select('clientes.fantasia', DB::raw('count(*) as total'))
                ->groupBy('clientes.fantasia')
                ->orderBy('total', 'desc');
            $labelColumn = 'fantasia';
        } else {
            $data = Historico::select($this->type, DB::raw('count(*) as total'))
                ->groupBy($this->type)
                ->orderBy('total', 'desc');
            $labelColumn = $this->type;
        }
        if (!empty($this->cliente_id)) {
            $data = $data->where('historicos.cliente_id', '=', $this->cliente_id);
        }    
        if (!empty($this->ini_rangedate) && !empty($this->end_rangedate)) {
            $data = $data->whereBetween('historicos.datahora', [$this->ini_rangedate.' 00:00:01', $this->end_rangedate.' 23:59:59']);
        }    
        $data = $data->get();
        $this->series = [];
        $this->labels = [];
        $otherTotal = 0;
        foreach ($data as $index => $item) {
            if ($index < $limit) {
                $this->series[] = $item->total;
                $label = Str::limit($item->{$labelColumn}, $labelLength);
                $this->labels[] = $label;
            } else {
                $otherTotal += $item->total;
            }
        }
        if ($otherTotal > 0) {
            $this->series[] = $otherTotal;
            $this->labels[] = 'Outros';
        }

        $this->emit('atualizaRosca', [ 'chartId' => $this->chartId, 'series' => $this->series, 'labels' => $this->labels ]);
        
    }

    public function render(){
        $this->dadosG();
        return view('livewire.donut-chart', [
            'title' => $this->title,
            'series' => $this->series,
            'labels' => $this->labels,
        ]);
    }
}
