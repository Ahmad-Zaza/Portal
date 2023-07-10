@if (!auth()->user()->organization->marketplace_subscription_guid)
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
                <p class="fn-13">You can find your Licnese Information here and add any new Verification Code to active
                    a new License.</p>
            </div>
        </div>
    </div>
    <!-- License -->

    <div class="row mbt">
        <div class="ml-40">
            <h5 class="txt-blue">License Info: </h5>
        </div>
        <div class="ml-20px mt-8">
            <div class="float-left text-white">License Count: <span class="org-color">
                    {{ $arr['license_allowed'] }}</span></div>
        </div>
        <div class="ml-30 mt-8">
            <div class="float-lef text-white"> License Used: <span class="org-color">
                    {{ $arr['used_license'] }}</span>
            </div>
        </div>
    </div>
    <!-- All verification code table -->
    <div class="row mt-35 ml-10">
        <div class="verCodeTable w-893">
            <table id="verCodesTable" class="stripe table table-striped table-dark w-100">
                <thead class="table-th">
                    <th class="after-none"></th>
                    <th>Verification Code</th>
                    <th>License Count</th>
                    <th>Expiry Date</th>
                </thead>
                <tbody class="repo-table-padding" id="table-content">

                </tbody>
            </table>

            <!-- Create new verification code button  -->
            <div class="col-lg-12">
                <div class="form-group">
                    <button type="button" class="addModal w-200 btn_primary_state custom-right-float-account">Add
                        Verification Code</button>
                </div>
            </div>
        </div>
    </div>
@else
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
                <p class="fn-13">Your licenses is managed by Microsoft Subscription, You can go to Azure Portal and
                    check your SaaS Subscriptions.</p>
            </div>
        </div>
    </div>
@endif


<!-- Filter Modal -->
<div id="searchModal" class="modal modal-center" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="divBorderRight"></div>
        <div class="divBorderBottom"></div>
        <div class="divBorderleft"></div>
        <div class="divBorderUp"></div>

        <!-- Modal Content -->
        <div class="modal-content">
            <div id="search_modal_id" class="modalContent">
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="row mb-15">
                    <div class="input-form-70">
                        <h4 class="per-req">Search</h4>
                    </div>
                </div>

                <div class="row mb-20" id="Duration-Section">
                    <div class="input-form-70 mb-1 text-white">Expiry Date:</div>
                    <div class="input-form-70 inline-flex">
                        <div class="mr-25 relative">
                            <input type="text" class="form_input form-control custom-form-control font-size"
                                id="ExpirationFrom" placeholder="From" />
                        </div>

                        <div class="relative">
                            <input type="text" class="form_input form-control font-size custom-form-control"
                                id="ExpirationTo" placeholder="To" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-form-70 inline-flex">
                        <button type="button" onclick="applySearch()"
                            class="btn_primary_state halfWidth mr-25">Apply</button>
                        <button type="button" onclick="resetSearch()"
                            class="btn_cancel_primary_state halfWidth">Reset</button>
                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<!-- Create Verification Code Modal -->
