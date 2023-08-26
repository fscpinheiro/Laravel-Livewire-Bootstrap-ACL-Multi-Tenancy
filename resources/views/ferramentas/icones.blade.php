@extends('layouts.master')
@section('title', 'Pacote de Icones')
@section('styles')

<style type="text/css">
    i.bi {
        font-size: 26px;
    }
    .icon-name{
        width: 90px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .icon-card {
        margin: 10px; /* Ajuste o valor para aumentar ou diminuir o espaço entre os cards */
        box-shadow: none;
    }
</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-header py-2">
                    <ul class="nav nav-pills card-header-pills ms-0" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-feather-tab" data-bs-toggle="pill" href="#pills-feather" role="tab" aria-controls="pills-feather" aria-selected="true">Feather</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-bootstrap-tab" data-bs-toggle="pill" href="#pills-bootstrap" role="tab" aria-controls="pills-bootstrap" aria-selected="false">Bootstrap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-material-tab" data-bs-toggle="pill" href="#pills-material" role="tab" aria-controls="pills-material"  aria-selected="false">Material Design</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-fawesome-tab" data-bs-toggle="pill" href="#pills-fawesome" role="tab" aria-controls="pills-fawesome"  aria-selected="false">FontAwesome</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane show active fade" id="pills-feather" role="tabpanel" aria-labelledby="pills-feather-tab">
                            <h4 class="card-title">Feather Icones <span id="qtfi" class="badge rounded-pill bg-info">0</span></h4>
                            <div class="col-12">
                                <div class="icon-search-wrapper my-3 mx-auto">
                                    <div class="mb-1 input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" class="form-control" id="icons-search" placeholder="Procurar Icone" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap" id="icons-container"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-bootstrap" role="tabpanel" aria-labelledby="pills-bootstrap-tab">
                            <h4 class="card-title">Bootstrap Icones <span id="qtbi" class="badge rounded-pill bg-info">0</span></h4>
                            <div class="col-12">
                                <div class="icon-search-wrapper my-3 mx-auto">
                                    <div class="mb-1 input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" class="form-control" id="bicons-search" placeholder="Procurar Icone" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-row" id="bicons-container"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-material" role="tabpanel" aria-labelledby="pill-material-tab">
                            <h4 class="card-title">Material Design Icones <span id="qtmd" class="badge rounded-pill bg-info">0</span></h4>
                            <div class="col-12">
                                <div class="icon-search-wrapper my-3 mx-auto">
                                    <div class="mb-1 input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" class="form-control" id="micons-search" placeholder="Procurar Icone" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-row" id="micons-container"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-fawesome" role="tabpanel" aria-labelledby="pill-fawesome-tab">
                            <h4 class="card-title">FontAwesome Icones <span id="qtfai" class="badge rounded-pill bg-info">0</span></h4>
                            <div class="col-12">
                                <div class="icon-search-wrapper my-3 mx-auto">
                                    <div class="mb-1 input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" class="form-control" id="faicons-search" placeholder="Procurar Icone" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap flex-row" id="faicons-container"></div>
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
@section('scripts')
<script type="text/javascript">
    feather.replace();
    var qtfi = Object.keys(feather.icons).length;
    $('#qtfi').html(qtfi);
    
</script>
@endsection