@extends('layouts.main')


@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                {{$contract->name}}</h1>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">年份</div>
                    <div class="datagrid-content">{{$contract->year}}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">签订日期</div>
                    <div class="datagrid-content">{{$contract->signing_date}}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">签订人</div>
                    <div class="datagrid-content">{{$contract->user->name}}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">公司</div>
                    <div class="datagrid-content">{{$contract->company->name}}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">合同文件</div>
{{--                    显示文件--}}
                    <a href="{{asset('storage/'.$contract->path)}}" target="_blank" >查看</a>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">合同金额</div>
                    <div class="datagrid-content">
                        {{$contract->amount}} 元
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">履约保证金/质量保证金</div>
                    <div class="datagrid-content">
                        {{$contract->fulfillmentDeposit}}
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">支付状态</div>
                    <div class="datagrid-content">
                        <span class="status status-green"> {{$contract->isPayed()}} </span>
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">状态</div>
                    <div class="datagrid-content">
                        <span class="status status-green">{{$contract->status->name}} </span>
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">经费来源</div>
                    <div class="datagrid-content">
                       {{$contract->funding->name}}
                        <br>
                        {{$contract->funding->code}}
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">合同类型</div>
                    <div class="datagrid-content">
                        {{$contract->contract_type->name}}
                    </div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">项目类型</div>
                    <div class="datagrid-content">{{$contract->project_type->name}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
