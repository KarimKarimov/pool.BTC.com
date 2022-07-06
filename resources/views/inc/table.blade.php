@extends('layouts.app')

<div class="row layout-top-spacing" id="cancel-row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <div class="table-responsive mb-4 mt-4">
                <table id="html5-extension" class="table table-hover non-hover">
                    <thead>
                        <tr>
                            <th>Воркер</th>
                            <th>Итого</th>

                            @foreach($days as $day)
                                <th>{{$day}}</th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($data as $key => $worker)
                            <td>{{$worker['worker_name']}}</td>
                            <td>{{$worker['total']}}</td>
                                @foreach($data[$key]['data'] as $worker)
                                <td>{{$worker['hashrate']}}Th/s</td>
                                <td>{{$worker['sum_in_dey']}}руб.</td>
                                @endforeach
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>