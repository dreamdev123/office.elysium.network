@extends('User.Layout.master')
@section('content')
    <script type="text/javascript">
        $(".page-content").css("padding", "0px");
        $(".page-content").css("display", "flex");
        $(".page-content").css("background-color", "#384253");
        $(".mainBreadcrumb").css("display", "none");

        //Document ready scripts
       
    </script>
    <style>
        .page-content-wrapper .page-content {
            position: relative;
            padding: 0px !important;
        }

        .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active > a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li.active.open > a, .page-sidebar .page-sidebar-menu > li.active > a, .page-sidebar .page-sidebar-menu > li.active.open > a 
        {
            background-color: #00a6e0 !important;   
        }

        .active
        {
           background-color: #00a6e0; 
        }
    </style>
    <div class="lds-spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="row mt-5" style="margin-left: 30px;">
        <div class="col">
            <h3>Please Upgrade to IB to access this service</h3>
        </div>
    </div>
@endsection
