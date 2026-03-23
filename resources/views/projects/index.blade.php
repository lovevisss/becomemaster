@extends('layouts.main')
@section("content")

<main class="container pb-4">

    <div class="page-header mt-3">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title ml-2">项目</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                    <div class="me-3 d-none d-md-block">
                        <div class="input-icon">
                            <input type="text" class="form-control" placeholder="Search…" />
                            <span class="input-icon-addon">
              <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-search"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                <path d="M21 21l-6 -6"></path>
              </svg>
            </span>
                        </div>
                    </div>
                    <a href="/contract/create" class="btn btn-primary">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-plus"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        添加项目
                    </a>
                    <a href="{{ route('bank_accounts.index') }}" class="btn btn-outline-primary ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-bank" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 21l18 0"></path>
                            <path d="M3 10l18 0"></path>
                            <path d="M5 6l7 -3l7 3"></path>
                            <path d="M4 10l0 11"></path>
                            <path d="M20 10l0 11"></path>
                            <path d="M8 14l0 3"></path>
                            <path d="M12 14l0 3"></path>
                            <path d="M16 14l0 3"></path>
                        </svg>
                        银行账户管理
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-3 card mb-4">
        <table class="table table-vcenter ">
            <thead>
            <tr>
                <th>名称</th>
                <th>签订日期</th>
                <th>合同类型</th>
                <th>项目类型</th>
                <th>公司名称</th>
                <th>合同金额</th>
                <th>当前状态</th>
                <th class="w-1">操作</th>
            </tr>
            </thead>
            <tbody>
            @php
                $temp = 0;
            @endphp
            @foreach($contracts as $contract)
{{--                如果$contract->year 和上一个元素的$contract->year 不同 则隔开并显示年份--}}

                @if($contract->year != $temp)
                    <tr><td class="bg-blue-lt">{{$contract->year}}</td></tr>
                    @php
                        $temp = $contract->year;
                    @endphp
                @endif
                <tr>
                    <td>{{$contract->name}}</td>
                    <td class="text-secondary">{{$contract->signing_date}}</td>
                    <td class="text-secondary">{{$contract->contract_type->name}}</td>
                    <td class="text-secondary">{{$contract->project_type->name}}</td>
                    <td class="text-secondary">{{$contract->company->name}}</td>
                    <td class="text-secondary"><i class="fa-solid fa-rmb"></i>{{$contract->amount}}</td>
                    <td class="text-secondary">
                        {{$contract->status->name}}
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('contract.edit', $contract->id) }}">Edit</a>
                        @if($contract)
                            <span class="mx-1">|</span>
                            <a href="{{ route('contract.show', $contract->id) }}">查看项目</a>
                        @endif
                    </td>
                </tr>

            @endforeach




            </tbody>
        </table>
    </div>




  </main>

@endsection
