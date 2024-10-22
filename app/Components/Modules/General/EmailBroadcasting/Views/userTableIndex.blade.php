@extends(ucfirst(getScope()).'.Layout.master')
@section('content')
    @if(getScope()=='user')
        @include('_includes.email_nav')
        <div class="heading" style="margin-top: 50px">
            <!-- <h3>{{ _mt($moduleId, 'EmailBroadCasting.email_broadcasting') }}</h3> -->
        </div>
    @endif()
    <div class="row">
        <div class="emailBroadcaster">
            <div class="col-md-12">
                <div class="selectAllUsers">
                    <label for="selectAllUser" class="selectAllUser-label">{{ _mt($moduleId,'EmailBroadCasting.select_all_user') }}</label>
                    <input type="checkbox" name="selectAllUser" class="selectAllUser" id="selectAllUser" style="margin-right: 20px;">
                    <label for="selectAllInsiderUser" class="selectAllInsiderUser-label">{{ _mt($moduleId,'EmailBroadCasting.select_all_insider_user') }}</label>
                    <input type="checkbox" name="selectAllInsiderUser" class="selectAllInsiderUser" id="selectAllInsiderUser">
                </div>
            </div>  
            <div class="col-md-12 userFilters"></div>
            <div class="col-md-12 userContainer"></div>
            <div class="emailSender">
                <div class="col-md-12">
                    <label>{{ _mt($moduleId,'EmailBroadCasting.subject') }}</label>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="subject" class="form-control mailSubject">
                    </div>
                </div>
                <div class="col-md-12">
                    <label>{{ _mt($moduleId,'EmailBroadCasting.compose') }}</label>
                </div>
                <div class="col-md-12">
                    <div class="form-group form-md-line-input form-md-floating-label">
                        <textarea class="mailcontent" name="content"
                                  placeholder="{{ _mt($moduleId,'EmailBroadCasting.Write_here') }}"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary sendBroadcast ladda-button" data-style="contract"
                            style="float: right">
                        {{ _mt($moduleId,'EmailBroadCasting.send') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedUserArray = [];
        $(function () {
            $('.selectAllUser').prettySwitch({
                checkedCallback: () => {
                    $('.selectAllInsiderUser').hide();
                    $('.selectAllInsiderUser-label').hide();
                    $('.userFilters').hide();
                    $('.userContainer').hide();
                },
                unCheckedCallback: () => {
                    $('.selectAllInsiderUser').show();
                    $('.selectAllInsiderUser-label').show();
                    $('.userFilters').show();
                    $('.userContainer').show();
                }
            });
            $('.selectAllInsiderUser').prettySwitch({
                checkedCallback: () => {
                    $('.selectAllUser').hide();
                    $('.selectAllUser-label').hide();
                    $('.userFilters').hide();
                    $('.userContainer').hide();
                },
                unCheckedCallback: () => {
                    $('.selectAllUser').show();
                    $('.selectAllUser-label').show();
                    $('.userFilters').show();
                    $('.userContainer').show();
                }
            });
            Ladda.bind('.ladda-button');
            loaduserFilters();
            $(".mailcontent").summernote({
                placeholder: "{{ _mt($moduleId,'EmailBroadCasting.Write_here') }}",
                height: 350
            });
        });

        $('.sendBroadcast').click(async function () {
            if (selectedUserArray.length || ($('.selectAllUser').prop("checked") == true) || ($('.selectAllInsiderUser').prop("checked") == true)) {
                if (!$('.mailcontent').summernote('isEmpty')) {
                    var selectAll = false;
                    var selectAllInsider = false;
                    var users = [];
                    if ($('.selectAllUser').prop("checked") == true)
                        selectAll = true;

                    if ($('.selectAllInsiderUser').prop("checked") == true)
                        selectAllInsider = true;

                    if (selectAll || selectAllInsider) {
                        var resultCustomerIDs = await getUserIds(selectAll, selectAllInsider);
                        users = JSON.parse(resultCustomerIDs.customer_ids);
                    } else {
                        users = selectedUserArray;
                    }

                    if (users.length) {
                        var nFlgs = 0;
                        var mailID = 0;
                        var startPoint = 0;
                        var lastmail = 'group';
                        for(item in users) {
                            var result = await send(users[item], mailID, lastmail, startPoint, selectAll, selectAllInsider);
                            console.log(result.status)
                            if (result.status || nFlgs == users.length) {
                                break;
                            } else {
                                nFlgs++;
                                mailID = result.mailID;
                                lastmail = result.lastmail;
                                startPoint = result.startPoint;
                                continue;
                            }
                        }
                        if (nFlgs == users.length || result.status) {
                            toastr.success("{{ _mt($moduleId,'EmailBroadCasting.Emails_has_been_sent_successfully') }}");
                            selectedUserArray.length = 0;
                            $('.mailcontent').summernote('code', '');
                            $('.mailSubject').val('');
                            $(".selectAllUser").prop("checked", false);
                            $(".selectAllInsiderUser").prop("checked", false);
                            $('.selectAllInsiderUser').show();
                            $('.selectAllInsiderUser-label').show();
                            $('.selectAllUser').show();
                            $('.selectAllUser-label').show();
                            fetchUserTable();
                            setTimeout(() => {
                                Ladda.stopAll();
                            });
                        } else {
                            setTimeout(() => {
                                Ladda.stopAll();
                            });
                            toastr.success("{{ _mt($moduleId,'EmailBroadCasting.cant_sent') }}");
                        }
                    } else {
                        setTimeout(() => {
                            Ladda.stopAll();
                        });
                        toastr.success("{{ _mt($moduleId,'EmailBroadCasting.cant_sent') }}");
                    }
                } else {
                    setTimeout(() => {
                        Ladda.stopAll();
                    });
                    toastr.error("{{ _mt($moduleId,'EmailBroadCasting.please_select_a_text') }}");
                }
            } else {
                setTimeout(() => {
                    Ladda.stopAll();
                });
                toastr.error("{{ _mt($moduleId,'EmailBroadCasting.please_select_at_least_one_user') }}");
            }
        });

        function loaduserFilters() {
            simulateLoading('.userFilters');
            $.get('{{ scopeRoute("email.broadcast.filters") }}', function (response) {
                $('.userFilters').html(response);
                $('.filterRequest').trigger('click')
            });
        }

        function fetchUserTable(route) {
            simulateLoading('.userContainer');
            route = route ? route : '{{ scopeRoute('email.broadcast.fetch') }}';
            $.get(route, $('.filterForm').serialize(), function (response) {
                $('.userContainer').html(response);
                Ladda.stopAll();
            })
        }

        function addUserToQueue(id) {
            selectedUserArray.push(id);
        }

        function deleteUserFromQueue(id) {
            selectedUserArray.pop(id);
        }

        function send(id, mailID, lastmail, startPoint, selectAll, selectAllInsider) {
            return $.ajax({
                url: '{{ scopeRoute("email.broadcast.send") }}',
                type: 'POST',
                data: {
                    customer_id: id,
                    mailcontent: $('.mailcontent').val(),
                    subject: $('.mailSubject').val(),
                    mailID: mailID,
                    lastmail: lastmail,
                    startPoint: startPoint,
                    selectAllUser: selectAll,
                    selectAllInsider: selectAllInsider
                }
            });
        }

        function getUserIds(selectAll, selectAllInsider) {
            return $.ajax({
                url: '{{ scopeRoute("email.broadcast.getUserIDs") }}',
                type: 'GET',
                data: {
                    selectAllUser: selectAll,
                    selectAllInsider: selectAllInsider
                }
            });
        }
    </script>
    <style>
        .page-content {
            background: #eee;
        }
    </style>
@endsection