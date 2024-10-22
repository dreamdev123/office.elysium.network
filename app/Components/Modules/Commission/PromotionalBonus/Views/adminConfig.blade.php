@foreach($scripts as $script)
    <script type="text/javascript" src="{{ $script }}"></script>
@endforeach
@foreach($styles as $style)
    <link href="{{ $style }}" rel="stylesheet" type="text/css"/>
@endforeach
<div class="row moduleConfig PromotionalBonus">
    <div class="col-sm-12">
        {!! Form::open(['route' => ['admin.module.configure',$moduleId],'class' => 'form-horizontal adminConfig','id' => 'adminConfig']) !!}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span aria-hidden="true"
                      class="icon-tag"></span> Promotional Bonus [PB]
            </div>
            <div class="panel-body">
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>{{ _mt($moduleId,'PromotionalBonus.To_Wallet') }}</label>
                    </div>
                    <div class="col-md-3">
                        <select class="walletSelect form-control input-medium" name="module_data[wallet]">
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->moduleId }}"
                                        @if($wallet->moduleId == $config->get('wallet')) selected @endif>{{ $wallet->registry['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label>Offer Start Date</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="offer_start_date form-control"
                               value="{{ $config->get('offer_start_date') }}" name="module_data[offer_start_date]"/>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Required IB</label>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" name="module_data[required_ib]"
                               value="{{ $config->get('required_ib') }}">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3">
                        <label class="control-label">With in Days</label>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" name="module_data[with_in_days]"
                               value="{{ $config->get('with_in_days') }}">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Amount</label>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" name="module_data[amount]"
                               value="{{ $config->get('amount') }}">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-offset-3 col-md-3">
                        <button type="button" value="{{ _mt($moduleId,'PromotionalBonus.Save') }}"
                                class="form-control ladda-button btn green button-submit" data-style="contract"
                                name="amount">{{ _mt($moduleId,'PromotionalBonus.Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    "use strict";

    $(function () {

        $('.offer_start_date').datepicker({
            format: 'yyyy-mm-dd'
        });

        var form = $('#adminConfig');
        var error1 = $('.alert-danger', form);
        var success1 = $('.alert-success', form);

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            var isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        var validator = form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                'module_data[wallet]': {
                    required: true,
                    number: true,
                },
                'module_data[offer_start_date]': {
                    required: true,
                },
                'module_data[required_ib]': {
                    required: true,
                    number: true,
                },
                'module_data[amount]': {
                    required: true,
                    number: true,
                },
                'module_data[with_in_days]': {
                    required: true,
                    number: true,
                },
            },
            messages: {
                'module_data[offer_start_date]': {
                    required: "Please enter offer start date",
                },
                'module_data[required_ib]': {
                    required: "Please enter required IB's",
                    number: "Please enter valid value",
                },
                'module_data[wallet]': "{{ _mt($moduleId,'PromotionalBonus.Please_select_wallet') }}",
                'module_data[amount]': {
                    required: "Please enter amount",
                    number: "Please enter valid amount",
                },
                'module_data[with_in_days]': {
                    required: "Please enter days",
                    number: "Please enter valid days",
                },
            },

            errorPlacement: function (error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
        });

        //Registration request

        $('.PromotionalBonus .button-submit').click(function () {

            $('.ajaxValidationError').removeClass('ajaxValidationError');

            var successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("{{ _mt($moduleId,'PromotionalBonus.Configuration_saved_sucessfully') }}");
            };

            if (!form.valid()) {
                Ladda.stopAll();
                return false;
            }

            var failCallBack = function (response) {
                Ladda.stopAll();
                var errors = response.responseJSON;

                for (var key in errors) {

                    if (key.split('.')[2])
                        var elemKey = 'module_data' + '[' + key.split('.')[1] + ']' + '[' + key.split('.')[2] + ']';
                    else
                        var elemKey = 'module_data' + '[' + key.split('.')[1] + ']';
                    $('#adminConfig [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    var errorOption = {};
                    errorOption[elemKey] = errors[key];
                    validator.showErrors(errorOption);
                }
            };

            var options = {
                form: form,
                actionUrl: "{{ route('admin.module.save',['module_id' => $moduleId]) }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });
    });

    //Small patch

    $('body').on('keyup click', '.ajaxValidationError input', function () {
        $(this).parent().removeClass('ajaxValidationError');
    });


    //select2
    $('select').select2({
        width: '170px'
    });
</script>

<style>
    .moduleConfig .panel-primary > .panel-heading {
        color: #4d4d4d;
        background-color: #eeeeee;
        border-color: #eeeeee;
        font-weight: 600;
    }

    .moduleConfig .panel-primary > .panel-heading {
        color: #919191;
    }

    .moduleConfig .panel-primary {
        border-color: #eeeeee;
    }

    .moduleConfig .mt-radio-inline {
        padding: 0px;
    }

    .moduleConfig input.form-control {
        margin-bottom: 10px;
    }

    .ajaxValidationError.has-error .help-block-error {
        opacity: 1 !important;
    }
</style>