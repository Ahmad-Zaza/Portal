<style>
    input[disabled] {
        color: #737779;
        background-color: #424a50 !important;
    }
</style>
<div class="row azure-custom-info ml-25 mr-4 mb-20">
    <div class="col-lg-11">
        <div class="row pl-0">
            <h5 class="txt-blue">Description</h5>
        </div>
        <div class="row newInfoRow mb-0">
            <div class="rowBorderRight"></div>
            <div class="rowBorderBottom"></div>
            <div class="rowBorderleft"></div>
            <div class="rowBorderUp"></div>
            <p class="fn-13">You can find your Main Profile Information here. This includes your provisioned Azure Subscription Details along with your User Information.</p>
        </div>
    </div>
</div>

<div class="row azure-custom-info mb-20">
    <div class="col-lg-6 ml-25 mb-20">
        <div class="col-lg-11 pl-0">
            <h5 class="txt-blue">Azure Tenant Information</h5>
        </div>

        <div class="col-lg-12 custom-info">
            <div class="col-lg-4 nopadding custom-title nopadding org-color">
                Tenant ID:
            </div>
            <div class="custom-info-details col-lg-8 nopadding">
                {{ Auth::user()->organization->azure_tenant_guid }}
            </div>
        </div>
        <br>
        <div class="col-lg-12 custom-info">
            <div class="col-lg-4 nopadding custom-title org-color">
                Tenant Name:
            </div>
            <div class="custom-info-details col-lg-8 nopadding">
                {{ Auth::user()->organization->azure_tenant_name }}
            </div>
        </div>
        <br>
        <div class="col-lg-12 custom-info mb-0">
            <div class="col-lg-4 nopadding custom-title org-color">
                Subscription ID:
            </div>
            <div class="custom-info-details col-lg-8 nopadding">
                {{ Auth::user()->organization->azure_subscription_guid }}
            </div>
        </div>

    </div>
</div>

<form method="POST" action="{{ route('bactopus-settings.update', ['id' => Auth::user()->id]) }}">
    @csrf
    @method('PUT')
    <div class="ml-25 pl-0">
        <h5 class="txt-blue">My Information</h5>
    </div>
    <div class="row newInfoRow ml-25 w-89">
        <div class="rowBorderRight"></div>
        <div class="rowBorderBottom"></div>
        <div class="rowBorderleft"></div>
        <div class="rowBorderUp"></div>
        <div class="col-lg-5">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-4 off-white" for="jobName">First Name</label>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control custom-form-control font-size" id="FirstName"
                            placeholder="First Name" name="first_name" required autocomplete="off"
                            value="{{ Auth::user()->first_name }}" />
                        @error('FirstName')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4 off-white" for="jobName">Phone</label>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control custom-form-control numm font-size" id="Phone"
                            placeholder="Phone" name="phone" required autocomplete="off"
                            value="{{ Auth::user()->phone }}" />
                        @error('Phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4 off-white" for="jobName">Company</label>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control custom-form-control numm font-size"
                            id="company_name" placeholder="company_name" name="company_name" required autocomplete="off"
                            value="{{ Auth::user()->organization->company_name }}" />
                        @error('company_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-1">
        </div>
        <div class="col-lg-5">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-4 off-white" for="jobName">Last Name</label>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control custom-form-control font-size" id="LastName"
                            placeholder="LastName" name="last_name" required autocomplete="off"
                            value="{{ Auth::user()->last_name ?: '' }}" />
                        @error('LastName')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4 off-white" for="jobName">Email</label>
                    <div class="col-sm-8">
                        <input disabled type="email" class="form-control custom-form-control font-size" id="email"
                            placeholder="Email" value="{{ Auth::user()->email }}" name="email" required
                            autocomplete="off">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4 off-white">Timezone</label>
                    <div class="col-sm-8">
                        <div class="allWidth relative ror">
                            <select style="width: 100%!important" name="timezone" id="timezone" required
                                class="form-control custom-form-control font-size form_input required js-data-example-ajax">
                                <option value="">Select Timezone</option>
                                @foreach ($timezones as $item)
                                    @if (Auth::user()->timezone == $item->name)
                                        <option selected value="{{ $item->name }}">{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="">
        <div class="form-group">
            <button type="submit" class="btn_primary_state custom-right-float">Save</button>
        </div>
    </div>

</form>