<div id="createNewVerificationCodeModal" class="modal modal-center" role="dialog">
    <div class="modal-dialog m-20v">
        <div class="divBorderRight"></div>
        <div class="divBorderBottom"></div>
        <div class="divBorderleft"></div>
        <div class="divBorderUp"></div>

        <!-- Modal Content -->
        <div class="modal-content">
            <form id="codeForm" onsubmit="makeCreateCode(event)">
                <div id="modalBody_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6 mb-15">
                            <h4 class="per-req">New Verification Code</h4>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="modal-body col-lg-8">
                            <input type="text" class="form_input form-control" id="modalVerCode"
                                name="modalVerCode" placeHolder="Enter Verification Code" required>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>

                    <div class="row pb-10">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <button type="submit" class="btn_primary_state allWidth">Add</button>
                        </div>
                        <div class="col-lg-2"></div>

                    </div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <button type="button" class="btn_cancel_primary_state" data-dismiss="modal"
                                onClick="clearCreateModalData()">Close</button>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    //-------------------------------------------//
    $(document).ready(function() {
        //-------------------------------------------//
        $('#verCodesTable').DataTable({
            'ajax': {
                "type": "GET",
                "url": "{{ url('getUserVerificationCodes') }}",
                "dataSrc": '',
                "data": {},
                "dataType": "json",
            },
            "order": [
                [3, 'desc']
            ],
            'columns': [{
                    "data": null,
                    "class": "after-none",
                    "render": function(data) {
                        return '<img class= "tableIcone w-13 mr-0" src="/svg/details\.svg " title="Code">';
                    }
                },
                {
                    "data": "code",
                    "class": "text-left",
                },
                {
                    "data": "license_count",
                },
                {
                    "data": null,
                    "render": function(data) {
                        let date = data.expiration_date;
                        if (!date)
                            return "";
                        var d = new Date(date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();

                        if (month.length < 2)
                            month = '0' + month;
                        if (day.length < 2)
                            day = '0' + day;
                        return day + "/" + month + "/" + year;
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'csvHtml5',
                    text: '<img src="/svg/excel.svg" class="custom-svg">',
                    titleAttr: 'Export to csv',
                    exportOptions: {
                        columns: 'th:not(:first-child,:last-child)',
                        format: {
                            body: function(data, column, row) {
                                //if it is html, return the text of the html instead of html
                                if (data.includes("<i")) {
                                    return 1;
                                } else if (/<\/?[^>]*>/.test(data)) {
                                    return $(data).text();
                                } else {
                                    return data;
                                }
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<img src="/svg/pdf.svg" class="custom-svg">',
                    customize: function(doc) {
                        customizePdf(doc)
                    },
                    titleAttr: 'Export to pdf',
                    exportOptions: {
                        columns: 'th:not(:first-child,:last-child)',
                        format: {
                            body: function(data, column, row) {
                                data = data + "";
                                //if it is html, return the text of the html instead of html
                                if (data.includes("<i")) {
                                    return 1;
                                } else if (/<\/?[^>]*>/.test(data)) {
                                    return $(data).text();
                                } else {
                                    return data;
                                }
                            }
                        }
                    }
                },
                {
                    text: '<img src="/svg/filter.svg" class="custom-svg">',
                    titleAttr: 'Advanced Search',
                    action: function(e, dt, node, config) {
                        $('#searchModal').modal('show');
                    }
                }
            ],
            "fnDrawCallback": function() {
                var icon =
                    '<div class="search-container"><img class="search-icon mt-12" src="/svg/search.svg"></div>';
                if ($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                $('.dataTables_filter input').addClass('form_input form-control');
            },
            "scrollY": "500px",
            "scrollCollapse": true,
            "bInfo": false,
            "paging": false,
            "autoWidth": false,
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },
            'columnDefs': [{
                'targets': [0], // column index (start from 0)
                'orderable': false, // set orderable false for selected columns
            }]
        });
        $('#verCodesTable').DataTable().buttons().container()
            .prependTo('#verCodesTable_filter');
        //-------------------------------------------//
        $("#ExpirationFrom").datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function(dateTime) {
                $("#verCodesTable").DataTable().draw();
            }
        });
        $("#ExpirationTo").datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function(dateTime) {
                $("#verCodesTable").DataTable().draw();
            }
        });
        //-------------------------------------------//
        $('.addModal').click(function() {
            $('#createNewVerificationCodeModal').modal('show');
        });
        //-------------------------------------------//
    });

    //-------------------------------------------//
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            res = true;
            ExpirationFrom = $("#ExpirationFrom").datepicker("getDate");
            ExpirationTo = $("#ExpirationTo").datepicker("getDate");
            let conditionsArray = [];

            if ((ExpirationFrom || ExpirationTo) && !data[3]) {
                res = false;
            } else {

                if (ExpirationFrom) {

                    if (new Date(ExpirationFrom) > new Date(data[3])) {
                        res = false;
                    }
                }
                if (ExpirationTo) {
                    if (new Date(ExpirationTo) < new Date(data[3])) {
                        res = false;
                    }
                }
            }
            return res;
        }
    );
    //-------------------------------------------//
    function resetSearch() {
        $("#ExpirationFrom").val("");
        $("#ExpirationTo").val("");
        $('#verCodesTable').DataTable().draw();
    }
    //-------------------------------------------//
    function applySearch() {
        $('#verCodesTable').DataTable().draw();
    }
    //-------------------------------------------//
    function makeCreateCode(event) {
        event.preventDefault();
        $(".spinner_parent").css("display", "block");
        $('body').addClass('removeScroll');
        $.ajax({
            type: "POST",
            url: "{{ url('saveUserVerificationCode') }}",
            data: {
                code: $('#codeForm').find('#modalVerCode').val(),
                _token: "{{ csrf_token() }}",
            },
            dataType: "text",
            success: function(data) {
                $(".spinner_parent").css("display", "none");
                $('body').removeClass('removeScroll');
                clearCreateModalData();
                $('#createNewVerificationCodeModal').modal('hide');
                $('#verCodesTable').DataTable().ajax.reload();
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
                showErrorMessage(errMessage);
            }
        });
    }

    //-------------------------------------------//
    function showErrorMessage(message) {
        $(".danger-oper .danger-msg").html(message);
        $(".danger-oper").css("display", "block");
        setTimeout(function() {
            $(".danger-oper").css("display", "none");
        }, 8000);
    }
    //-------------------------------------------//
    function clearCreateModalData() {
        document.getElementById('modalVerCode').value = '';
    }

    //-------------------------------------------//
    $(window).load(function() {
        clearCreateModalData();
    });
</script>
