@extends('layouts.app')
@section('title', 'Отчет по воркерам')


@section('content')
<div class="align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <div class="text-start">
        <h5>Отчет по воркерам:</h5>
        <form action="{{url('/')}}" method="POST" class="row align-items-center">
            @csrf
            <div class="form-group col-2 mb-4">
                <label for="rate">Тариф</label>
                <input type="number" value="{{$get['rate'] ?? ''}}" step="0.1" class="form-control" id="rate" name="rate" placeholder="0,0">
            </div>
            <div class="form-group col-2 mb-4">
                <label for="consumption">Потребление</label>
                <input type="number" value="{{$get['consumption'] ?? ''}}" step="0.1" class="form-control" id="consumption" name="consumption" placeholder="0,0">
            </div>
            <div class="form-group col-3 mb-4">
                <label for="exampleInputPassword1">Дата начала расчета</label>
                <input type="date" value="{{$get['date_start'] ?? ''}}" class="form-control" id="date_start" name="date_start" placeholder="Дата начала расчета">
            </div>
            <div class="form-group col-3 mb-4">
                <label for="exampleInputPassword1">Дата окончания расчета</label>
                <input type="date" value="{{$get['date_end'] ?? ''}}" class="form-control" id="date_end" name="date_end" placeholder="Дата окончания расчета">
            </div>
            <button type="submit" id="get_workerss" class="btn h-50 btn-primary col-2">Вперед!</button>
        </form>
    </div>
    @if(isset($days))
    <div class="layout-px-spacing " id="table_container">
        <div class="row layout-top-spacing" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-responsive mb-4 mt-4">
                        <table id="html5-extension" class="table table-hover non-hover">
                            <thead>
                                <tr>
                                    <th>Воркер</th>
                                    <th>Итого</th>
                                    @foreach($days as $dey)
                                    <th>{{$dey}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $workers_series=[]; $grand_total=0; @endphp
                                @foreach($data as $key => $item)
                                <tr>
                                    <td>{{$item['worker_name']}}</td>
                                    <td>{{round($item['total'],2)}} руб.</td>
                                    @php $grand_total += round($item['total'],2); @endphp
                                    @foreach($days as $dey)
                                    @php $worker=[];
                                    foreach($data[$key]['data'] as $k=> $value){
                                    if(date("Y-m-d", strtotime($value['created_at']))==$dey){
                                    $worker=$value;
                                    $workers_series[$key]['name']=$worker['worker_name'];  
                                    break;
                                    }
                                    }
                                    @endphp
                                    @php 
                                    $workers_series[$key]['data'][]=$worker['sum_in_dey'] ?? '0';
                                    @endphp
                                    <td>{{$worker['hashrate'] ?? '0'}}Th/s. {{$worker['sum_in_dey'] ?? '0'}}руб.</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h6 class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer mb-4">Общий итог: {{$grand_total}} руб.</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Диаграма</h4>
                    <div id="spline_area" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
         </div>
    </div>
    @endif
</div>



@endsection

@if(isset($days))

@section('script')
<script src="script.js"></script>
<script>
   
    var days = <?= json_encode($days) ?>;
    var data = <?= json_encode($workers_series) ?>;

    Options.set_series(data);
    Options.set_xaxis(days);
    Options.start();

</script>
@endsection

@endif