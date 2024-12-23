<div class="heading">
    <h3>{{ _t('member.profile') }}</h3>
</div>
<div class="profileManagement row">
    <div class="col-md-3 col-sm-3 profileNavWrapper">
        <div class="navInner">
            <div class="profileNav active" data-target="account">
                <i class="fa fa-at"></i>{{ _t('member.accountInfo') }}
            </div>
            <div class="profileNav" data-target="profile">
                <i class="fa fa-user"></i>{{ _t('member.profileInfo') }}
            </div>
            <div class="profileNav" data-target="hide_name">
                <i class="fa fa-user"></i>{{ _t('member.hidename') }}
            </div>
            <div class="profileNav" data-target="password">
                <i class="fa fa-lock"></i>{{ _t('member.securityInfo') }}
            </div>
            <div class="profileNav" data-target="social">
                <i class="fa fa-facebook"></i>{{ _t('member.socialInfo') }}
            </div>
            <div class="profileNav" data-target="downgrade_package">
                <i class="fa fa-user"></i>{{ _t('member.downgrade_ib_to_affiliate') }}
            </div>
            <div class="profileNav" data-target="re_instate_package">
                <i class="fa fa-user"></i>{{ _t('member.re_instate_package') }}
            </div>
            <div class="profileNav" data-target="aums">
                <i class="fa fa-user"></i>{{ _t('member.aums') }}
            </div>
            @if($user->username == 'admin')
            <div class="profileNav" data-target="unsubscribe_email">
                <i class="fa fa-user"></i>{{ _t('member.unsubscribe_email') }}
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-9 col-sm-9 profilePanelWrapper">
        <div class="profilePanelInner">
            <div class="profilePanel active" data-target="account">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.profileUsername'],'class' => 'form ajaxSave','id' => 'usernameForm' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.accountInfo') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <fieldset>
                            <legend>{{ _t('member.basic_info') }}</legend>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.customer_id') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->customer_id }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.username') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" placeholder="{{ _t('member.username') }}" name="username"
                                           value="{{ $user->username }}">
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.signup') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->created_at ?? '' }}</label>
                                </div>
                            </div>

                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.currentPackage') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->package ? $user->package->name : 'NA' }}</label>
                                </div>
                            </div>

                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.signupPackage') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->signupPackage ? $user->signupPackage->name : 'NA' }}</label>
                                </div>
                            </div>

                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.highest_achieved_rank') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $highestRank }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.actual_rank') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->rank ? $user->rank->rank->name : 'NA' }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.sponsor') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->sponsor()->username }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.parent') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->parent()->username }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.expiry_date') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->expiry_date }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.register_paid') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>
                                        @if($register_pay_status)
                                            {{ _t('member.paid') }}
                                        @else
                                            {{ _t('member.notPaid') }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.subscribe_paid') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>
                                        @if($subscribe_pay_status)
                                            {{ _t('member.paid') }}
                                        @else
                                            {{ _t('member.notPaid') }}
                                        @endif
                                    </label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#usernameSaveModal_{{ $user->id }}" style="min-width: 100px">
                                        {{ _t('member.save') }}
                                    </button>
                                </div>
                            </div>

                            {{--Modal--}}

                            <div class="modal fade" id="usernameSaveModal_{{ $user->id }}" tabindex="-1" role="dialog"
                                 aria-labelledby="usernameSaveModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ _t('member.save_confirm') }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn green ladda-button usernameSave"
                                                    data-style="contract" data-dismiss="modal">
                                                {{ _t('member.save') }}
                                            </button>
                                            <button type="button" class="btn default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>{{ _t('member.placement_info') }}</legend>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.global_level') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->repoData->level }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.sponsorBasedLevel') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ $user->repoData->level - (isset($user->repoData->sponsorUser->repoData) ? $user->repoData->sponsorUser->repoData->level : 0) }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.position') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>
                                        @if($user->repoData->position == 1) Left @else Right @endif</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="profile">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.profileProfile'],'class' => 'form ajaxSave','id' => 'profileForm' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.personal') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.country') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="country_id" id="country_list"
                                        class="form-control" style="width:100%;">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                                @if($country['id'] == $user->metaData->country_id ) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.firstName') }}<span class="required"
                                                                         aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.firstName') }}" name="firstname"
                                       value="{{ $user->metaData->firstname }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.lastName') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.lastName') }}" name="lastname"
                                       value="{{ $user->metaData->lastname }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.dateOfBirth') }}<span class="required"
                                                                           aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="datePicker dob" data-date-format="yyyy-mm-dd" name="dob"
                                       value="{{ $user->metaData->dob }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>Passport Number<span class="required"
                                                            aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="Passport Number" name="passport_no"
                                       onkeyup="this.value = this.value.toUpperCase();"
                                       value="{{ $user->metaData->passport_no }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.gender') }}<span class="required"
                                                                      aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7 mt-radio-inline" style="padding: 0px 10px;">
                                <label class="mt-radio">
                                    <input type="radio" name="gender" id="optionsRadios25" value="M"
                                           @if($user->MetaData->gender == 'M') checked @endif> {{ _t('member.male') }}
                                    <span></span>
                                </label>
                                <label class="mt-radio">
                                    <input type="radio" name="gender" id="optionsRadios26" value="F"
                                           @if($user->MetaData->gender == 'F') checked @endif> {{ _t('member.female') }}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.nationality') }}<span class="required"
                                                                           aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="nationality" id="country_list"
                                        class="form-control" style="width:100%;">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                                @if($country['id'] == $user->metaData->nationality ) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.place_of_birth') }}<span class="required"
                                                                              aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="place_of_birth" id="country_list"
                                        class="form-control" style="width:100%;">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                                @if($country['id'] == $user->metaData->place_of_birth ) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.date_of_passport_issue') }}<span class="required"
                                                                                      aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.date_of_passport_issue') }}"
                                       name="date_of_passport_issuance"
                                       value="{{ $user->metaData->date_of_passport_issuance }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.country_of_passprt_issue') }}<span class="required"
                                                                                        aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="country_of_passport_issuance" id="country_list"
                                        class="form-control" style="width:100%;">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                                @if($country['id'] == $user->metaData->country_of_passport_issuance ) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.passport_expirition_date') }}<span class="required"
                                                                                        aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.passport_expirition_date') }}"
                                       name="passport_expirition_date"
                                       value="{{ $user->metaData->passport_expirition_date }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.email') }}<span class="required"
                                                                     aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.email') }}" name="email"
                                       value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.phone') }}<span class="required"
                                                                     aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.phone') }}" name="phone"
                                       value="{{ $user->phone }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.street_name') }}<span class="required"
                                                                           aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.street_name') }}" name="street_name"
                                       value="{{ $user->metaData->street_name }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.house_no') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.house_no') }}" name="house_no"
                                       value="{{ $user->metaData->house_no }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.zipcode') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.zipcode') }}" name="postcode"
                                       value="{{ $user->metaData->postcode }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.country') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <select name="address_country" id="country_list"
                                        class="form-control" style="width:100%;">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['id'] }}"
                                                @if($country['id'] == $user->metaData->address_country ) selected @endif>{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.city') }}<span class="required"
                                                                    aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.city') }}" name="city"
                                       value="{{ $user->metaData->city }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.additional_information') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.additional_information') }}"
                                       name="additional_info"
                                       value="{{ $user->metaData->additional_info }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>Company Name</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="Company Name"
                                       name="company_name"
                                       value="{{ $user->metaData->company_name }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>Company Registration NR</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="Company Registration NR"
                                       name="company_registration_nr"
                                       value="{{ $user->metaData->company_registration_nr }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>Company Address</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="Company Address"
                                       name="company_address"
                                       value="{{ $user->metaData->company_address }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>Confirm You are UBO-Director</label>
                            </div>
                            <div class="col-md-7 mt-radio-inline" style="padding: 0px 10px;">
                                <label class="mt-radio">
                                    <input type="checkbox" name="company_ubo_director" id="optionsRadios25"
                                           @if($user->MetaData->company_ubo_director == 1) checked @endif> Confirm
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#profileSaveModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.save') }}
                                </button>
                            </div>
                        </div>

                        {{--Modal--}}

                        <div class="modal fade" id="profileSaveModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="profileSaveModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.save_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button profileSave"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.save') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="hide_name">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.profileHidename'],'class' => 'form ajaxSave','id' => 'hideName' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.hidename') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.hidename') }}<span class="required"
                                                                      aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7 mt-radio-inline" style="padding: 0px 10px;">
                                <label class="mt-radio">
                                    <input type="radio" name="hide_name" id="optionsRadios25" value="0"
                                           @if($user->hide_name == 0) checked @endif> {{ _t('member.show') }}
                                    <span></span>
                                </label>
                                <label class="mt-radio">
                                    <input type="radio" name="hide_name" id="optionsRadios26" value="1"
                                           @if($user->hide_name == 1) checked @endif> {{ _t('member.hidden') }}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#hidenameModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.save') }}
                                </button>
                            </div>
                        </div>

                        {{--Modal--}}

                        <div class="modal fade" id="hidenameModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="profileSaveModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.save_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button hidenameSave"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.save') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="password">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.profilePassword'],'class' => 'form ajaxSave','id' => 'passwordForm' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.changePassword') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.password') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="password" name="password" id="password{{$user->id}}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.confirmPassword') }}<span class="required"
                                                                               aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="password" name="password_confirmation">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#passwordSaveModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.save') }}
                                </button>
                            </div>
                        </div>

                        {{--Modal--}}

                        <div class="modal fade" id="passwordSaveModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="passwordSaveModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.save_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button passwordSave"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.save') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="social">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.profileSocial'],'class' => 'form ajaxSave','id' => 'social']) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.social_precense') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.about_me') }}</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="profileAddress" placeholder="{{ _t('member.about_me') }}"
                                          name="about_me">{{ $user->metaData->about_me }}</textarea>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.faebook') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.faebook') }}" name="facebook"
                                       value="{{ $user->metaData->facebook }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.twitter') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.twitter') }}" name="twitter"
                                       value="{{ $user->metaData->twitter }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.linkedIn') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.linkedIn') }}" name="linked_in"
                                       value="{{ $user->metaData->linked_in }}">
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.googlePlus') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.googlePlus') }}" name="google_plus"
                                       value="{{ $user->metaData->google_plus }}">
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="eachField row">
                    <div class="col-md-5">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#socialSaveModal_{{ $user->id }}"
                                style="min-width: 100px">
                            {{ _t('member.save') }}
                        </button>
                    </div>
                </div>

                {{--Modal--}}

                <div class="modal fade" id="socialSaveModal_{{ $user->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="socialSaveModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>{{ _t('member.save_confirm') }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn green ladda-button socialSave"
                                        data-style="contract" data-dismiss="modal">
                                    {{ _t('member.save') }}
                                </button>
                                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profilePanel" data-target="downgrade_package">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.downgradePackage'],'class' => 'form ajaxSave','id' => 'downgrade_package' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <input type="hidden" name="username" value="{{ $user->username }}" id="downgradeUserPackage_{{ $user->id }}" >
                    <h3 class="mfkToggle">{{ _t('member.downgrade_ib_to_affiliate') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.signup') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->created_at ?? '' }}</label>
                            </div>
                        </div>

                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.currentPackage') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->package ? $user->package->name : 'NA' }}</label>
                            </div>
                        </div>

                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.signupPackage') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->signupPackage ? $user->signupPackage->name : 'NA' }}</label>
                            </div>
                        </div>
                        @if($user->username != 'admin')
                        @if($user->package->slug != 'affiliate' && $user->package->slug != 'client')
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#downgradePackageModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.downgrade') }}
                                </button>
                            </div>
                        </div>
                        @endif
                        @endif
                        {{--Modal--}}

                        <div class="modal fade" id="downgradePackageModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="downgradePackageModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.downgrade_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button downgradePackage"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.downgrade') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="re_instate_package">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.reInstatePackage'],'class' => 'form ajaxSave','id' => 're_instate_package' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <input type="hidden" name="username" value="{{ $user->username }}" >
                    <h3 class="mfkToggle">{{ _t('member.re_instate_package') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.signup') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->created_at ?? '' }}</label>
                            </div>
                        </div>

                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.currentPackage') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->package ? $user->package->name : 'NA' }}</label>
                            </div>
                        </div>

                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.signupPackage') }}</label>
                            </div>
                            <div class="col-md-7">
                                <label>{{ $user->signupPackage ? $user->signupPackage->name : 'NA' }}</label>
                            </div>
                        </div>
                        @if($user->username != 'admin')
                        @if($user->package->slug == 'affiliate')
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#reInstatePackageModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.re_instate') }}
                                </button>
                            </div>
                        </div>
                        @endif
                        @endif
                        {{--Modal--}}

                        <div class="modal fade" id="reInstatePackageModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="reInstatePackageModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.re_instate_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button reInstatePackage"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.re_instate') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="profilePanel" data-target="aums">
                <div class="profileSection mfkToggleWrap">
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <h3 class="mfkToggle">{{ _t('member.equiti_info') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <fieldset>
                            <legend>{{ _t('member.basic_info') }}</legend>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.aum') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ isset($investment_client) ? $investment_client->profit->invested_amount : '0' }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.equity') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ isset($investment_client) ? $investment_client->profit->equity : '0' }}</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <h3 class="mfkToggle" style="margin-top: 30px;">{{ _t('member.multibank_info') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <fieldset>
                            <legend>{{ _t('member.basic_info') }}</legend>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.aum') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ isset($investment_client) ? $investment_client->profit->multi_invested_amount : '0' }}</label>
                                </div>
                            </div>
                            <div class="eachField row">
                                <div class="col-md-5">
                                    <label>{{ _t('member.equity') }}</label>
                                </div>
                                <div class="col-md-7">
                                    <label>{{ isset($investment_client) ? $investment_client->profit->multi_equity : '0' }}</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="profilePanel" data-target="unsubscribe_email">
                <div class="profileSection mfkToggleWrap">
                    {!! Form::open(['route' =>  ['management.members.unSubscribeEmail'],'class' => 'form ajaxSave','id' => 'unSubscribeEmail' . $user->id]) !!}
                    <input type="hidden" name="userId" value="{{ $user->id }}" readonly>
                    <input type="hidden" name="username" value="{{ $user->username }}" >
                    <h3 class="mfkToggle">{{ _t('member.unsubscribe_email') }}</h3>
                    <div class="profileSectionBody toggleBody" style="display: block">
                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.email') }}<span class="required"
                                                                     aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" placeholder="{{ _t('member.email') }}" name="email"
                                       value="">
                            </div>
                        </div>

                        <div class="eachField row">
                            <div class="col-md-5">
                                <label>{{ _t('member.status') }}<span class="required" aria-required="true"> * </span></label>
                            </div>
                            <div class="col-md-7 mt-radio-inline" style="padding: 0px 10px;">
                                <label class="mt-radio">
                                    <input type="radio" name="subscribe_status" id="optionsRadios25" value="0" checked > {{ _t('member.unsubscribe') }}
                                    <span></span>
                                </label>
                                <label class="mt-radio">
                                    <input type="radio" name="subscribe_status" id="optionsRadios26" value="1" > {{ _t('member.subscribe') }}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="eachField row">
                            <div class="col-md-5">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#unSubscribeEmailModal_{{ $user->id }}" style="min-width: 100px">
                                    {{ _t('member.save') }}
                                </button>
                            </div>
                        </div>
                        {{--Modal--}}

                        <div class="modal fade" id="unSubscribeEmailModal_{{ $user->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="unSubscribeEmailModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ _t('member.save_confirm') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn green ladda-button unSubscribeEmailSave"
                                                data-style="contract" data-dismiss="modal">
                                            {{ _t('member.save') }}
                                        </button>
                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .eachField .required {
        color: #e02222;
        font-size: 12px;
        padding-left: 2px;
    }
</style>
<script>
    "use strict";

    $('[data-target="account"]').on('keyup', 'input', function () {
        $('[data-target="#usernameSaveModal"]').text('{{ _t('member.save') }}');
    });

    $('[data-target="profile"]').on('keyup', 'input', function () {
        $('[data-target="#profileSaveModal"]').text('{{ _t('member.save') }}');
    });

    $('[data-target="profile"]').on('change', 'select', function () {
        $('[data-target="#profileSaveModal"]').text('{{ _t('member.save') }}');
    });

    $('[data-target="hide_name"]').on('change', 'select', function () {
        $('[data-target="#hidenameModal"]').text('{{ _t('member.save') }}');
    });

    $('[data-target="password"]').on('keyup', 'input', function () {
        $('[data-target="#passwordSaveModalLabel"]').text('{{ _t('member.save') }}');
    });

    $('[data-target="social"]').on('keyup', 'input', function () {
        $('[data-target="#usernameSaveModal"]').text('{{ _t('member.save') }}');
    });

    $('.profileNav').click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        let target = $('.profilePanel[data-target="' + $(this).attr('data-target') + '"]');
        target.addClass('active').siblings().removeClass('active');
    });

    var genderChange = false;
    $('[name="gender"]').on('change', function() {
        genderChange = true;
    });

    $(function () {
        Ladda.bind('.ladda-button');

        $('select').select2({
            width: '100%'
        });

        $('.dob').datepicker();

        //Username Form
        let usernameForm = $('#usernameForm{{ $user->id }}');

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            let isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        let usernameValidator = usernameForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {

                'username': {
                    required: true,
                },
            },
            messages: {
                'username': {
                    required: "{{ _t('member.please_enter_username') }}"
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


        //Username Save
        $('.usernameSave').click(function () {
            $('.ajaxValidationError').removeClass('ajaxValidationError');

            let successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("{{ _t('member.username_updated_successfully') }}");
                $('[data-target="#usernameSaveModal"]').text('{{ _t('member.saved') }}');
                window.onbeforeunload = null;
            };

            if (!usernameForm.valid()) {
                Ladda.stopAll();
                return false;
            }

            let failCallBack = function (response) {
                Ladda.stopAll();
                let errors = response.responseJSON;

                for (let key in errors) {
                    let elemKey = key;
                    $('#usernameForm [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    let errorOption = {};
                    errorOption[elemKey] = errors[key];
                    usernameValidator.showErrors(errorOption);
                }
            };

            let options = {
                form: usernameForm,
                actionUrl: "{{ route("management.members.profileUsername") }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });

        //Profile Save
        let profileForm = $('#profileForm{{ $user->id }}');

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            let isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        let ProfileValidator = profileForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                'firstname': {
                    required: true
                },
                'lastname': {
                    required: true
                },
                'dob': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true
                },
                'phone': {
                    required: true
                },
                'gender': {
                    required: true
                },
                'city': {
                    required: true
                },
                'nationality': {
                    required: true
                },
                'place_of_birth': {
                    required: true
                },
                'date_of_passport_issuance': {
                    required: true
                },
                'country_of_passport_issuance': {
                    required: true
                },
                'passport_expirition_date': {
                    required: true
                },
                'street_name': {
                    required: true
                },
                'house_no': {
                    required: true
                },
                'postcode': {
                    required: true
                },

            },
            messages: {
                'firstname': {
                    required: "{{ _t('member.please_enter_first_name') }}"
                },
                'lastname': {
                    required: "{{ _t('member.please_enter_last_name') }}"
                },
                'dob': {
                    required: "{{ _t('member.please_enter_dob') }}"
                },
                'email': {
                    required: "{{ _t('member.please_enter_email') }}",
                    email: "{{ _t('member.please_enter_valid_email') }}",
                },
                'phone': {
                    required: "{{ _t('member.please_enter_phone') }}",
                },
                'gender': {
                    required: "{{ _t('member.please_enter_gender') }}",
                },
                'city': {
                    required: "{{ _t('member.please_enter_city') }}",
                },
                'nationality': {
                    required: "{{ _t('member.please_enter_nationality') }}",
                },
                'place_of_birth': {
                    required: "{{ _t('member.please_enter_place_of_birth') }}",
                },
                'date_of_passport_issuance': {
                    required: "{{ _t('member.please_enter_date_of_passport_issuance') }}",
                },
                'country_of_passport_issuance': {
                    required: "{{ _t('member.please_enter_country_of_passport_issuance') }}",
                },
                'passport_expirition_date': {
                    required: "{{ _t('member.please_enter_passport_expirition_date') }}",
                },
                'street_name': {
                    required: "{{ _t('member.please_enter_street_name') }}",
                },
                'house_no': {
                    required: "{{ _t('member.please_enter_house_no') }}",
                },
                'postcode': {
                    required: "{{ _t('member.please_enter_postcode') }}",
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

        $('.profileSave').click(function () {
            $('.ajaxValidationError').removeClass('ajaxValidationError');

            let successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("{{ _t('member.profile_updated_successfully') }}");
                $('[data-target="#profileSaveModal"]').text('{{ _t('member.saved') }}');
                if (genderChange) {
                    window.onbeforeunload = null;
                    location.reload();
                }
                window.onbeforeunload = null;
            };

            if (!profileForm.valid()) {
                Ladda.stopAll();
                return false;
            }

            let failCallBack = function (response) {
                Ladda.stopAll();
                let errors = response.responseJSON;

                for (let key in errors) {
                    let elemKey = key;
                    $('#profileForm [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    let errorOption = {};
                    errorOption[elemKey] = errors[key];
                    ProfileValidator.showErrors(errorOption);
                }
            };

            let options = {
                form: profileForm,
                actionUrl: "{{ route("management.members.profileProfile") }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });

        //Profile Save
        let hidenameForm = $('#hideName{{ $user->id }}');

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            let isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        let hidenameValidator = hidenameForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                'hide_name': {
                    required: true
                }
            },
            messages: {
                'hide_name': {
                    required: "{{ _t('member.please_enter_hidename') }}",
                }
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

        $('.hidenameSave').click(function () {
            $('.ajaxValidationError').removeClass('ajaxValidationError');

            let successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("{{ _t('member.profile_updated_successfully') }}");
                $('[data-target="#hidenameModal"]').text('{{ _t('member.saved') }}');
                window.onbeforeunload = null;
            };

            if (!hidenameForm.valid()) {
                Ladda.stopAll();
                return false;
            }

            let failCallBack = function (response) {
                Ladda.stopAll();
                let errors = response.responseJSON;

                for (let key in errors) {
                    let elemKey = key;
                    $('#hidenameForm [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    let errorOption = {};
                    errorOption[elemKey] = errors[key];
                    hidenameValidator.showErrors(errorOption);
                }
            };

            let options = {
                form: hidenameForm,
                actionUrl: "{{ route("management.members.profileHidename") }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });


        //password form
        let passwordForm = $('#passwordForm{{ $user->id }}');

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            let isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        let validator = passwordForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                'password': {
                    required: true,
                    ajaxValidate: true,
                },
                'confirm_password': {
                    required: true,
                    equalTo: "#password{{$user->id}}",
                    ajaxValidate: true,
                },
            },
            messages: {
                'password': {
                    required: "{{ _t('member.please_enter_password') }}",
                },
                'confirm_password': {
                    required: "{{ _t('member.Please_enter_confirm_password') }}",
                    equalTo: "{{ _t('member.Password_missmatch') }}",
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

        //SecurityInfo
        $('.passwordSave').click(function () {
            $('.ajaxValidationError').removeClass('ajaxValidationError');

            let successCallBack = function (response) {
                Ladda.stopAll();
                toastr.success("{{ _t('members.password_updated_successfully') }}");
                $('[data-target="#passwordSaveModalLabel"]').text('{{ _t('member.saved') }}');
                window.onbeforeunload = null;
            };

            if (!passwordForm.valid()) {
                Ladda.stopAll();
                return false;
            }

            let failCallBack = function (response) {
                Ladda.stopAll();
                let errors = response.responseJSON;

                for (let key in errors) {
                    let elemKey = key;
                    $('#passwordForm [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    let errorOption = {};
                    errorOption[elemKey] = errors[key];
                    validator.showErrors(errorOption);
                }
            };

            let options = {
                form: passwordForm,
                actionUrl: "{{ route("management.members.profilePassword") }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });

        $('.socialSave').click(function () {
            let formData = $('#social').serialize();

            $.post('{{ route("management.members.profileSocial") }}', formData, function (response) {
                toastr.success('Profile updated sucessfully');
                $('[data-target="#socialSaveModal"]').text('{{ _t('member.save') }}');
                window.onbeforeunload = null;
            });
        });

        $('.payoutSave').click(function () {
            let formData = $('#payout').serialize();

            $.post('{{ route("management.members.profilePayout") }}', formData, function (response) {
                toastr.success('Profile updated sucessfully');
            });
        });

        $('#country_list').change(function () {
            getStates($(this).val());
        });

        $('#state_list').change(function () {
            getCities($(this).val());
        });

        /*// init editable field
        $('form.panelForm input[type="text"], form.panelForm textarea').each(function () {
            $(this).editableField();
        });*/


        //I-Payout Account Update
        let ipay_update = $('#ipay_update{{ $user->id }}');

        $.validator.addMethod('ajaxValidate', function (value, element, param) {
            let isValid = !$(element).parent().hasClass('ajaxValidationError');
            return isValid; // return bool here if valid or not.
        }, function (param, element) {
            return $(element).siblings('.help-block-error').text();
        });

        let IpayAccountValidator = ipay_update.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                'firstname': {
                    required: true
                },
                'lastname': {
                    required: true
                },
                'dob': {
                    required: true,
                },
                'email': {
                    required: true,
                    email: true
                },
                'postcode': {
                    required: true
                },
                'address': {
                    required: true
                },
                'city': {
                    required: true
                },

            },
            messages: {
                'firstname': {
                    required: "{{ _t('member.please_enter_first_name') }}"
                },
                'lastname': {
                    required: "{{ _t('member.please_enter_last_name') }}"
                },
                'dob': {
                    required: "{{ _t('member.please_enter_dob') }}"
                },
                'email': {
                    required: "{{ _t('member.please_enter_email') }}",
                    email: "{{ _t('member.please_enter_valid_email') }}",
                },
                'postcode': {
                    required: "{{ _t('member.please_enter_postcode') }}",
                },
                'address': {
                    required: "{{ _t('member.please_enter_address') }}",
                },
                'city': {
                    required: "{{ _t('member.please_enter_city') }}",
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

        $('.IPayUpdate').click(function () {
            $('.ajaxValidationError').removeClass('ajaxValidationError');

            let successCallBack = function (response) {
                Ladda.stopAll();
                if (!response.status) {
                    toastr.error(response.message);
                } else {
                    toastr.success("{{ _t('member.IPay_account_updated_successfully') }}");
                }
                $('[data-target="#iPayAccountUpdateModal"]').text('{{ _t('member.updated') }}');
                window.onbeforeunload = null;
            };

            if (!ipay_update.valid()) {
                Ladda.stopAll();
                return false;
            }

            let failCallBack = function (response) {
                Ladda.stopAll();
                let errors = response.responseJSON;

                for (let key in errors) {
                    let elemKey = key;
                    $('#ipay_update [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                    let errorOption = {};
                    errorOption[elemKey] = errors[key];
                    IpayAccountValidator.showErrors(errorOption);
                }
            };

            let options = {
                form: ipay_update,
                actionUrl: "{{ route("management.members.ipayoutAccountUpdate") }}",
                successCallBack: successCallBack,
                failCallBack: failCallBack
            };
            sendForm(options);
        });
    });

    //I-Payout username Update
    let ipay_username_update = $('#ipay_username_update{{ $user->id }}');

    $.validator.addMethod('ajaxValidate', function (value, element, param) {
        let isValid = !$(element).parent().hasClass('ajaxValidationError');
        return isValid; // return bool here if valid or not.
    }, function (param, element) {
        return $(element).siblings('.help-block-error').text();
    });

    let IpayUsernameValidator = ipay_username_update.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "", // validate all fields including form hidden input
        rules: {
            'old_username': {
                required: true
            },
            'new_username': {
                required: true
            }
        },
        messages: {
            'old_username': {
                required: "{{ _t('member.please_enter_first_name') }}"
            },
            'new_username': {
                required: "{{ _t('member.please_enter_last_name') }}"
            }
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

    $('.IPayUsernameUpdate').click(function () {
        $('.ajaxValidationError').removeClass('ajaxValidationError');

        let successCallBack = function (response) {
            Ladda.stopAll();
            if (!response.status) {
                toastr.error(response.message);
            } else {
                toastr.success("{{ _t('member.IPay_account_updated_successfully') }}");
            }
            $('[data-target="#iPayUsernameUpdateModal"]').text('{{ _t('member.updated') }}');
            window.onbeforeunload = null;
        };

        if (!ipay_username_update.valid()) {
            Ladda.stopAll();
            return false;
        }

        let failCallBack = function (response) {
            Ladda.stopAll();
            let errors = response.responseJSON;

            for (let key in errors) {
                let elemKey = key;
                $('#ipay_username_update [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                let errorOption = {};
                errorOption[elemKey] = errors[key];
                IpayAccountValidator.showErrors(errorOption);
            }
        };

        let options = {
            form: ipay_username_update,
            actionUrl: "{{ route("management.members.ipayoutUsernameUpdate") }}",
            successCallBack: successCallBack,
            failCallBack: failCallBack
        };
        sendForm(options);
    });

    let downgrade_package = $('#downgrade_package{{ $user->id }}');

    $('.downgradePackage').click(function () {
        $('.ajaxValidationError').removeClass('ajaxValidationError');

        let successCallBack = function (response) {
            Ladda.stopAll();
            toastr.success("{{ _t('member.downgrade_package_successfully') }}");
            $('[data-target="#downgradePackageModal"]').text('{{ _t('member.updated') }}');
            window.onbeforeunload = null;
            location.reload();
        };

        let failCallBack = function (response) {
            Ladda.stopAll();
            let errors = response.responseJSON;

            for (let key in errors) {
                let elemKey = key;
                $('#downgrade_package [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                let errorOption = {};
                errorOption[elemKey] = errors[key];
                IpayAccountValidator.showErrors(errorOption);
            }
        };

        // let options = {
        //     form: downgrade_package,
        //     actionUrl: "{{ route("management.members.downgradePackage") }}",
        //     successCallBack: successCallBack,
        //     failCallBack: failCallBack
        // };
        // sendForm(options);
        let user_username = $('#downgradeUserPackage_{{ $user->id }}').val();
        window.location.href = "{{config('app.url')}}" + "/admin/package/ibToAffiliate/" + user_username;
    });

    let re_instate_package = $('#re_instate_package{{ $user->id }}');

    $('.reInstatePackage').click(function () {
        $('.ajaxValidationError').removeClass('ajaxValidationError');

        let successCallBack = function (response) {
            Ladda.stopAll();
            toastr.success("{{ _t('member.re_instate_successfully') }}");
            $('[data-target="#reInstatePackageModal"]').text('{{ _t('member.updated') }}');
            window.onbeforeunload = null;
            location.reload();
        };

        let failCallBack = function (response) {
            Ladda.stopAll();
            let errors = response.responseJSON;

            for (let key in errors) {
                let elemKey = key;
                $('#re_instate_package [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                let errorOption = {};
                errorOption[elemKey] = errors[key];
                IpayAccountValidator.showErrors(errorOption);
            }
        };

        let options = {
            form: re_instate_package,
            actionUrl: "{{ route("management.members.reInstatePackage") }}",
            successCallBack: successCallBack,
            failCallBack: failCallBack
        };
        sendForm(options);
    });

    let unSubscribeEmailForm = $('#unSubscribeEmail{{ $user->id }}');
    let UnSubscribeEmailValidator = unSubscribeEmailForm.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "", // validate all fields including form hidden input
        rules: {
            'email': {
                required: true,
                email: true
            },
        },
        messages: {
            'email': {
                required: "{{ _t('member.please_enter_email') }}",
                email: "{{ _t('member.please_enter_valid_email') }}",
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

    $('.unSubscribeEmailSave').click(function () {
        $('.ajaxValidationError').removeClass('ajaxValidationError');

        let successCallBack = function (response) {
            Ladda.stopAll();
            if (!response.status) {
                if (response.already) {
                    toastr.info(response.message);
                } else {
                    toastr.error(response.message);
                }
            } else {
                toastr.success("{{ _t('member.status_updated_successfully') }}");
            }
            $('[data-target="#unSubscribeEmailModal"]').text('{{ _t('member.saved') }}');
            window.onbeforeunload = null;
        };

        if (!unSubscribeEmailForm.valid()) {
            Ladda.stopAll();
            return false;
        }

        let failCallBack = function (response) {
            Ladda.stopAll();
            let errors = response.responseJSON;

            for (let key in errors) {
                let elemKey = key;
                $('#unSubscribeEmail [name="' + elemKey + '"]').parent().addClass('ajaxValidationError');
                let errorOption = {};
                errorOption[elemKey] = errors[key];
                UnSubscribeEmailValidator.showErrors(errorOption);
            }
        };

        let options = {
            form: unSubscribeEmailForm,
            actionUrl: "{{ route("management.members.unSubscribeEmail") }}",
            successCallBack: successCallBack,
            failCallBack: failCallBack
        };
        sendForm(options);
    });


    //Function to retrieve states of country
    function getStates(country) {
        let options = {action: 'getStates', data: {country: country}};

        return $.post("{{ route('local.api') }}", options, function (response) {
            var states = '';
            response.forEach(function (value, index) {
                states += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $('#state_list').html(states);
        });
    }

    //Function to retrieve cities of states
    function getCities(state) {
        let options = {action: 'getCities', data: {state: state}};
        let post = $.post("{{ route('local.api') }}", options);

        post.done(function (response) {
            var cities = '';
            response.forEach(function (value, index) {
                cities += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $('#city_list').html(cities);
        });

        return post;
    }

    $('body').on('keyup click', '.ajaxValidationError input', function () {
        $(this).parent().removeClass('ajaxValidationError');
    });

    preventReloadPageIfChangesNotSave($("form.panelForm"));

</script>
