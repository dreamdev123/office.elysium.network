@if(!$ajaxRequest)
    @extends(ucfirst(getScope()).'.Layout.master')
@section('content')
    @endif
    @include('_includes.network_nav')
    <div class="portlet" style="margin-top: 40px;">
        <div class="row">
            <div class="col-sm-2 form-group">
                <label>{{ _mt($moduleId, 'GenealogyTree.Username') }}</label>
                <input class="form-control userFiller" type="text"
                       placeholder="{{ _mt($moduleId, 'GenealogyTree.enter_username') }}">
                <input type="hidden" name="filters[user_id]" id="user_id">
            </div>
            <div class="col-sm-10 portlet-body" style="height: auto; padding: 0;">
                {!! $content !!}
            </div>
        </div>
       
    </div>

    <script type="text/javascript">
        'use strict';

        //Adjust dropdown position
        function adjustPosition(elem, target) {
            elem.width(target.outerWidth()).slideDown().offset({
                left: target.offset().left,
                top: (target.offset().top + target.outerHeight())
            });
        }

        //Get users
        function getUsers(data) {
            var options = {
                limit: 10,
                action: 'getUsers'
            };
            options.data = data;

            return $.post("{{ route('user.api') }}", options);
        }


    </script>
    <style type="text/css">

        .treeWrapper {
            position: relative;
        }

        form.treeFilters {
            margin-bottom: 0px;
            position: relative;
            border-bottom: 2px solid #36c6d3 !important;
            display: block;
            padding: 0 !important;
            box-shadow: 3px 9px 10px rgba(68, 68, 68, 0.11);
        }

        .treeFilters input[type="text"] {
            padding: 5px;
            border: 1px solid #dddddd;
            outline: none !important;
            width: 100%;
        }

        .arrowNav:after {
            content: '';
            position: relative;
            width: 0px;
            display: block;
            margin: auto;
            border-top: 10px solid #36c6d3 !important;
            border: 10px solid rgba(255, 255, 255, 0.02);
            height: 0px;
        }

        .arrowNav {
            position: relative;
        }

        form.treeFilters > div {
            padding: 10px;
        }

        .ajaxDropDown .eachResult {
            padding: 6px;
            border-bottom: 1px solid #e5e5e5;
            cursor: pointer;
            color: black;
        }

        .ajaxDropDown {
            position: absolute;
            z-index: 999;
            display: none;
            max-width: 400px;
            background: white;
            border: 1px solid #dadada;
            border-bottom: 3px solid #36c6d3;
            box-shadow: 4px 6px 12px rgba(62, 62, 62, 0.28);
        }

        .ajaxNoResult i {
            color: black;
            font-size: 15px;
        }

        .ajaxNoResult {
            padding: 10px;
            font-size: 13px;
            color: #a5a5a5;
            font-style: italic;
        }

        .AvatarBlock.dropdown {
            float: right;
            background-color: transparent !important;
            margin-top: 19px;
        }

        .AvatarBlock.dropdown button.btn {
            background-color: transparent;
            border: solid 1px #eee;
            color: #888;
            padding: 10px 10px;
            font-size: 14px;
        }

        .AvatarBlock.dropdown button.btn span i {
            padding: 0px 4px;
        }

        .AvatarBlock ul.dropdown-menu {
        }

        .AvatarBlock ul.dropdown-menu h3.AvatarTitle {
            margin: 0;
            font-size: 15px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-weight: 400;
            color: #9e9898;
            white-space: nowrap;
            background: #eaedf2;
        }

        .AvatarBlock ul.dropdown-menu:after {
            border-bottom: 7px solid #eaedf2;
        }

        .AvatarBlock ul.dropdown-menu li {
            border-bottom: solid 1px #eeeeee96;
            padding: 8px 16px;
            color: #6f6f6f;
            text-decoration: none;
            display: block;
            clear: both;
            font-weight: 300;
            line-height: 18px;
            white-space: nowrap;
        }

        .AvatarBlock ul.dropdown-menu li span img {
            width: 15px;
        }

        .AvatarBlock ul.dropdown-menu li span label.avtColor {
            width: 15px;
            height: 15px;
            background-color: red;
            display: inline-block;
            margin: 0px;
        }

        /*----------------genealogy-scroll----------*/

        .genealogy-scroll::-webkit-scrollbar {
          width: 15px;
          height: 15px;
        }
        .genealogy-scroll::-webkit-scrollbar-track {
          border-radius: 10px;
          background-color: #e4e4e4;
        }
        .genealogy-scroll::-webkit-scrollbar-thumb {
          background: #212121;
          border-radius: 10px;
          transition: 0.5s;
          width: 150px;
        }
        .genealogy-scroll::-webkit-scrollbar-thumb:hover {
          background: #d5b14c;
          transition: 0.5s;
        }


        /*----------------genealogy-tree----------*/
        .genealogy-body{
          white-space: nowrap;
          overflow: auto;
          padding: 50px;
          min-height: 500px;
          padding-top: 10px;
          text-align: center;
          max-height: calc(100vh - 195px);
        }
        .genealogy-tree{
        display: inline-block;
        }
        .genealogy-tree ul {
          padding-top: 20px; 
          position: relative;
          padding-left: 0px;
          display: flex;
          justify-content: center;
        }
        .genealogy-tree li {
          float: left; text-align: center;
          list-style-type: none;
          position: relative;
          padding: 20px 5px 0 5px;
        }
        .genealogy-tree li::before, .genealogy-tree li::after{
          content: '';
          position: absolute; 
        top: 0; 
        right: 50%;
          border-top: 2px solid #ccc;
          width: 50%; 
        height: 18px;
        }
        .genealogy-tree li::after{
          right: auto; left: 50%;
          border-left: 2px solid #ccc;
        }
        .genealogy-tree li:only-child::after, .genealogy-tree li:only-child::before {
          display: none;
        }
        .genealogy-tree li:only-child{ 
          padding-top: 0;
        }
        .genealogy-tree li:first-child::before, .genealogy-tree li:last-child::after{
          border: 0 none;
        }
        .genealogy-tree li:last-child::before{
          border-right: 2px solid #ccc;
          border-radius: 0 5px 0 0;
          -webkit-border-radius: 0 5px 0 0;
          -moz-border-radius: 0 5px 0 0;
        }
        .genealogy-tree li:first-child::after{
          border-radius: 5px 0 0 0;
          -webkit-border-radius: 5px 0 0 0;
          -moz-border-radius: 5px 0 0 0;
        }
        .genealogy-tree ul ul::before{
          content: '';
          position: absolute; top: 0; left: 50%;
          border-left: 2px solid #ccc;
          width: 0; height: 20px;
        }
        .genealogy-tree li a{
          text-decoration: none;
          color: #666;
          font-family: arial, verdana, tahoma;
          font-size: 11px;
          display: inline-block;
          border-radius: 5px;
          -webkit-border-radius: 5px;
          -moz-border-radius: 5px;
        }

        .genealogy-tree li a:hover+ul li::after, 
        .genealogy-tree li a:hover+ul li::before, 
        .genealogy-tree li a:hover+ul::before, 
        .genealogy-tree li a:hover+ul ul::before{
          border-color:  #fbba00;
        }

        /*--------------memeber-card-design----------*/
        .member-view-box{
          padding:0px 20px;
          text-align: center;
          border-radius: 4px;
          position: relative;
          display: flex;
          justify-content: center;
        }
        .member-image{
          position: relative;
          text-align: center;
        }
        .member-image img{
          width: 60px;
          height: 60px;
          z-index: 1;
          border: 2px solid #c8d5d8;
          border-radius: 50% !important;
          padding: 4px;
          background: #fff;
        }
        .username-style {
          background-color: #5c519f;
          padding: 5px 10px;
          border-radius: 2px;
          margin-top: 5px;
          margin-bottom: 0;
          color: #fff;
          font-size: 12px!important;
        }

    </style>
    @if(!$ajaxRequest)
@endsection
@endif