@extends('layouts.main')

@section("content")
    <div class="bg-dark text-secondary px-3 py-4 text-center">
        <div class="py-4">
            <h1 class="display-5 fw-bold text-white">修改项目</h1>
            <div class="col-lg-6 mx-auto">
                <p class="fs-5 mb-4"></p>
                {{-- <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                  <button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold">Custom button</button>
                  <button type="button" class="btn btn-outline-light btn-lg px-4">Secondary</button>
                </div> --}}
            </div>
        </div>
    </div>
    <contract :contract="{{$contract}}"></contract>
    {{-- projects --}}
{{--    <div class="row g-4 py-5">--}}

{{--        <div class="col-md-8 ">--}}
{{--            <form method="POST" action="{{route('contract.store')}}" enctype="multipart/form-data" >--}}
{{--                {{ csrf_field() }}--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="name">名称*</label>--}}
{{--                    <input type="text" class="form-control" id="name" aria-describedby="name" name="name" value="{{$contract->name}}">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="year" class="form-label">合同年份<span class="required">*</span></label>--}}
{{--                    <input type="text" class="form-control" required id="year" placeholder="签订年份(2024)" name="year" value="{{$contract->year}}">--}}
{{--                </div>--}}
{{--                --}}{{--                合同编号--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="code">合同编号</label>--}}
{{--                    <input type="text" class="form-control" id="code" name="code">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="path" class="form-label">文件副本</label>--}}
{{--                    <input type="file" class="form-control" id="path" name="path">--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="signing_date" class="form-label">签订日期</label>--}}
{{--                    <input type="date" class="form-control" id="signing_date" name="signing_date">--}}
{{--                </div>--}}

{{--                <div class="mb-3">--}}
{{--                    <label for="user_id" class="form-label">签订人</label>--}}
{{--                    <select class="form-select" id="user_id" name="user_id">--}}
{{--                        <option selected>选择签订人</option>--}}
{{--                        @foreach($users as $user)--}}
{{--                            <option value="{{$user->id}}">{{$user->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}

{{--                <div class="mb-3">--}}
{{--                    --}}{{--                    合同金额--}}
{{--                    <label for="amount" class="form-label">合同金额</label>--}}
{{--                    <input type="text" class="form-control" id="amount" name="amount">--}}
{{--                </div>--}}

{{--                <div class="mb-3">--}}
{{--                    <label for="company_id" class="form-label">公司</label>--}}
{{--                    <select class="form-select" id="company_id" name="company_id">--}}
{{--                        <option selected>选择公司</option>--}}
{{--                        @foreach($companies as $company)--}}
{{--                            <option value="{{$company->id}}">{{$company->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                --}}{{--                质保金--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="fulfillmentDeposit">质保金</label>--}}
{{--                    <input type="text" class="form-control" id="fulfillmentDeposit" name="fulfillmentDeposit">--}}
{{--                </div>--}}
{{--                --}}{{--                经费来源--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="funding_id">经费来源</label>--}}
{{--                    <select class="form-select" id="funding_id" name="funding_id">--}}
{{--                        <option selected>选择经费来源</option>--}}
{{--                        @foreach($fundings as $funding)--}}
{{--                            <option value="{{$funding->id}}">{{$funding->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}

{{--                --}}{{--                $table->unsignedInteger('contract_type_id')->nullable(); //合同类型--}}
{{--                --}}{{--                $table->unsignedInteger('project_type_id')->nullable(); //项目类别--}}
{{--                --}}{{--                $table->unsignedInteger('parent_id')->nullable(); //父合同--}}
{{--                <div class="mb-3">--}}
{{--                    <label for="project_type_id">合同类型</label>--}}
{{--                    <select class="form-select" id="project_type_id" name="project_type_id">--}}
{{--                        <option selected>合同选项</option>--}}
{{--                        @foreach($contract_types as $type)--}}
{{--                            <option value="{{$type->id}}">{{$type->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}

{{--                <div class="mb-3">--}}
{{--                    <label for="contract_type_id">项目类别</label>--}}
{{--                    <select class="form-select" id="contract_type_id" name="contract_type_id">--}}
{{--                        <option selected>项目类别</option>--}}
{{--                        @foreach($project_types as $pt)--}}
{{--                            <option value="{{$pt->id}}">{{$pt->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}

{{--                <button type="submit" class="btn btn-primary">Create</button>--}}
{{--            </form>--}}
{{--        </div>--}}




{{--    </div>--}}




@endsection
