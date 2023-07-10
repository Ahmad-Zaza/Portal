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
            <p class="fn-13">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum, quia placeat amet
                quae accusantium
                reiciendis facilis.</p>
        </div>
    </div>
</div>

<div class="row ml-25 mr-4 mb-20">
    <div class="col-lg-11">
        <div class="row pl-0 ml-35">
            <h5 class="txt-blue">E-Mail Settings</h5>
        </div>
        <div class="col-lg-8 ml-35">
            <div class="form-group">
                <label class="control-label col-sm-2 mt-8 text-white" for="sendToEmail">Send To:</label>
                <div class="col-sm-9">
                    <textarea class="form_input form-control w-600" id="sendToEmail" name="sendToEmail" rows="1"
                        maxlength="500">{{ Auth::user()->send_emails ?? Auth::user()->email }}</textarea>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
    </div>
</div>


<div class="col-sm-12">
    <div class="row">
        <div class="allWidth ml-25">
            <div class="row ml-0">
                <h5 class="txt-blue">Notifications Settings</h5>
            </div>
            <table class="stripe table table-striped table-dark w-91" CELLSPACING=0>
                <thead class="table-th">
                    <th>
                        <span class="data-toggle-fmc ml-5">Notification</span>
                    </th>
                    <th>
                        <label class="checkbox-container checkbox-search">&nbsp;
                            <input type="checkbox" class="form-check-input emailCheck" />
                            <span class="check-mark"></span>
                        </label>
                        <span>E-Mail Notifications</span>
                    </th>
                    <th>
                        <label class="checkbox-container checkbox-search">&nbsp;
                            <input type="checkbox" class="form-check-input teamsCheck" />
                            <span class="check-mark"></span>
                        </label>
                        <span>Teams Notifications</span>
                    </th>
                </thead>

                <tbody class="tbody-back-color">
                    @if (!$data)
                        <tr>
                            <td colspan="3">
                                No data available
                            </td>
                        </tr>
                    @endif
                    @foreach ($data as $item1)
                        <tr>
                            <td>
                                <div class="one-tab1">
                                    <input type="hidden" name="notId" class="notId"
                                        value="{{ $item1->id }}">
                                    <span class="data-toggle-fm">{{ $item1->text }}</span>
                                </div>
                            </td>
                            <td>
                                <label class="checkbox-container checkbox-search" style="left:28px;">&nbsp;
                                    @php
                                        $checked = $item1->userNotifications > 0 && $item1->userNotifications[0]->email ? 'checked' : '';
                                    @endphp
                                    <input type="checkbox" {{ $checked }} value="{{ $item1->id }}"
                                        class="form-check-input email-input-check" />
                                    <span class="check-mark"></span>
                                </label>
                            </td>
                            <td>
                                <label class="checkbox-container checkbox-search" style="left:28px;">&nbsp;
                                    @php $checked = ($item1->userNotifications > 0 && $item1->userNotifications[0]->teams) ? "checked": "" @endphp
                                    <input type="checkbox" {{ $checked }} value="{{ $item1->id }}"
                                        class="form-check-input teams-input-check" />
                                    <span class="check-mark"></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="col-lg-12">
                <div class="form-group">
                    <button type="button" class="saveBtn btn_primary_state custom-right-float-note">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(window).load(function() {
        $('.teamsCheck').change(function() {
            let thead = $(this).closest('thead');
            $('tbody').find('.teams-input-check').prop('checked', $(this).prop('checked'));
        });

        $('.emailCheck').change(function() {
            let thead = $(this).closest('thead');
            $('tbody').find('.email-input-check').prop('checked', $(this).prop('checked'));
        });
    });

    $('.saveBtn').click(function() {
        let nots = $('.notId');
        let dataArr = [];
        nots.each(function() {
            let row = $(this).closest('tr');
            dataArr.push({
                id: $(this).val(),
                email: row.find('.email-input-check')[0].checked,
                teams: row.find('.teams-input-check')[0].checked,
            });
        });

        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');
        $.ajax({
            type: "POST",
            url: "{{ url('saveUserNotifications') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "sendToEmail": $('#sendToEmail').val(),
                "dataArr": JSON.stringify(dataArr),
            },
            success: function(data) {
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
            },
            statusCode: {
                401: function() {
                    window.location.href = "{{ url('/') }}";
                },
                402: function() {
                    let errMessage = "   ERROR   ";
                    $(".danger-oper .danger-msg").html(
                        "{{ __('variables.errors.restore_session_expired') }}");
                    $(".danger-oper").css("display", "block");
                    setTimeout(function() {
                        $(".danger-oper").css("display", "none");
                        window.location.reload();
                    }, 3000);
                }
            },
            error: function(error) {
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                let errMessage = "   ERROR   ";
                if (error.responseText) {
                    let err = JSON.parse(error.responseText);
                    if (err.message)
                        errMessage = "ERROR  : " + err.message;
                }
                $(".danger-oper .danger-msg").html(errMessage);
                $(".danger-oper").css("display", "block");
                setTimeout(function() {
                    $(".danger-oper").css("display", "none");

                }, 8000);
            }
        });
    });
</script>
