@extends('layouts.main')

@section("content")
    <div class="bg-dark text-secondary px-3 py-4 text-center">
        <div class="py-4">
            <h1 class="display-5 fw-bold text-white">新建项目</h1>
            <div class="col-lg-6 mx-auto">
                <p class="fs-5 mb-4"></p>
                {{-- <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                  <button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold">Custom button</button>
                  <button type="button" class="btn btn-outline-light btn-lg px-4">Secondary</button>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- projects --}}
    <div class="row g-4 py-5">

        <div class="col-md-8">
            <form method="POST" action="{{route('contracts.store')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="mb-3">
                    <label for="">名称</label>
                    <input type="text" class="form-control" id="" aria-describedby="name" name="name">
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">合同年份<span class="required">*</span></label>
                    <input type="text" class="form-control" required id="year" placeholder="Enter Company Name" name="year" >
                </div>
                <div class="mb-3">
                    <label for="path" class="form-label">文件副本</label>
                    <input type="file" class="form-control" id="description" name="path">
                </div>
                <div class="mb-3">
                    <label for="signing_date" class="form-label">签订日期</label>
                    <input type="date" class="form-control" id="signing_date" name="signing_date">
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>




    </div>




@endsection
