@extends('layouts.app')

<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/veeamServer/repositories.css') }}" />
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
<link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/restore.css') }}">
<link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/restore-customize.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/css/eDiscovery.css') }}" />

<script src="/js/html-duration-picker.min.js"></script>
@section('topnav')
    @php
        $status = $data['job']['status'];
        $isRestore = strpos($data['job']['sub_type'], 'Restore');
        $process = $isRestore === 0 ? 'Restore' : 'Export';
    @endphp
    <div class="col-sm-10 navbarLayout">
        <!-- Upper navbar -->
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm upperNavBar"> -->
        <ul class="ulNavbar">
            @php $parent = strtolower($data['kind']) @endphp
            <li class="liNavbar"><a class="parent-link" data-parent="{{ $parent }}"
                    href="{{ url('e-discovery', $data['kind']) }}"> E-Discvovery <img class="nav-arrow"
                        src="/svg/arrow-right.svg"> {{ getDataType($data['kind']) }}</a></li>
            <li class="liNavbar"><a class="active"
                    href="{{ url('e-discovery', [$data['kind'], 'result', $data['job']['restore_session_guid']]) }}">{{ $data['job']['name'] }}
                    - Result</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>
@endsection
@section('content')
    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div class="row main-button-cont mb-40">
            <div class="btnMain main-button flex">
                <div class="btnUpMask"></div>
                <div class="row m-0 pl-4 pr-4 allWidth">
                    <div class="col-lg-4 mailBoxFolderItemsButton">
                        <div class="selected-action actions-btn allWidth relative">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Selected Items Actions
                                <span class="selectedItemCount"></span>
                                <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu allWidth">
                                <li>
                                    <a href="javascript:restoreItemOriginalModal(event)" class="tooltipSpan"
                                        title="Restore Selected to Original Location">
                                        Restore Selected to Original Location
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:restoreItemAnotherModal(event)" class="tooltipSpan"
                                        title="Restore Selected to Another Location">
                                        Restore Selected to Another Location
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="javascript:downloadMultiItems(event)" class="tooltipSpan"
                                        title="Export Selected To .zip">
                                        Export Selected To .zip
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="javascript:exportItemsModal(0)" class="tooltipSpan" title="Export to PST">
                                        Export to PST
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="btnDownMask"></div>
            </div>
        </div>
        <!-- All repositories table -->
        <div class="row">
            <div class="col-lg-4 z-index-500 select-custom main-filter-cont">
                <select style="width: 100%!important" name="groupSelect" id="groupSelect"
                    class="form-control form_input required js-data-example-ajax select2">
                    <option value="">Select Mailbox</option>
                    @foreach ($data['search_data'] as $item)
                        <option value="{{ $item->mailboxName . ($item->folderName ? '_' . $item->folderName : '') }}">
                            {{ $item->mailboxName . ($item->folderName ? ': ' . $item->folderName : '') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="jobsTable" style="margin-left:0px; margin-top: -38px;">
            <table id="detailsTable"
                class="stripe table table-striped table-dark display nowrap allWidth table-text-truncate"
                style="width:100%">
                <thead class="table-th">
                    <tr>
                        <th>
                            <label class="checkbox-top-left checkbox-container checkbox-search left-17">
                                <input type="checkbox" class="form-check-input allItemsCheck">
                                <span class="tree-checkBox check-mark-white check-mark"></span>
                            </label>
                        </th>
                        <th class="text-left">Mailbox</th>
                        <th>Folder</th>
                        {{-- <th>Type</th> --}}
                        <th>From</th>
                        <th>To</th>
                        <th>Subject</th>
                        <th>Cc</th>
                        <th>Bcc</th>
                        <th>Sent</th>
                        <th>Received</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <div class="float-left">
                                <span class="showingCount">0</span> items showing
                            </div>
                            <div class="">
                                <div class="float-right"><span class="boxesCount">0</span> total items</div>
                                <div class="sep">|</div>
                                <div class="float-right"><span class="selectedCount">0</span> selected items</div>
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row mr-0" style="margin-bottom: 25px;margin-top:25px;">
            <div class="col-lg-4 flex">
                <button class="pl-0 btn-sm custom-btn-sm hand previousPage" disabled="disabled" onclick="previousPage()"
                    title="Previous">
                    <img class="iconColor mt-0 mr-0 hide-pre hide" src="{{ url('/svg/Pre.svg') }}">
                    <img class="iconColor mt-0 mr-0 show-pre" src="{{ url('/svg/Pre-02.svg') }}">
                </button>
                <div class="flex mr-0 align-center">
                    Page <span class="ml-1 mr-1 currentPage">0</span> of <span class="ml-1 mr-0 totalPages">0</span>
                </div>
                <button class="btn-sm custom-btn-sm hand mr-0 nextPage" onclick="nextPage()" title="Next">
                    <img class="iconColor mt-0 show-next" src="{{ url('/svg/dash-next.svg') }}">
                    <img class="iconColor mt-0 hide-next hide" src="{{ url('/svg/Next-01.svg') }}">
                </button>
            </div>
            <div class="col-lg-4"></div>
            <div class="col-lg-4 nopadding flex flex-row-reverse">
                <a href="{{ url('e-discovery', $data['kind']) }}"
                    class="cancel-button btn_primary_state mr-0 min-width-150">
                    Cancel</a>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportItemsModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-15 mb-4">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Items</h4>
                        </div>
                    </div>
                    <form id="exportItemsForm" class="mb-0" onsubmit="exportMailBoxFolderItemsToPst(event)">
                        <input type="hidden" class="showVersions" name="showVersions"
                            value="{{ $data['job']->is_restore_point_show_versions }}" />
                        <input type="hidden" class="showDeleted" name="showDeleted"
                            value="{{ $data['job']->is_restore_point_show_deleted }}" />
                        <input type="hidden" class="jobTime" name="jobTime"
                            value="{{ $data['job']->restore_point_time }}" />
                        <input type="hidden" class="jobId" name="jobId"
                            value="{{ $data['job']->backup_job_id }}" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Items</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportItemsResultsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label
                                                            class="checkbox-top-left checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark-white check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Mailbox</th>
                                                    <th>Item</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> items selected
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="exportItemsZipModal" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg ">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-15  mb-4">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Export Selected Items</h4>
                        </div>
                    </div>
                    <form id="exportItemsZipForm" class="mb-0" onsubmit="exportMailBoxFolderItemsToZip(event)">
                        <input type="hidden" class="showVersions" name="showVersions"
                            value="{{ $data['job']->is_restore_point_show_versions }}" />
                        <input type="hidden" class="showDeleted" name="showDeleted"
                            value="{{ $data['job']->is_restore_point_show_deleted }}" />
                        <input type="hidden" class="jobTime" name="jobTime"
                            value="{{ $data['job']->restore_point_time }}" />
                        <input type="hidden" class="jobId" name="jobId"
                            value="{{ $data['job']->backup_job_id }}" />
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Restore Job Name</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder pb-3 pt-3">
                                    <div class="mb-0 allWidth flex relative">
                                        <input type="text"
                                            class="form-control form_input custom-form-control font-size"
                                            placeholder="Job Name" name="restoreJobName" required autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 mb-1">
                                <h5 class="txt-blue mt-0">Selected Items</h5>
                            </div>
                            <div class="input-form-70">
                                <div class="col-lg-12 customBorder">
                                    <div class="allWidth">
                                        <table id="exportItemsZipResultsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <th>
                                                        <label
                                                            class="checkbox-top-left checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark-white check-mark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Mailbox</th>
                                                    <th>Item</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> items selected
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-form-70 inline-flex">
                                <button type="submit" class="btn_primary_state allWidth mr-25">Export</button>
                                <button type="button" class="btn_cancel_primary_state  allWidth"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="restoreItem" class="modal modal-center" role="dialog">
        <div class="modal-dialog modal-lg modal-width modal-item-width" style="width: 600px">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row ml-100 modal-item-margin mb-4">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p modal-title">Restore Selected Items</h4>
                        </div>
                    </div>
                    <form id="restoreItemForm" class="mb-0" onsubmit="restoreItem(event)">
                        <div class="custom-left-col modal-item-left">
                            <input type="hidden" class="restoreType" name="restoreType" />
                            <input type="hidden" class="showVersions" name="showVersions"
                                value="{{ $data['job']->is_restore_point_show_versions }}" />
                            <input type="hidden" class="showDeleted" name="showDeleted"
                                value="{{ $data['job']->is_restore_point_show_deleted }}" />
                            <input type="hidden" class="jobTime" name="jobTime"
                                value="{{ $data['job']->restore_point_time }}" />
                            <input type="hidden" class="jobId" name="jobId"
                                value="{{ $data['job']->backup_job_id }}" />
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Job Name</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div class="mb-0 allWidth flex relative">
                                            <input type="text"
                                                class="form-control form_input custom-form-control font-size"
                                                placeholder="Job Name" name="restoreJobName" required
                                                autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('partials.device-code-authentication')
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Mailbox Items</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder modal-item-h-275p h-218p">
                                        <table id="itemsResultsTable"
                                            class="stripe table table-striped table-dark display nowrap allWidth">
                                            <thead class="table-th">
                                                <tr>
                                                    <td>
                                                        <label style="top: 45%;left: 10px;"
                                                            class="checkbox-container checkbox-search">
                                                            <input type="checkbox" checked class="form-check-input">
                                                            <span class="tree-checkBox check-mark"></span>
                                                        </label>
                                                    </td>
                                                    <td>Mailbox</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">
                                                        <span class="boxesCount"></span> items selected <span
                                                            class="unresolvedCount"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-right-col modal-item-right">
                            <div class="row hide itemsAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="mb-10">Specify Mailbox to Restore to:</div>
                                            <div class="mb-10 allWidth">
                                                <input type="email"
                                                    class="form-control form_input custom-form-control font-size required"
                                                    name="mailbox" placeholder="MailBox" name="mailbox" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide itemsAnotherOptions_cont targetMailbox">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Target MailBox Folder</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">
                                        <div>
                                            <div class="authCont custom-authCont pt-3 pb-3">
                                                <label class="mr-2 m-0 nowrap">Folder to Restore To:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="original" checked>Original
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="folderType" class="folderType"
                                                                value="custom" checked>Another Folder
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-10 pl-2 allWidth">
                                                <input type="text" class="form-control form_input required font-size"
                                                    id="folder" value="" placeholder="Folder" name="folder"
                                                    required autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide itemsAnotherOptions_cont">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Restore Options</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder pb-3 pt-3">

                                        <div class="row pl-3 mb-10">Specify Restore Options:</div>
                                        <div class="row">
                                            <div class="pr-0 pl-3">
                                                <label>Restore the following items:</label>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="changedItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Changed Items</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="relative allWidth mb-2">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="deletedItems" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Deleted Items</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="mt-3 ml-25">Flag Restored Items as Unread:</label>
                                                <div class="allWidth relative ml-30">
                                                    <label style="padding-top: 5px;left: 0px;"
                                                        class="checkbox-container">&nbsp;
                                                        <input name="markRestoredAsunread" type="checkbox"
                                                            class="form-check-input" />
                                                        <span
                                                            style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                                            class="check-mark"></span>
                                                    </label>
                                                    <span style="margin-left: 25px;">Mark Restored As Unread</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-form-70 inline-flex">
                                    <button type="submit" class="btn_primary_state allWidth mr-25">Restore</button>
                                    <button type="button" class="btn_cancel_primary_state  allWidth"
                                        data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="firstSearchModal" class="modal modal-center" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:20vh auto;">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>

            <!-- Modal content-->
            <div class="modal-content ">

                <div id="seaerch_modal_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req ml-2p">Search
                            </h4>
                        </div>
                    </div>
                    <div class="row" id="Status-Section">
                        <div class="input-form-70 mb-1">Status:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstSuccess" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Success</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstStop" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Stop</span>
                            </div>

                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstWarning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Warning</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstError" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Error</span>
                            </div>
                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="firstRunning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Running</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="applySearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="resetSearch()">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <div id="secondSearchModal" class="modal modal-center" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 500px; margin:20vh auto;">
            <div class="divBorderRight"></div>
            <div class="divBorderBottom"></div>
            <div class="divBorderleft"></div>
            <div class="divBorderUp"></div>

            <!-- Modal content-->
            <div class="modal-content ">
                <div id="seaerch_modal_id" class="modalContent">
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="input-form-70">
                            <h4 class="per-req">Search
                            </h4>
                        </div>
                    </div>

                    <div class="row" id="Duration-Section">
                        <div class="input-form-70 mb-1">Duration:</div>

                        <div class="input-form-70" style="display: inline-flex;">
                            <div class="mr-25" style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationFrom"
                                    placeholder="From" />
                            </div>

                            <div style="position: relative">
                                <input class="form_input form-control html-duration-picker" id="durationTo"
                                    placeholder="To" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70 mb-1">Status:</div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondSuccess" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Success</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondStop" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Stop</span>
                            </div>

                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondWarning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Warning</span>
                            </div>
                            <div class="halfWidth" style="position: relative;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondError" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Error</span>
                            </div>
                        </div>
                        <div class="input-form-70" style="display: inline-flex;">
                            <div style="position: relative;width:60%;">
                                <label style="padding-top: 5px;left: 0px;" class="checkbox-container">&nbsp;
                                    <input id="secondRunning" type="checkbox" class="form-check-input" />
                                    <span style="width: 15px !important; height: 15px !important;top:-5px!important;"
                                        class="check-mark"></span>
                                </label>
                                <span style="margin-left: 25px;">Running</span>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-form-70" style="display: inline-flex;">
                            <button type="button" onclick="secondApplySearch()"
                                class="btn_primary_state  halfWidth mr-25">Apply</button>
                            <button type="button" class="btn_cancel_primary_state  halfWidth"
                                onclick="secondResetSearch()">Reset</button>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">&nbsp;</div>

                </div>
            </div>

        </div>
    </div>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <script>
        let partitionsArr = [];
        let rowsArr = [];
        let nextPartition = null;
        let totalCount = pageItemsCount = 0;
        let filterPartition = null;
        let currentPartition = null;
        let nextRow = null;
        let currentRow = null;
        //-----------------------------------//
        let jobType = "{{ $data['job']->restore_point_type }}";
        let jobId = "{{ $data['job']->backup_job_id }}";
        let jobTime = "{{ $data['job']->restore_point_time }}";
        let showDeleted = "{{ $data['job']->is_restore_point_show_deleted }}";
        let showVersions = "{{ $data['job']->is_restore_point_show_version }}";
        //-----------------------------------//
        $(document).ready(function() {
            var parent = $('.parent-link').attr('data-parent');
            $('.submenu-discovery.submenu a[data-route="' + parent + '"]').addClass('active');
            var row = $('a.sub-menu-link.active').closest('.row');
            row.find('.left-nav-list').addClass('active').removeClass('collapsed');
            $('.submenu-discovery').addClass('in');

            //---------------------------------------//
            let detailsTable = $('#detailsTable').DataTable({
                'ajax': {
                    "type": "GET",
                    "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
                    "dataSrc": function(json) {
                        nextPartition = json['nextPartition'];
                        nextRow = json['nextRow'];
                        totalCount = json['totalCount'];
                        pageItemsCount = json['pageItemsCount'];
                        return json['data'];
                    },
                    "data": function(d) {
                        d.filter = getFilterPartition();
                        d.nextPartition = getNextPartition();
                        d.nextRow = getNextRow();
                    },
                    "statusCode": {
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
                    "dataType": "json",
                    "beforeSend": function() {
                        $('#detailsTable > tbody').html(
                            '<tr class="odd">' +
                            '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                            '</tr>');
                        currentPartition = nextPartition;
                        currentRow = nextRow;
                    }
                },
                'columns': [{
                        "class": "after-none",
                        "data": null,
                        render: function(data) {
                            return '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                '<input type="hidden" class="mailboxId" value="' + data.mailbox +
                                '">' +
                                '<input type="hidden" class="mailboxTitle" value="' + data
                                .mailboxTitle + '">' +
                                '<input type="hidden" class="folderId" value="' + data.folder +
                                '">' +
                                '<input type="hidden" class="folderTitle" value="' + data
                                .folderTitle + '">' +
                                '<input type="checkbox" class="mailBoxFolderItemCheck form-check-input" value="' +
                                data.id + '"/>' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span></label>';
                        }
                    },
                    {
                        "class": "text-left",
                        "data": null,
                        "render": function(data) {
                            return data.mailboxTitle;
                        }
                    },
                    {
                        "data": "folderTitle",
                    },
                    // {
                    //     "data": "itemClass",
                    //     "render": function(data){
                    //         return data.replace("IPM.","");
                    //     }
                    // },
                    {
                        "data": null,
                        "render": function(data) {
                            if (data.from)
                                return data.from;
                            if (data.organizer)
                                return data.organizer;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            if (data.to)
                                return data.to;
                            if (data.attendees)
                                return data.attendees;
                        }
                    },
                    {
                        "data": "subject",
                        "class": "subject wrap",
                    },
                    {
                        "data": "cc",
                    },
                    {
                        "data": "bcc",
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            if (data.sent)
                                return formatDate(data.sent);
                            if (data.startTime)
                                return formatDate(data.startTime);
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            if (data.sent)
                                return formatDate(data.received);
                        }
                    }, {
                        "data": null,
                        "class": "after-none",
                        "width": "5%",
                        render: function(data, type, full, meta) {
                            return '<img class= "tableIcone downloadMail hand w-13 mr-0" src="/svg/download\.svg " title="Download">';
                        }
                    }
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('hasHTML') && $(this).children().length == 0)
                            $(this).attr('title', $(this).html());
                    });
                },
                dom: 'Bfrtip',
                "fnDrawCallback": function(data) {
                    //-----------------------------------------//
                    var icon =
                        '<div class="search-container"><img class="search-icon session-search-icon" src="/svg/search.svg"></div>';
                    if ($(".dataTables_filter label").find('.search-icon').length == 0)
                        $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control');
                    //-----------------------------------------//
                    if (nextPartition && $('#detailsTable').DataTable().data().count() > 0) {
                        if (rowsArr.indexOf(nextRow) == -1) {
                            rowsArr.push(nextRow);
                            partitionsArr.push(nextPartition);
                        }
                        $('.nextPage').removeAttr("disabled");
                        $('.hide-next').addClass('hide');
                        $('.show-next').removeClass('hide');
                    } else {
                        $('.nextPage').attr("disabled", "disabled");
                        $('.hide-next').removeClass('hide');
                        $('.show-next').addClass('hide');
                    }
                    //-----------------------------------------//
                    if (rowsArr.indexOf(currentRow) >= 0 && $('#detailsTable').DataTable().data()
                        .count() > 0) {
                        $('.previousPage').removeAttr("disabled");
                        $('.show-pre').addClass('hide');
                        $('.hide-pre').removeClass('hide');
                    } else {
                        $('.previousPage').attr("disabled", "disabled");
                        $('.show-pre').removeClass('hide');
                        $('.hide-pre').addClass('hide');
                    }
                    //-----------------------------------------//
                    $(".currentPage").html(rowsArr.indexOf(currentRow) + 2);
                    $(".totalPages").html(Math.ceil(totalCount / pageItemsCount));
                    //-----------------------------------------//
                    $('#detailsTable_wrapper').find('.showingCount').html($(
                        '#detailsTable').DataTable().data().count());
                    //-----------------------------------------//
                    $('#detailsTable_wrapper').find('.boxesCount').html(totalCount);
                    //-----------------------------------------//
                    $('.mailBoxFolderItemCheck').change(function() {
                        if ($('.mailBoxFolderItemCheck:checked').length > 0)
                            $('.actions-btn button').removeAttr("disabled");
                        else
                            $('.actions-btn button').attr("disabled", "disabled");
                        $('.selectedCount').html($('.mailBoxFolderItemCheck:checked').length);
                    }).change();
                    //-----------------------------------------//
                    $('.tableIcone.downloadMail').unbind('click').click(function() {
                        var tr = $(this).closest('tr');
                        $('tr.current').removeClass('current');
                        tr.addClass('current');
                        downloadSingleMail();
                    });
                    //-----------------------------------------//
                },
                buttons: [

                    {
                        extend: 'csvHtml5',
                        text: '<img src="/svg/excel.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Export to csv'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<img src="/svg/pdf.svg" style="width:15px;height:30px;">',
                        customize: function(doc) {
                            customizePdf(doc)
                        },
                        titleAttr: 'Export to pdf',
                    },
                    {
                        text: '<img src="/svg/filter.svg" style="width:15px;height:30px;">',
                        titleAttr: 'Advanced Search',
                        action: function(e, dt, node, config) {
                            $('#firstSearchModal').modal('show');
                        }
                    }
                ],
                deferRender: true,
                "processing": false,
                "serverside": true,
                "scrollY": "300px",
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                language: {
                    sEmptyTable: function() {
                        let filter = $("#groupSelect").val();
                        if (!filter)
                            return "Please Select Mailbox";
                        return "No Data to View";
                    },
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                },
                'columnDefs': [{
                    'targets': [0, 10], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }],
                "order": [
                    [1, 'asc']
                ],
            });
            $('#detailsTable').DataTable().buttons().container()
                .prependTo('#detailsTable_filter');
            // $('#detailsTable_filter').addClass('hide');
            //---------------------------------------//
            $('#itemsResultsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" folderId="' + data.folderId + '" folderTitle="' +
                                data.folderTitle + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "mailboxTitle",
                        "width": "40%"
                    },
                    {
                        "data": "name",
                        "width": "60%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "148px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //---------------------------------------//
            $('#exportItemsResultsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" folderId="' + data.folderId + '" folderTitle="' +
                                data.folderTitle + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "mailboxTitle",
                        "width": "40%"
                    },
                    {
                        "data": "name",
                        "width": "60%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //---------------------------------------//
            $('#exportItemsZipResultsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none') && $(this).children().length == 0) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                "order": [
                    [1, 'asc']
                ],
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label style="top: 45%;left: 10px;" class="checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" mailboxId="' + data
                                .mailboxId + '" folderId="' + data.folderId + '" folderTitle="' +
                                data.folderTitle + '" mailboxTitle="' + data.mailboxTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "mailboxTitle",
                        "width": "40%"
                    },
                    {
                        "data": "name",
                        "width": "60%"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "200px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('input[name="folderType"]').change(function() {
                if ($(this).val() == 'original') {
                    $('.foldersAnotherOptions_cont.targetMailbox input[name="folder"]').attr('disabled',
                        'disabled').val('');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').attr('disabled',
                        'disabled').val('');
                } else {
                    $('.foldersAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                    $('.itemsAnotherOptions_cont.targetMailbox input[name="folder"]').removeAttr(
                        'disabled');
                }
            });
            $('input[name="folderType"]').change();
            //---------------------------------------//
            $('#groupSelect').select2();
            //---------------------------------------//
            $('#groupSelect').change(function() {
                filterPartition = $(this).val();
                nextPartition = null;
                nextRow = null;
                currentPartition = null;
                currentRow = null;
                partitionsArr = [];
                rowsArr = [];
                $('.allItemsCheck').prop("checked", false).change();
                $('#detailsTable').DataTable().ajax.reload();
            });
            //---------------------------------------//
            $('.allItemsCheck').change(function() {
                $('.mailBoxFolderItemCheck').prop("checked", $(this).prop("checked")).change();
            })
            //---------------------------------------//
        });
        //---------------------------------------//
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                res = true;
                let conditionsArray = [];

                if (settings.nTable.id !== 'detailsTable') {
                    return true;
                }

                if ($("#firstSuccess")[0].checked === true) {
                    conditionsArray.push("success");
                }
                if ($("#firstStop")[0].checked === true) {
                    conditionsArray.push("stop");
                }
                if ($("#firstWarning")[0].checked === true) {
                    conditionsArray.push("warning");
                }
                if ($("#firstError")[0].checked === true) {
                    conditionsArray.push("error");
                }
                if ($("#firstRunning")[0].checked === true) {
                    conditionsArray.push("running");
                }
                if (conditionsArray.length > 0 && (!data[2] || conditionsArray.indexOf(data[2].toLowerCase()) === -1)) {
                    res = false;
                }
                return res;
            }
        );
        //---------------------------------------//
        function getExchangeResult(result) {
            let resultArr = JSON.parse(result);
            let str = '<div class="col-lg-12 sess-info text-left" style="max-width:250px">';
            str += '<span class="col-lg-6 sess-title">Warning:</span>';
            str += '<span class="col-lg-6 sess-title">' + (resultArr.warnings ? resultArr.warnings.length : 0) + '</span>';
            str += '<span class="col-lg-6 sess-title">Failed Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.failedItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Merged Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.mergedItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Created Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.createdItemsCount + '</span>';
            str += '<span class="col-lg-6 sess-title">Skipped Items:</span>';
            str += '<span class="col-lg-6 sess-title">' + resultArr.skippedItemsCount + '</span>';
            str += '</div>';
            return str;
        }
        //---------------------------------------//
        function resetSearch() {
            $("#firstSuccess").attr('checked', false);
            $("#firstStop").attr('checked', false);
            $("#firstError").attr('checked', false);
            $("#firstWarning").attr('checked', false);
            $("#firstRunning").attr('checked', false);
            $('#detailsTable').DataTable().draw();
        }
        //---------------------------------------//
        function applySearch() {
            $('#detailsTable').DataTable().draw();
        }
        //---------------------------------------//
        function secondResetSearch() {
            $("#durationFrom").val("00:00:00");
            $("#durationTo").val("00:00:00");
            $("#secondSuccess").attr('checked', false);
            $("#secondStop").attr('checked', false);
            $("#secondError").attr('checked', false);
            $("#secondWarning").attr('checked', false);
            $("#secondRunning").attr('checked', false);
            $('#sessionsTable').DataTable().draw();
        }
        //---------------------------------------//
        function secondApplySearch() {
            $('#sessionsTable').DataTable().draw();
        }
        //---------------------------------------//
        function toMin(timeString) {

            let timeArr = timeString.split(":");
            return parseInt(timeArr[0]) * 60 + parseInt(timeArr[1]);
        }
        //---------------------------------------//
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            hours = '' + d.getHours();
            minutes = '' + d.getMinutes();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            if (hours.length < 2)
                hours = '0' + hours;
            if (minutes.length < 2)
                minutes = '0' + minutes;
            return month + "/" + day + "/" + year + " " + hours + ":" + minutes;

        }
        //----------------------------------------------------//
        function getNextPartition() {
            return nextPartition;
        }
        //----------------------------------------------------//
        function getFilterPartition() {
            return filterPartition;
        }
        //----------------------------------------------------//
        function getNextRow() {
            return nextRow;
        }
        //----------------------------------------------------//
        function showErrorMessage(message) {
            $(".danger-oper .danger-msg").html(message);
            $(".danger-oper").css("display", "block");
            setTimeout(function() {
                $(".danger-oper").css("display", "none");

            }, 8000);
        }
        //-------------------------------------------------------------//
        function checkTableCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onTableResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onTableResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //-------------------------------------------------------------//
        function onTableResultChange(tableName) {
            let folders = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let foldersCount = folders.length;
            let unresolvedCount = 0;
            $('#' + tableName + '_wrapper').find('.boxesCount').html(foldersCount);
        }
        //-------------------------------------------------------------//
        function showSuccessMessage(message) {
            $(".success-oper .success-msg").html(message);
            $(".success-oper").css("display", "block");
            setTimeout(function() {
                $(".success-oper").css("display", "none");
            }, 2000);
        }
        //-------------------------------------------------------------//
        function checkMailBoxCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onMailBoxResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onMailBoxResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //-------------------------------------------------------------//
        function restoreItemOriginalModal() {
            //--------------------//
            $('.modal-item-width').removeClass('modal-width');
            $('.modal-item-left').removeClass('custom-left-col');
            $('.modal-item-right').removeClass('custom-right-col');
            $('.modal-item-margin').removeClass('ml-100');
            // $('.modal-item-h-275p').removeClass('h-268p');
            $('.itemsAnotherOptions_cont').addClass('hide');
            $('.itemsAnotherOptions_cont .required').removeAttr('required');
            $('#restoreItem').find('.restoreType').val('original');
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                folderTitle = tr.find('.folderTitle').val();
                data.push({
                    "id": $(this).val(),
                    "mailboxTitle": mailboxTitle,
                    "folderTitle": folderTitle,
                    "mailboxId": boxId,
                    "name": tr.find('.subject').html(),
                });
            });

            //-----------------------------------------------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //-----------------------------------------------------------//
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').find('.modal-title').html("Restore Items to Original Location");
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').modal('show');
        }
        //----------------------------------------------------//
        function restoreItemAnotherModal() {
            //--------------------//
            $('.modal-item-width').addClass('modal-width');
            $('.modal-item-left').addClass('custom-left-col');
            $('.modal-item-right').addClass('custom-right-col');
            $('.modal-item-margin').addClass('ml-100');
            // $('.modal-item-h-275p').addClass('h-268p');
            $('.itemsAnotherOptions_cont').removeClass('hide');
            $('.itemsAnotherOptions_cont .required').attr('required', 'required');
            $('#restoreItem').find('.restoreType').val('another');
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            let boxId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "mailboxTitle": mailboxTitle,
                    "folderTitle": folderTitle,
                    "mailboxId": boxId,
                    "name": tr.find('.subject').html(),
                });
            });
            //--------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreItem').find('.modal-title').html("Restore Items to Another Location");
            //--------------------//
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').modal('show');
        }
        //----------------------------------------------------//
        function exportItemsModal() {
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "folderTitle": folderTitle,
                    "mailboxTitle": mailboxTitle,
                    "mailboxId": boxId,
                    "name": tr.find('.subject').html(),
                });
            });
            //-----------------------------------------------------------//
            $('#exportItemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportItemsResultsTable').DataTable().clear().draw();
            $('#exportItemsResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportItemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('exportItemsResultsTable');
            adjustTable();
            $("#exportItemsResultsTable").DataTable().draw();
            //-----------------------------------------------------------//
            $('#exportItemsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportItemsZipModal() {
            //--------------------//
            let items = $('input.mailBoxFolderItemCheck:checked');
            let data = [];
            items.each(function() {
                var tr = $(this).closest('tr');
                mailboxTitle = tr.find('.mailboxTitle').val();
                folderTitle = tr.find('.folderTitle').val();
                boxId = tr.find('.mailboxId').val();
                data.push({
                    "id": $(this).val(),
                    "folderTitle": folderTitle,
                    "mailboxTitle": mailboxTitle,
                    "mailboxId": boxId,
                    "name": tr.find('.subject').html(),
                });
            });
            //-----------------------------------------------------------//
            $('#exportItemsZipResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportItemsZipResultsTable').DataTable().clear().draw();
            $('#exportItemsZipResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportItemsZipResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable
            //--------------------//
            checkMailBoxCount('exportItemsZipResultsTable');
            adjustTable();
            $("#exportItemsZipResultsTable").DataTable().draw();
            //-----------------------------------------------------------//
            $('#exportItemsZipModal').modal('show');
        }
        //-------------------------------------------------------------//
        function downloadSingleMail() {
            let tr = $('tr.current');
            let item = tr.find('input.mailBoxFolderItemCheck').val();
            let boxId = tr.find('.mailboxId').val();
            let folderTitle = tr.find('.folderTitle').val();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSingleItem') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    mailboxId: boxId,
                    folderTitle: folderTitle,
                    itemId: item,
                    jobType: jobType,
                    jobId: jobId,
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    window.open(res.file);
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //---------------------------------------------------//





        //-------------------------------------------------------------//
        function CheckSessionsTable() {
            if ("{{ $data['job']['status'] }}" == 'In Progress')
                setTimeout(() => {
                    $('#sessionsTable').DataTable().ajax.reload();
                }, 15000);
        }
        //---------------------------------------------------//
        function previousPage() {
            nextPartition = partitionsArr[rowsArr.indexOf(currentRow) - 1];
            nextRow = rowsArr[rowsArr.indexOf(currentRow) - 1];
            $('.previousPage,.nextpage').attr("disabled", "disabled");
            $('.allItemsCheck').prop("checked", false).change();
            $('#detailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function nextPage() {
            $('.previousPage,.nextPage').attr("disabled", "disabled");
            $('.allItemsCheck').prop("checked", false).change();
            $('#detailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function filterItems() {
            $.ajax({
                "type": "GET",
                "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                "data": {
                    offset: 0,
                    filter: getFilterPartition(),
                    nextPartition: getNextPartition(),
                    nextRow: getNextRow(),
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                },
                "dataType": "json",
                "success": function(res) {
                    nextPartition = res.nextPartition;
                    nextRow = res.nextRow;
                    if (res.data.length > 0) {
                        $('#detailsTable').DataTable().rows.add(res.data);
                        $('#detailsTable').DataTable().columns.adjust().draw();
                    }
                },
            });
        }
        //---------------------------------------------------//
        function restoreItem() {
            event.preventDefault()
            var data = $('#restoreItemForm').serialize();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            //---------------------------------------//
            let mailboxes = $('#restoreItemForm .mailboxCheck:checked');
            let mailboxArr = [];
            mailboxes.each(function() {
                if (mailboxArr.filter(e => e.mailboxId === $(this).attr("mailboxId")).length == 0) {
                    let mailboxItems = $('#restoreItemForm .mailboxCheck:checked[mailboxId="' + $(this).attr(
                        "mailboxId") + '"]');
                    let mailboxItemsArr = [];
                    mailboxItems.each(function() {
                        let tr = $(this).closest('tr');
                        mailboxItemsArr.push({
                            "id": $(this).val(),
                            "mailboxTitle": $(this).attr("mailboxTitle"),
                            "name": tr.find('td:nth-child(3)').html(),
                            "folderTitle": $(this).attr("mailboxTitle"),
                        })
                    });
                    mailboxArr.push({
                        "mailboxId": $(this).attr("mailboxId"),
                        "mailboxTitle": $(this).attr("mailboxTitle"),
                        "items": mailboxItemsArr
                    });
                }
            });
            //---------------------------------------//
            $.ajax({
                type: "POST",
                url: "{{ url('restoreItem') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&items=' + JSON.stringify(mailboxArr) +
                    "&_token={{ csrf_token() }}" +
                    "&jobType=" + jobType +
                    "&folderTitle=" + $("#restoreItemForm").find("#folder").val(),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    setTimeout(function() {}, 4000)
                    $('#restoreItem').modal('hide');
                },
                "statusCode": {
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
            return false;
        }
        //----------------------------------------------------//
        function exportMailBoxFolderItemsToPst() {
            event.preventDefault();
            //--------------------------------------------------//
            let items = $('#exportItemsForm .mailboxCheck:checked');
            let data = $('#exportItemsForm').serialize();
            //----------------------------------------------//
            let mailboxes = $('#exportItemsForm .mailboxCheck:checked');
            let mailboxArr = [];
            mailboxes.each(function() {
                if (mailboxArr.filter(e => e.mailboxId === $(this).attr("mailboxId")).length == 0) {
                    let mailboxItems = $('#exportItemsForm .mailboxCheck:checked[mailboxId="' + $(this).attr(
                        "mailboxId") + '"]');
                    let mailboxItemsArr = [];
                    mailboxItems.each(function() {
                        let tr = $(this).closest('tr');
                        mailboxItemsArr.push({
                            "id": $(this).val(),
                            "mailboxTitle": $(this).attr("mailboxTitle"),
                            "name": tr.find('td:nth-child(3)').html(),
                            "folderTitle": $(this).attr("mailboxTitle"),
                        })
                    });
                    mailboxArr.push({
                        "mailboxId": $(this).attr("mailboxId"),
                        "mailboxTitle": $(this).attr("mailboxTitle"),
                        "items": mailboxItemsArr
                    });
                }
            });
            //--------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderItemsToPst') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data +
                    "&_token={{ csrf_token() }}" +
                    "&items=" + JSON.stringify(mailboxArr) +
                    "&jobType=" + "{{ $data['job']->restore_point_type }}",
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    $('#exportItemsModal').modal('hide');
                },
                "statusCode": {
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //----------------------------------------------------//
        function exportMailBoxFolderItemsToZip() {
            event.preventDefault();
            //--------------------------------------------------//
            let items = $('#exportItemsZipForm .mailboxCheck:checked');
            let data = $('#exportItemsZipForm').serialize();
            //----------------------------------------------//
            let mailboxes = $('#exportItemsZipForm .mailboxCheck:checked');
            let mailboxArr = [];
            mailboxes.each(function() {
                if (mailboxArr.filter(e => e.mailboxId === $(this).attr("mailboxId")).length == 0) {
                    let mailboxItems = $('#exportItemsZipForm .mailboxCheck:checked[mailboxId="' + $(this).attr(
                        "mailboxId") + '"]');
                    let mailboxItemsArr = [];
                    mailboxItems.each(function() {
                        let tr = $(this).closest('tr');
                        mailboxItemsArr.push({
                            "id": $(this).val(),
                            "mailboxTitle": $(this).attr("mailboxTitle"),
                            "name": tr.find('td:nth-child(3)').html(),
                            "folderTitle": $(this).attr("mailboxTitle"),
                        })
                    });
                    mailboxArr.push({
                        "mailboxId": $(this).attr("mailboxId"),
                        "mailboxTitle": $(this).attr("mailboxTitle"),
                        "items": mailboxItemsArr
                    });
                }
            });
            //--------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportMailBoxFolderItemsToZip') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data +
                    "&_token={{ csrf_token() }}" +
                    "&items=" + JSON.stringify(mailboxArr),
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    $('#exportItemsZipModal').modal('hide');
                },
                "statusCode": {
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
                    showErrorMessage(errMessage);
                }
            });
        }
        //----------------------------------------------------//
    </script>
    <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
@endsection
