@extends('layouts.master')
@section('title', 'Gerenciamento de Ações e Acessos do Sistema')
@section('styles')
<style>
    .card-browser-states .browser-states {
        margin-top: 2.14rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .h_card {
        min-height: 380px !important;
        max-height: 380px !important;
        overflow: auto !important;
    }
    .h_card_450 {
        min-height: 450px !important;
        max-height: 450px !important;
        overflow-y: hidden !important;
    }
</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Histórico de Ações e Acessos</h5>
                    <div class="action-btns">
                        <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                            <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                        </a>
                    </div>                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="cadastro-tab" data-bs-toggle="tab" href="#historico" aria-controls="cadastro" role="tab" aria-selected="true"><i class="fa-regular fa-pen-to-square me-2"></i> Histórico</a>
                                </li>
                                <li class="nav-item"> 
                                    <a class="nav-link" id="consulta-tab" data-bs-toggle="tab" href="#analise" aria-controls="consulta" role="tab" aria-selected="false"><i class="fa-solid fa-magnifying-glass me-2"></i> Análise</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="historico" aria-labelledby="historico-tab" role="tabpanel">
                                    @livewire('historico-table2')                            
                                </div>
                                <div class="tab-pane" id="analise" aria-labelledby="analise-tab" role="tabpanel">
                                    <div class="row">     
                                        <div class="col-md-12 col-12 mb-3">
                                            @livewire($canConsult ? 'l-w-historico' : 'aviso-tipo1')   
                                        </div>
                                    </div>
                                    <div class="row">                                            

                                        <div class="col-md-4 mb-3 align-items-stretch">
                                            <div class="card h_card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Uso por Navegadores</h5>
                                                </div>
                                                <livewire:donut-chart type="browser" title="Navegadores"/> 
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3 align-items-stretch">
                                            <div class="card h_card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Uso por Sistema Operacional</h5>
                                                </div>
                                                <livewire:donut-chart type="so" title="Sistemas Operacionais"/>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3 align-items-stretch">
                                            <div class="card h_card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Uso por Cliente</h5>
                                                </div>
                                                <livewire:donut-chart type="cliente_id" title="Ações dos Clientes"/>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 align-items-stretch">
                                            <div class="card h_card_450">
                                                <div class="card-body">
                                                    <h5 class="card-title">Ações dos Clientes</h5>
                                                </div>
                                                <livewire:area-chart type="acao" title="Ações dos Clientes"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3 align-items-stretch">
                                            <div class="card h_card_450">
                                                <div class="card-body">
                                                    <h5 class="card-title">Acesso dos Clientes</h5>
                                                </div>
                                                <livewire:line-chart type="acesso" title="Acessos dos Clientes"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('vendorscripts')
@endsection

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('#date-range', {
            mode: 'range',
            onChange: function (selectedDates, dateStr, instance) {
                // Emitir evento para o componente Livewire com as datas selecionadas
                Livewire.emit('dateRangeSelected', selectedDates);
            }
        });
    });
    const select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Escolha um item',
                dropdownParent: $this.parent(),
                templateResult: formatState 
            }).on('change', function (e) {
                if (this.id === 'cliente_id_select') {
                    Livewire.emit('updatedClienteId', this.value);
                }
                if(this.id === 'clienteapp_id_select'){
                    Livewire.emit('updatedClienteAppId', this.value);
                }
            });
        });
    }

    function formatState (state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl;

        if (state.element.parentElement.id === 'clienteapp_id_select') {
            baseUrl = "/storage/icone_uploads";
        } else {
            baseUrl = "/storage/image_uploads";
        }
        var imageName = $(state.element).data('image');
        if(imageName === null || imageName === ''){
            var text = state.text;
            var matches = text.match(/\b(\w)/g);
            var initials = matches.join('').substr(0, 2).toUpperCase();
            var $state = $(
                '<span><div style="display:inline-block;width:24px;height:24px;background-color:rgba(130, 134, 139, 0.12);color:#82868b;font-size:12px;text-align:center;line-height:24px;border-radius:50%;margin-right:5px;">'+initials+'</div>'+state.text+'</span>'
            );
        }else{
            var $state = $(
                '<span><img src="' + baseUrl + '/' + imageName + '" class="img-flag" width="24px"/> ' + state.text + '</span>'
            );    
        }
        return $state;
    };

    window.addEventListener('livewire:load', function () {


        Livewire.hook('message.processed', (message, component) => {
            $('.select2').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    placeholder: 'Escolha um item',
                    dropdownParent: $(this).parent(),
                    templateResult: formatState 
                });
            });
        });
        //GRAFICOS
        var components = document.querySelectorAll('[wire\\:id]');
        components.forEach(function (componentEl) {
            var componentId = componentEl.getAttribute('wire:id');
            var component = window.livewire.find(componentId);

            if (component.__instance.fingerprint.name === 'donut-chart') {
                var chartEl = componentEl.querySelector('div');
                var chartId = chartEl.getAttribute('id');
                var title = component.get('title');
                var series = component.get('series');
                var labels = component.get('labels');
                //var chartDataDiv = document.querySelector('#chart-data');
                //if (chartDataDiv) {
                //    chartDataDiv.textContent = JSON.stringify({ title, series, labels });
                //}
                var options = {
                    chart: {
                        type: 'donut',
                        width: '100%',
                        height: '270px' 
                    },
                    legend: {
                        position: 'left'
                    },
                    //title: {
                    //    text: component.get('title')
                    //},
                    labels: component.get('labels'),
                    series: component.get('series')
                };
                var chart = new ApexCharts(chartEl, options);
                chart.render();

               function updateTeste1(dados){
                    chart.updateSeries(dados.series);
                    chart.updateOptions({labels: dados.labels});
               }

                Livewire.on('atualizaRosca', (data) => {
                    if (data.chartId === chartId) {
                        updateTeste1(data);
                    }
                });             
            } else if (component.__instance.fingerprint.name === 'area-chart') {

                var areaChartEl = componentEl.querySelector('div');
                var areaChartId = areaChartEl.getAttribute('id');
                var areaOptions = {
                    series: [{
                        name: 'Ações',
                        data: component.get('series'),
                    }],
                    chart: {
                        height: 270,
                        type: 'radar',
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: component.get('labels')
                    }
                };
                var areaChart = new ApexCharts(areaChartEl, areaOptions);
                areaChart.render();

                function updateAreaChart(data) {
                    areaChart.updateSeries([{
                        name: 'Series 1',
                        data: data.series
                    }]);
                    areaChart.updateOptions({ xaxis: { categories: data.labels } });
                }

                Livewire.on('atualizaArea', (data) => {
                    if (data.chartId === areaChartId) {
                        updateAreaChart(data);                        
                    }
                });
            } else if (component.__instance.fingerprint.name === 'line-chart'){
                
                var lineChartEl = componentEl.querySelector('div');
                var lineChartId = lineChartEl.getAttribute('id');

                var series = component.get('series').map(function (serie) {
                    return {
                        name: serie.name,
                        data: serie.data
                    };
                });

                var lineOptions = {
                    series: series,
                    chart: {
                        height: 250,
                        width: '96%',
                        type: 'line',
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], 
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: component.get('labels')
                    }
                };
                var lineChart = new ApexCharts(lineChartEl, lineOptions);
                lineChart.render();

                function updateLineChart(data) {
                    var series = data.series.map(function (serie) {
                        return {
                            name: serie.name,
                            data: serie.data
                        };
                    });
                    lineChart.updateSeries(series);
                    lineChart.updateOptions({ xaxis: { categories: data.labels } });
                }
                
                Livewire.on('atualizaLine', (data) => {
                    if (data.chartId === lineChartId) {                        
                        updateLineChart(data);                        
                    }
                });

            }
        });

    });
    
    window.addEventListener('show-delete-confirmation', event => {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteItemConfirmed');
            }
        });
    });

   
   

</script>
@endsection