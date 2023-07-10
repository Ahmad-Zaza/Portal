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
                    href="{{ url('e-discovery', [$data['kind'],'result',$data['job']['restore_session_guid']]) }}">{{$data['job']['name']}} - Result</a></li>
            <!-- Authentication Links -->
            @include('layouts.authentication-links')
        </ul>
    </div>

@endsection
@section('content')
    <div id="mainContent">
        <!-- ----------------------------------------------------------------------------------------------------------------------------- -->
        <div id="exportSiteDocumentsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg mt-10v">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Site Documents To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportSiteDocumentsForm" class="mb-0" onsubmit="exportSiteDocuments(event)">
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
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Site Documents</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth">
                                            <table id="exportDocsResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>File</th>
                                                        <th>Library</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> documents selected
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
        <div id="exportSiteItemsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Site Items Attachments To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportSiteItemsForm" class="mb-0" onsubmit="exportSiteItems(event)">
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
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Site Items</h5>
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
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Item</th>
                                                        <th>List</th>
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
        <div id="restoreSiteDocumentsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-10v">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-100 mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Restore Selected Documents</h4>
                            </div>
                        </div>
                        <form id="restoreSiteDocumentsForm" class="mb-0" onsubmit="restoreSiteDocuments(event)">
                            <div class="custom-left-col">
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
                                        <h5 class="txt-blue mt-0">Target Library</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3">
                                            <div class="allWidth">
                                                <div class="authCont custom-authCont pt-3 pb-3">
                                                    <label class="mr-4 m-0 nowrap">Library to Restore To:</label>
                                                    <div class="radioDiv">
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="original" checked>Original
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="custom" checked>Following
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" required
                                                    class="required form-control form_input custom-form-control font-size"
                                                    id="list" placeholder="Library" name="list" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Site Documents</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-127p">
                                            <div class="allWidth">
                                                <table id="docsResultsTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked class="form-check-input">
                                                                    <span class="tree-checkBox check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>File</th>
                                                            <th>Library</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> documents selected
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="flex">
                                                <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="Last"
                                                                checked="">Last
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="All">All
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex pt-1 pb-1">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv pb-2">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
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
        <div id="restoreSiteItemsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-10v">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-100 mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Restore Selected Items</h4>
                            </div>
                        </div>
                        <form id="restoreSiteItemsForm" class="mb-0" onsubmit="restoreSiteItems(event)">
                            <div class="custom-left-col">
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
                                <div class="row restoreAnother_cont">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Target Items</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3">
                                            <div class="allWidth">
                                                <div class="authCont custom-authCont pt-3 pb-3">
                                                    <label class="mr-4 m-0 nowrap">List to Restore To:</label>
                                                    <div class="radioDiv">
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="original" checked>Original
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="custom" checked>Following
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" required
                                                    class="required form-control form_input custom-form-control font-size"
                                                    id="list" placeholder="List" name="list" required autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Site Items</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-127p">
                                            <div class="allWidth">
                                                <table id="itemsResultsTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked class="form-check-input">
                                                                    <span class="tree-checkBox check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Item</th>
                                                            <th>List</th>
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
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="flex">
                                                <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="Last"
                                                                checked="">Last
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="All">All
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex pt-1 pb-1">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv pb-1">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
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
        <div id="restoreFoldersModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-10v">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-100 mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Restore Selected Folders</h4>
                            </div>
                        </div>
                        <form id="restoreFoldersForm" class="mb-0" onsubmit="restoreFolders(event)">
                            <div class="custom-left-col">
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
                                        <h5 class="txt-blue mt-0">Target List</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3">
                                            <div class="allWidth">
                                                <div class="authCont custom-authCont pt-3 pb-3">
                                                    <label class="mr-4 m-0 nowrap">List to Restore To:</label>
                                                    <div class="radioDiv">
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="original" checked>Original
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="radio m-0">
                                                            <label>
                                                                <input type="radio" name="listType" class="listType"
                                                                    value="custom" checked>Following
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" required
                                                    class="required form-control form_input custom-form-control font-size"
                                                    id="list" placeholder="Target" name="list" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-right-col">

                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Selected Folders</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-127p">
                                            <div class="allWidth">
                                                <table id="foldersResultTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-top-left checkbox-container checkbox-search">
                                                                    <input type="checkbox" checked class="form-check-input">
                                                                    <span class="tree-checkBox check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Site</th>
                                                            <th>Content</th>
                                                            <th>Folder</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> folders selected
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="flex">
                                                <label class="mr-4 m-0 nowrap">Documents Version:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="Last"
                                                                checked="">Last
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="documentVersion"
                                                                class="documentVersion" id="documentVersion" value="All">All
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex pt-1 pb-1">
                                                <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                            </div>
                                            <div class="radioDiv pb-1">
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Overwrite"
                                                            checked="">Overwrite
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="radio m-0">
                                                    <label>
                                                        <input type="radio" name="documentLastVersionAction"
                                                            class="documentLastVersionAction" value="Merge">Merge
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="sendSharedLinksNotification" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Send Shared Links
                                                                Notifications</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Permissions</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
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
        <div id="exportFoldersModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p">Export Selected Folders To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportFoldersForm" class="mb-0" onsubmit="exportFolders(event)">
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
                            <div class="row">
                                <div class="input-form-70 mb-1">
                                    <h5 class="txt-blue mt-0">Selected Folders</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth">
                                            <table id="exportFoldersResultsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-top-left checkbox-container checkbox-search">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Site</th>
                                                        <th>Content</th>
                                                        <th>Folders</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> folders selected
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
        <div class="row main-button-cont">
            <div class="btnMain main-button flex mb-40">
                <div class="btnUpMask"></div>
                <div class="row m-0 pl-4 pr-4 allWidth">
                    <div class="col-lg-3 siteFoldersButton">
                        <div class="selected-action allWidth relative folder-actions-btn">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Folders Actions
                                <span class="selectedFoldersCount"></span>
                                <span class="fa fa-caret-down"></span></button>

                            <ul class="dropdown-menu allWidth">
                                <li>
                                    <a href="javascript:restoreFoldersModal(event)" class="tooltipSpan"
                                        title="Restore Selected Folders">
                                        Restore Selected Folders
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:exportFoldersModal(event)" class="tooltipSpan"
                                        title="Export Selected Folders to .Zip">
                                        Export Selected Folders to .Zip
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 siteItemsButton">
                        <div class="selected-action allWidth relative documents-actions-btn">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Documents Actions
                                <span class="selectedItemCount"></span>
                                <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu allWidth">
                                <li class="docLi">
                                    <a href="javascript:restoreSiteDocumentsModal(event)" class="tooltipSpan"
                                        title="Restore Selected Documents">
                                        Restore Selected Documents
                                    </a>
                                </li>
                                <li class="docLi">
                                    <a href="javascript:exportSiteDocumentsModal(event)" class="tooltipSpan"
                                        title="Export Selected Documents to .Zip">
                                        Export Selected Documents to .Zip
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 siteItemsButton">
                        <div class="selected-action allWidth relative items-actions-btn">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Items Actions
                                <span class="selectedItemCount"></span>
                                <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu allWidth">
                                <li class="itemsLi">
                                    <a href="javascript:restoreSiteItemsModal(event)" class="tooltipSpan"
                                        title="Restore Selected Items">
                                        Restore Selected Items
                                    </a>
                                </li>
                                <li class="itemsLi">
                                    <a href="javascript:exportSiteItemsModal(event)" class="tooltipSpan"
                                        title="Export Selected Items Attachments">
                                        Export Selected Items Attachments
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
        <div class="row display-root">
            <div class="col-lg-4 z-index-500 select-custom main-filter-cont">
                <select style="width: 100%!important" name="groupSelect" id="groupSelect"
                class="form-control form_input required js-data-example-ajax select2">
                <option value="">Select Site</option>
                @foreach ($data['search_data'] as $item)
                    <option value="{{ $item->siteTitle.($item->listName?"_".$item->listName:"") }}">{{ $item->siteTitle.($item->listName?": ".$item->listName:"") }}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div class="jobsTable" style="margin-left:0px; margin-top: -38px;">
            <table id="detailsTable" class="stripe table table-striped table-dark display nowrap allWidth table-text-truncate" style="width:100%">
                <thead class="table-th">
                    <tr>
                        <th>
                            <label
                                class="checkbox-top-left checkbox-container checkbox-search left-17">
                                <input type="checkbox" class="form-check-input allItemsCheck">
                                <span
                                    class="tree-checkBox check-mark-white check-mark"></span>
                            </label>
                        </th>
                        <th class="text-left">Site</th>
                        <th>Name</th>
                        <th>Created By</th>
                        <th>Creation Time</th>
                        <th>Modified By</th>
                        <th>Modification Time</th>
                        <th></th>
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
                <button class="pl-0 btn-sm custom-btn-sm hand previousPage"
                    disabled="disabled" onclick="previousPage()" title="Previous">
                    <img class="iconColor mt-0 mr-0 hide-pre hide" src="{{ url('/svg/Pre.svg') }}">
                    <img class="iconColor mt-0 mr-0 show-pre" src="{{ url('/svg/Pre-02.svg') }}">
                </button>
                <div class="flex mr-0 align-center">
                    Page <span class="ml-1 mr-1 currentPage">0</span> of <span class="ml-1 mr-0 totalPages">0</span>
                </div>
                <button class="btn-sm custom-btn-sm hand mr-0 nextPage"
                    onclick="nextPage()" title="Next">
                    <img class="iconColor mt-0 show-next" src="{{ url('/svg/dash-next.svg') }}">
                    <img class="iconColor mt-0 hide-next hide" src="{{ url('/svg/Next-01.svg') }}">
                </button>
            </div>
            <div class="col-lg-4"></div>
            <div class="col-lg-4 nopadding flex flex-row-reverse">
                <a href="{{ url('e-discovery', $data['kind']) }}"
                    class="cancel-button btn_primary_state mr-0 min-width-150 justify-content-center">
                    Cancel</a>
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
                    "url": "{{ url('getEdiscoveryJobResult',[$data['kind'], optional($data)['job']->id]) }}",
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
                            $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
                            $(".danger-oper").css("display", "block");
                            setTimeout(function() {
                                                        $(".danger-oper").css("display", "none");
                        window.location.reload();
                            }, 3000);
                        }
                    },
                    "dataType": "json",
                    "beforeSend": function(){
                        $('#detailsTable > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="35" class="dataTables_empty processing_row"><span class="table-spinner"></span></td>' +
                        '</tr>');
                        currentPartition = nextPartition;
                        currentRow = nextRow;
                    }
                },
                'columns': [{
                        "class": "w-49 after-none",
                        "data": null,
                        render: function(data) {
                            return ''+
                                ((data.itemType=="folder") ?
                                    '<img class= "tableIcone w-13 mr-0 ml-4 mt-1" src="/svg/folders/none\.svg " title="Folder">' :
                                    '' +
                                    '<img class= "tableIcone w-13 mr-0 ml-4 mt-1" src="/svg/folders/tasks\.svg " title="File">'
                                )+
                                '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                '<input type="hidden" class="siteId" value="' + data.siteId +
                                '">' +
                                '<input type="hidden" class="siteTitle" value="' + data
                                .siteTitle + '">' +
                                '<input type="hidden" class="listId contentId" value="' + data.listId +
                                '">' +
                                '<input type="hidden" class="listTitle contentTitle" value="' + data
                                .listTitle + '">' +
                                '<input type="checkbox" data-type="'+data.type+'" data-itemType="'+data.itemType+'" class="singleItemCheck form-check-input" value="' +
                                data.id + '"/>' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span></label>';

                        }
                    },
                    {
                        "class": "text-left",
                        "data": null,
                        "render": function(data){
                            if(data.listTitle)
                                return data.siteTitle+": "+data.listTitle;
                            return data.siteTitle;
                        }
                    },
                    {
                        "data": "name",
                        "class": "fileNameColumn wrap"
                    },
                    {
                        "data": "createdBy",
                    },
                    {
                        "data": null,
                        "render": function(data){
                            return formatDate(data.creationTime);
                        }
                    },
                    {
                        "data": "modifiedBy",
                    },
                    {
                        "data": null,
                        "render": function(data){
                            return formatDate(data.modificationTime);
                        }
                    },
                    {
                        "data": null,
                        "class": "after-none",
                        "width": "5%",
                        render: function(data, type, full, meta) {
                            if(data.itemType == "document")
                                return '<img class= "hand tableIcone downloadMail w-13 mr-0" src="/svg/download\.svg " title="Download">';
                            return "";
                        }
                    },{
                        "data":null,
                        "render": function(data){
                            if(data.itemType == "folder")
                                return true;
                            return false;
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
                    if($(".dataTables_filter label").find('.search-icon').length == 0)
                    $('.dataTables_filter label').append(icon);
                    $('.dataTables_filter input').addClass('form_input form-control');
                    //-----------------------------------------//
                    if(nextPartition && $('#detailsTable').DataTable().data().count() > 0){
                        if(rowsArr.indexOf(nextRow) == -1){
                            rowsArr.push(nextRow);
                            partitionsArr.push(nextPartition);
                        }
                        $('.nextPage').removeAttr("disabled");
                        $('.hide-next').addClass('hide');
                        $('.show-next').removeClass('hide');
                    } else {
                        $('.nextPage').attr("disabled","disabled");
                        $('.hide-next').removeClass('hide');
                        $('.show-next').addClass('hide');
                    }
                    //-----------------------------------------//
                    if(rowsArr.indexOf(currentRow) >= 0 && $('#detailsTable').DataTable().data().count() > 0){
                        $('.previousPage').removeAttr("disabled");
                        $('.show-pre').addClass('hide');
                        $('.hide-pre').removeClass('hide');
                    } else {
                        $('.previousPage').attr("disabled","disabled");
                        $('.show-pre').removeClass('hide');
                        $('.hide-pre').addClass('hide');
                    }
                    //-----------------------------------------//
                    $(".currentPage").html(rowsArr.indexOf(currentRow)+2);
                    $(".totalPages").html(Math.ceil(totalCount/pageItemsCount));
                    //-----------------------------------------//
                    $('#detailsTable_wrapper').find('.showingCount').html($(
                        '#detailsTable').DataTable().data().count());
                    //-----------------------------------------//
                    $('#detailsTable_wrapper').find('.boxesCount').html(totalCount);
                    //-----------------------------------------//
                    $('.singleItemCheck').change(function(){
                        if($('.singleItemCheck[data-itemType="document"]:checked').length > 0)
                            $('.documents-actions-btn button').removeAttr("disabled");
                        else
                            $('.documents-actions-btn button').attr("disabled","disabled");

                        if($('.singleItemCheck[data-itemType="folder"]:checked').length > 0)
                            $('.folder-actions-btn button').removeAttr("disabled");
                        else
                            $('.folder-actions-btn button').attr("disabled","disabled");

                        if($('.singleItemCheck[data-itemType="item"]:checked').length > 0)
                            $('.items-actions-btn button').removeAttr("disabled");
                        else
                            $('.items-actions-btn button').attr("disabled","disabled");

                        $('.selectedCount').html($('.singleItemCheck:checked').length);
                    }).change();
                    //-----------------------------------------//
                    $('.tableIcone.downloadMail').unbind('click').click(function() {
                        var tr = $(this).closest('tr');
                        $('tr.current').removeClass('current');
                        tr.addClass('current');
                        downloadSingleDocument();
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
                "scrollY": "500px",
                "bInfo": false,
                "paging": false,
                "autoWidth": false,
                language: {
                    sEmptyTable: function() {
                        let filter = $("#groupSelect").val();
                        if (!filter)
                            return "Please Select Site";
                        return "No Data to View";
                    },
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                },
                "orderFixed": {
                    "pre": [8, 'dexc']
                },
                'columnDefs': [{
                    'targets': [0,7], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                },{
                    'targets': [8], // column index (start from 0)
                    'visible': false, // set orderable false for selected columns
                }],
                "order": [
                    [1, 'asc']
                ],
            });
            $('#detailsTable').DataTable().buttons().container()
                .prependTo('#detailsTable_filter');
            // $('#detailsTable_filter').addClass('hide');
            //---------------------------------------//

            $('#foldersResultTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "siteTitle"
                    },
                    {
                        "data": "contentTitle"
                    },
                    {
                        "data": "name"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "59px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#exportFoldersResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="' + data
                                .siteId + '" data-contentId="' + data.contentId + '">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "siteTitle"
                    },
                    {
                        "data": "contentTitle"
                    },
                    {
                        "data": "name"
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

            $('#docsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="'+data.siteId+'" data-contentId="'+data.contentId+'">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "59px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });


            $('#itemsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="'+data.siteId+'" data-contentId="'+data.contentId+'">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "59px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#exportDocsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="'+data.siteId+'" data-contentId="'+data.contentId+'">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
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


            $('#exportItemsResultsTable').DataTable({
                "data": [],
                "order": [
                    [2, 'asc']
                ],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
                            $(this).attr('title', $(this).html());
                        }
                    });
                },
                'bAutoWidth': false,
                'columns': [{
                        "data": null,
                        "class": 'after-none',
                        "render": function(data) {
                            return '<label class="checkbox-top-left checkbox-container checkbox-search">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-siteId="'+data.siteId+'" data-contentId="'+data.contentId+'">' +
                                '<input class="siteId" value="' + data.siteId + '">' +
                                '<input class="contentId" value="' + data.contentId + '">' +
                                '<input class="contentTitle" value="' + data.contentTitle + '">' +
                                '<input class="siteTitle" value="' + data.siteTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "contentTitle"
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
            $('#groupSelect').select2();
            //---------------------------------------//
            $('#groupSelect').change(function(){
                filterPartition = $(this).val();
                nextPartition = null;
                nextRow = null;
                currentPartition = null;
                currentRow = null;
                partitionsArr = [];
                rowsArr = [];
                $('.allItemsCheck').prop("checked",false).change();
                $('#detailsTable').DataTable().ajax.reload();
            });
            //---------------------------------------//
            $('.allItemsCheck').change(function(){
                $('.singleItemCheck').prop("checked",$(this).prop("checked")).change();
            })
            //---------------------------------------//
            $('input[name="siteType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('[name="alias"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('[name="alias"]').removeAttr('disabled');
                }
            });
            $('input[name="siteType"]').change();
            //--------------------------------------------------//
            $('input[name="listType"]').change(function() {
                let form = $(this).closest('form');
                if ($(this).val() == 'original') {
                    form.find('input[name="list"]').attr('disabled', 'disabled').val('');
                } else {
                    form.find('input[name="list"]').removeAttr('disabled');
                }
            });
            $('input[name="listType"]').change();
            //--------------------------------------------------//
            //---------------------------------------//
        });
        //---------------------------------------//

        function toMin(timeString) {

            let timeArr = timeString.split(":");
            return parseInt(timeArr[0]) * 60 + parseInt(timeArr[1]);
        }

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
        function getNextPartition(){
            return nextPartition;
        }
        //----------------------------------------------------//
        function getFilterPartition(){
            return filterPartition;
        }
        //----------------------------------------------------//
        function getNextRow(){
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
            let lists = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let listsCount = lists.length;
            let unresolvedCount = 0;
            $('#' + tableName + '_wrapper').find('.boxesCount').html(listsCount);
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



        //------- Ajax Functions
        //---------------------------------------------------//
        function downloadSingleDocument() {
            let tr = $('tr.current');
            let item = tr.find('input.singleItemCheck').val();
            let pointSiteId = tr.find('.siteId').val();
            let libraryTitle = tr.find('.contentTitle').val();
            let siteTitle = tr.find('.siteTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            let contentType = tr.find('input.singleItemCheck').attr('data-type');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSiteDocument') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: jobId,
                    jobTime: jobTime,
                    jobType: jobType,
                    showDeleted: showDeleted,
                    showVersions: showVersions,
                    siteId: pointSiteId,
                    libraryTitle: libraryTitle,
                    fileSize: fileSize,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    siteTitle: siteTitle,
                    contentType: contentType,
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    if (res.message)
                        showSuccessMessage(res.message);
                    if (res.file)
                        window.open(res.file);
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function downloadSingleItem() {
            let tr = $('tr.current');
            let item = tr.find('input.singleItemCheck').val();
            let pointSiteId = tr.find('.siteId').val();
            let listTitle = tr.find('.contentTitle').val();
            let siteTitle = tr.find('.siteTitle').val();
            let contentType = tr.find('input.singleItemCheck').attr('data-type');
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadSiteItem') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: jobId,
                    jobTime: jobTime,
                    jobType: jobType,
                    showDeleted: showDeleted,
                    showVersions: showVersions,
                    siteId: pointSiteId,
                    listTitle: listTitle,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    siteTitle: siteTitle,
                    contentType: contentType,
                    jobId: jobId
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    if (res.message)
                        showSuccessMessage(res.message);
                    if (res.file)
                        window.open(res.file);
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function downloadDocument() {
            let items = $('input.singleItemCheck:checked');
            let itemsArr = [];
            let boxId = '';
            items.each(function() {
                var tr = $(this).closest('tr');
                boxId = tr.find('.siteId').val();
                itemsArr.push({
                    "id": $(this).val()
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadItem') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    siteId: boxId,
                    items: JSON.stringify(itemsArr),
                    jobId: jobId
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(res.message);
                    window.open('/' + res.file);
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        function exportSiteDocuments() {
            event.preventDefault()
            let docs = $('#exportSiteDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function(){
                let tr = $(this).closest('tr');
                if(docsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr("data-contentId")).length == 0){
                    let siteDocs = $('#exportSiteDocumentsForm .mailboxCheck:checked[data-siteId="'+$(this).attr("data-siteId")+'"][data-contentId="'+$(this).attr("data-contentId")+'"]');
                    let siteDocsArr = [];
                    siteDocs.each(function(){
                        tr = $(this).closest('tr');
                        siteDocsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    docsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteDocsArr
                    });
                }
            });
            //-----------------------------------//
            data = "docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteDocuments') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportSiteDocumentsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportSiteDocumentsModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//
        function exportSiteItems() {
            event.preventDefault()
            let docs = $('#exportSiteItemsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('data-siteId');
                docsArr.push({
                    id: $(this).val(),
                    name: tr.find('td:nth-child(2)').html(),
                    siteId: tr.find('.siteId').val(),
                    siteTitle: tr.find('.siteTitle').val(),
                    contentTitle: tr.find('.contentTitle').val(),
                    folderName: tr.find('.folderTitle').val(),
                });
            });
            //-----------------------------------//
            data = "docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteItems') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportSiteItemsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportSiteItemsModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//
        function restoreSiteDocuments() {
            event.preventDefault()
            //-----------------------------------//
            let docs = $('#restoreSiteDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            //-----------------------------------//
            docs.each(function(){
                let tr = $(this).closest('tr');
                if(docsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr("data-contentId")).length == 0){
                    let siteDocs = $('#restoreSiteDocumentsForm .mailboxCheck:checked[data-siteId="'+$(this).attr("data-siteId")+'"][data-contentId="'+$(this).attr("data-contentId")+'"]');
                    let siteDocsArr = [];
                    siteDocs.each(function(){
                        tr = $(this).closest('tr');
                        siteDocsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    docsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteDocsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreSiteDocumentsForm').serialize();
            data += "&restorePermissions=" + $("#restoreSiteDocumentsForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreSiteDocumentsForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteDocuments') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#restoreSiteDocumentsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreSiteDocumentsModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//
        function restoreSiteItems() {
            event.preventDefault()
            //-----------------------------------//
            let items = $('#restoreSiteItemsForm .mailboxCheck:checked');
            let itemsArr = [];
            //-----------------------------------//
            items.each(function(){
                let tr = $(this).closest('tr');
                if(itemsArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr("data-contentId")).length == 0){
                    let siteItems = $('#restoreSiteItemsForm .mailboxCheck:checked[data-siteId="'+$(this).attr("data-siteId")+'"][data-contentId="'+$(this).attr("data-contentId")+'"]');
                    let siteItemsArr = [];
                    siteItems.each(function(){
                        tr = $(this).closest('tr');
                        siteItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    itemsArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreSiteItemsForm').serialize();
            data += "&restorePermissions=" + $("#restoreSiteItemsForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreSiteItemsForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&items=" + JSON.stringify(itemsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteItems') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#restoreSiteItemsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreSiteItemsModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//
        function restoreFolders() {
            event.preventDefault()
            let folders = $('#restoreFoldersForm .mailboxCheck:checked');
            let foldersArr = [];
            //-----------------------------------//
            folders.each(function(){
                let tr = $(this).closest('tr');
                if(foldersArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr("data-contentId")).length == 0){
                    let siteFolders = $('#restoreFoldersForm .mailboxCheck:checked[data-siteId="'+$(this).attr("data-siteId")+'"][data-contentId="'+$(this).attr("data-contentId")+'"]');
                    let siteFoldersArr = [];
                    siteFolders.each(function(){
                        tr = $(this).closest('tr');
                        siteFoldersArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(4)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    foldersArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteFoldersArr
                    });
                }
            });
            var data = $('#restoreFoldersForm').serialize();
            data += "&restorePermissions=" + $("#restoreFoldersForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreFoldersForm [name='sendSharedLinksNotification']")[0]
                .checked;
            data += "&folders=" + JSON.stringify(foldersArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreSiteFolders') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#restoreFoldersForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreFoldersModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//
        function exportFolders() {
            event.preventDefault()
            //-----------------------------------//
            let folders = $('#exportFoldersForm .mailboxCheck:checked');
            let foldersArr = [];
            //-----------------------------------//
            folders.each(function(){
                let tr = $(this).closest('tr');
                if(foldersArr.filter(e => e.siteId === $(this).attr("data-siteId") && e.contentId === $(this).attr("data-contentId")).length == 0){
                    let siteFolders = $('#exportFoldersForm .mailboxCheck:checked[data-siteId="'+$(this).attr("data-siteId")+'"][data-contentId="'+$(this).attr("data-contentId")+'"]');
                    let siteFoldersArr = [];
                    siteFolders.each(function(){
                        tr = $(this).closest('tr');
                        siteFoldersArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(4)').html(),
                            "siteTitle": tr.find('.siteTitle').val(),
                            "contentTitle": tr.find('.contentTitle').val(),
                            "contentId": $(this).attr("data-contentId"),
                            "siteId": $(this).attr("data-siteId"),
                        })
                    });
                    foldersArr.push({
                        "siteId": $(this).attr("data-siteId"),
                        "siteTitle": tr.find('.siteTitle').val(),
                        "contentTitle": tr.find('.contentTitle').val(),
                        "contentId": $(this).attr("data-contentId"),
                        "items": siteFoldersArr
                    });
                }
            });
            //-----------------------------------//
            var data = "folders=" + JSON.stringify(foldersArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportSiteFolders') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportFoldersForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportFoldersModal').modal('hide');
                },
                "statusCode": {
                    401: function() {
                        window.location.href = "{{ url('/') }}";
                    },
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
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
        //---------------------------------------------------//




        //------- Modal Functions
        //---------------------------------------------------//
        function exportSiteDocumentsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="document"]:checked');
            let tableData = [];
            let pointSiteId, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                contentId = tr.find('.contentId').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": contentId,
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            //--------------------//
            $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportDocsResultsTable').DataTable().clear().draw();
            $('#exportDocsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportDocsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportDocsResultsTable');
            adjustTable();
            $("#exportDocsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportSiteDocumentsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportSiteItemsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="item"]:checked');
            let tableData = [];
            let pointSiteId, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                contentId = tr.find('.contentId').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": contentId,
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            //--------------------//
            $('#exportItemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportItemsResultsTable').DataTable().clear().draw();
            $('#exportItemsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportItemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportItemsResultsTable');
            adjustTable();
            $("#exportItemsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportSiteItemsModal').modal('show');
        }
        //----------------------------------------------------//
        function exportFoldersModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="folder"]:checked');
            let tableData = [];
            let pointSiteId, contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                contentId = tr.find('.contentId').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            //--------------------//
            $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportFoldersResultsTable').DataTable().clear().draw();
            $('#exportFoldersResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportFoldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFoldersResultsTable');
            adjustTable();
            $("#exportFoldersResultsTable").DataTable().draw();
            //--------------------//
            $('#exportFoldersModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreSiteDocumentsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="document"]:checked');
            let tableData = [];
            let pointSiteId;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                contentId = tr.find('.contentId').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });

            //--------------------//
            $('#docsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsResultsTable').DataTable().clear().draw();
            $('#docsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsResultsTable');
            adjustTable();
            $("#docsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreSiteDocumentsModal').find(".refreshDeviceCode").click();
            $('#restoreSiteDocumentsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreSiteItemsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="item"]:checked');
            let tableData = [];
            let pointSiteId;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            //--------------------//
            $('#itemsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#itemsResultsTable').DataTable().clear().draw();
            $('#itemsResultsTable').DataTable().rows.add(tableData); // Add new data
            $('#itemsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('itemsResultsTable');
            adjustTable();
            $("#itemsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreSiteItemsModal').find(".refreshDeviceCode").click();
            $('#restoreSiteItemsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreFoldersModal() {
            //--------------------//
            let items = $('input.singleItemCheck[data-itemType="folder"]:checked');
            let tableData = [];
            let pointSiteId;
            let contentTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                siteTitle = tr.find('.siteTitle').val();
                pointSiteId = tr.find('.siteId').val();
                contentTitle = tr.find('.contentTitle').val();
                tableData.push({
                    "id": $(this).val(),
                    "siteId": pointSiteId,
                    "siteTitle": siteTitle,
                    "contentId": tr.find('.contentId').val(),
                    "contentTitle": contentTitle,
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            //--------------------//
            $('#foldersResultTable_wrapper').find('.boxesCount').html(items.length);
            $('#foldersResultTable').DataTable().clear().draw();
            $('#foldersResultTable').DataTable().rows.add(tableData); // Add new data
            $('#foldersResultTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('foldersResultTable');
            adjustTable();
            $("#foldersResultTable").DataTable().draw();
            //--------------------//
            $('#restoreFoldersModal').find(".refreshDeviceCode").click();
            $('#restoreFoldersModal').modal('show');
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
        function previousPage(){
            nextPartition = partitionsArr[rowsArr.indexOf(currentRow)-1];
            nextRow = rowsArr[rowsArr.indexOf(currentRow)-1];
            $('.previousPage,.nextpage').attr("disabled","disabled");
            $('.allItemsCheck').prop("checked",false).change();
            $('#detailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function nextPage(){
            $('.previousPage,.nextPage').attr("disabled","disabled");
            $('.allItemsCheck').prop("checked",false).change();
            $('#detailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function filterItems(){
            $.ajax({
                "type": "GET",
                "url": "{{ url('getEdiscoveryJobResult',[$data['kind'], optional($data)['job']->id]) }}",
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
                    402: function() {
                        let errMessage = "   ERROR   ";
                        $(".danger-oper .danger-msg").html("{{__('variables.errors.restore_session_expired')}}");
                        $(".danger-oper").css("display", "block");
                        setTimeout(function() {
                                                    $(".danger-oper").css("display", "none");
                        window.location.reload();
                        }, 3000);
                    }
                },
                "dataType": "json",
                "success": function(res){
                    nextPartition = res.nextPartition;
                    nextRow = res.nextRow;
                    if(res.data.length > 0){
                        $('#detailsTable').DataTable().rows.add(res.data);
                        $('#detailsTable').DataTable().columns.adjust().draw();
                    }
                },
            });
        }
        //----------------------------------------------------//

    </script>
@endsection
