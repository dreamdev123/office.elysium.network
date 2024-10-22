<div class="heading">
    <h3>{{ _mt($moduleId,'AccountStatus.Activate_Deactivate') }}</h3>
</div>
@if(getAdminUser()->id != $userId)
    <form name="accountStatusForm" id="accountStatusForm_{{ $userId }}">
        <input type="hidden" name="member_id" id="member_id" value="{{ $userId }}">
        <div class="eachField row">
            <div class="col-md-3">
                <label>{{ _mt($moduleId,'AccountStatus.member_status') }}</label>
            </div>
            <div class="col-md-9">
                <select class="form-control select2" name="member_status" id="member_status_{{ $userId }}">
                    <option value="active"
                            @if($userStatusData['title'] =='active') selected @endif >{{ _mt($moduleId,'AccountStatus.active') }}</option>
                    <option value="inactive"
                            @if($userStatusData['title'] =='inactive') selected @endif >{{ _mt($moduleId,'AccountStatus.inactive') }}</option>
                    <option value="terminated"
                            @if($userStatusData['title'] =='terminated') selected @endif >{{ _mt($moduleId,'AccountStatus.terminated') }}</option>
                    <option value="custom"
                            @if($userStatusData['title'] =='custom') selected @endif >{{ _mt($moduleId,'AccountStatus.custom') }}</option>
                </select>
            </div>
        </div>
        <div class="eachField row">
            <div class="col-md-3">
                <label>{{ _mt($moduleId,'AccountStatus.Suspend_Login') }}</label>
            </div>
            <div class="col-md-1">
                <div class="pretty p-default">
                    <input type="checkbox" @if($userStatusData['title'] !='custom') disabled="true"
                           @endif class="md-check statusOptionCheck_{{ $userId }}" @if($userStatusData['login']) checked
                           @endif value="1"
                           id="login_status_{{ $userId }}" name="login_status">
                    <div class="state">
                        <label>{{ _mt($moduleId,'AccountStatus.null') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <span class="alertText">{{ _mt($moduleId,'AccountStatus.block_the_users_login_access_to_the_system') }}</span>
            </div>
        </div>
        <div class="eachField row">
            <div class="col-md-3">
                <label>{{ _mt($moduleId,'AccountStatus.suspend_Commission') }}</label>
            </div>
            <div class="col-md-1">
                <div class="pretty p-default">
                    <input type="checkbox" id="commission_status_{{ $userId }}" name="commission_status"
                           @if($userStatusData['title'] !='custom') disabled="true"
                           @endif class="md-check statusOptionCheck_{{ $userId }}"
                           @if($userStatusData['commission']) checked
                           @endif value="1">
                    <div class="state">
                        <label>{{ _mt($moduleId,'AccountStatus.null') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <span class="alertText">{{ _mt($moduleId,'AccountStatus.block_the_users_in_getting_commission_from_the_system') }}</span>
            </div>
        </div>
        <div class="eachField row">
            <div class="col-md-3">
                <label>{{ _mt($moduleId,'AccountStatus.suspend_Payout') }}</label>
            </div>
            <div class="col-md-1">
                <div class="pretty p-default">
                    <input type="checkbox" id="payout_status_{{ $userId }}" name="payout_status"
                           @if($userStatusData['title'] !='custom') disabled="true"
                           @endif class="md-check statusOptionCheck_{{ $userId }}"
                           @if($userStatusData['payout']) checked
                           @endif value="1">
                    <div class="state">
                        <label>{{ _mt($moduleId,'AccountStatus.null') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <span class="alertText">{{ _mt($moduleId,'AccountStatus.block_the_users_in_getting_Payout_from_the_system') }}</span>
            </div>
        </div>
        <div class="eachField row">
            <div class="col-md-3">
                <label>{{ _mt($moduleId,'AccountStatus.suspend_Fund_Transfer') }}</label>
            </div>
            <div class="col-md-1">
                <div class="pretty p-default">
                    <input type="checkbox" id="fund_transfer_status_{{ $userId }}" name="fund_transfer_status"
                           @if($userStatusData['title'] !='custom') disabled="true"
                           @endif class="md-check statusOptionCheck_{{ $userId }}"
                           @if($userStatusData['fund_transfer']) checked
                           @endif value="1">
                    <div class="state">
                        <label>{{ _mt($moduleId,'AccountStatus.null') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <span class="alertText">{{ _mt($moduleId,'AccountStatus.block_the_users_from_transferring_wallet_balance') }}</span>
            </div>
        </div>
        <div class="eachField row">
            <div class="col-md-3">
                <button type="button" class="btn btn-success ladda-button submitForm_{{ $userId }}"
                        data-style="contract">{{ _mt($moduleId,'AccountStatus.save') }}</button>
            </div>
        </div>
    </form>
@else
    {{ _mt($moduleId,'AccountStatus.Can_t_able_to_change_admin_status') }}
@endif
<script>"use strict";

    $(function () {
        Ladda.bind('.ladda-button');

        $('.select2').select2({
            width: '100%'
        });
    });
    $('#member_status_{{ $userId }}').change(function () {
        if ($(this).val() == 'terminated') {
            $('.statusOptionCheck_{{ $userId }}').prop('checked', true).attr('disabled', true);
        } else if ($(this).val() == 'active') {
            $('.statusOptionCheck_{{ $userId }}').prop('checked', false).attr('disabled', true);
        } else if ($(this).val() == 'inactive') {
            $('.statusOptionCheck_{{ $userId }}').prop('checked', false).attr('disabled', true);
            $('#commission_status_{{ $userId }}').prop('checked', true);
            $('#fund_transfer_status_{{ $userId }}').prop('checked', true);
            $('#payout_status_{{ $userId }}').prop('checked', true);
            $('#login_status_{{ $userId }}').prop('checked', false);
        } else if ($(this).val() == 'custom') {
            $('.statusOptionCheck_{{ $userId }}').prop('checked', false).attr('disabled', false);
        }

    });

    $('.submitForm_{{ $userId }}').click(function () {
        var formData = $('#accountStatusForm_{{ $userId }}').serialize();
        $.post('{{ scopeRoute("accountStatus.save") }}', formData, function (response) {
            toastr.success('{{ _mt($moduleId,'AccountStatus.member_status_updated') }}');
            Ladda.stopAll();
        }).fail(function () {
            Ladda.stopAll();
        });
    });
</script>
<style>
    .alertText {
        color: #999;
    }
</style>