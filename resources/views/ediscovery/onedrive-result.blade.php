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
        <div id="exportOnedriveFoldersModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req">Export Selected Onedrive Folders To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportOnedriveFoldersForm" class="mb-0" onsubmit="exportOnedriveFolders(event)">
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
                                    <h5 class="txt-blue mt-0">Onedrive Folders</h5>
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
                                                        <th>Folder</th>
                                                        <th>Onedrive</th>
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

        <div id="exportOnedriveDocumentsModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req">Export Onedrive Documents To .Zip
                                </h4>
                            </div>
                        </div>
                        <form id="exportOnedriveDocumentsForm" class="mb-0" onsubmit="exportOnedriveDocuments(event)">
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
                                    <h5 class="txt-blue mt-0">Onedrive Documents</h5>
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
                                                        <th>Folder</th>
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

        <div id="restoreItem" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p modal-title">Restore Selected Items</h4>
                            </div>
                        </div>
                        <form id="restoreDocumentForm" class="mb-0" onsubmit="restoreItem(event)">
                            <div class="custom-left-col">
                                <input type="hidden" class="restoreType" name="restoreType" />
                                <input type="hidden" class="restoreAction" name="restoreAction" />
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
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-1 pt-1">
                                            <div class="flex pt-2 pb-2">
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
                                            <div class="hide restoreAnother_cont">
                                                <div class="flex pb-2 ">
                                                    <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                                </div>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="overwrite"
                                                                checked="">Overwrite
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="merge">Merge
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-255p">
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
                                                            <th>Folder</th>
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
                                <div class="row hide restoreAnother_cont">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div>
                                                <div class="mb-10">Specify Onedrive to Restore to:</div>
                                                <div class="mb-10 allWidth">
                                                    <select style="width:100%;" required
                                                        class="js-data-example-ajax users required">
                                                        <option value="">Select User</option>
                                                    </select>
                                                </div>
                                                <div class="mb-10 allWidth relative">
                                                    <div class="onedrive-spinner hide"></div>
                                                    <select
                                                        class="allWidth form-control form_input user_onedrive required custom-form-control"
                                                        required id="onedrive" name="onedrive">
                                                    </select>
                                                </div>
                                                <div class="allWidth">
                                                    <input type="text" required
                                                        class="required form-control form_input custom-form-control"
                                                        id="folder" placeholder="Folder" name="folder" required
                                                        autocomplete="off" />
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

        <div id="restoreItemCopy" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p modal-title">Restore Selected Items</h4>
                            </div>
                        </div>
                        <form id="restoreDocumentCopyForm" class="mb-0" onsubmit="restoreItemCopy(event)">
                            <div class="custom-left-col">
                                <input type="hidden" class="restoreType" name="restoreType" />
                                <input type="hidden" class="restoreAction" name="restoreAction" />
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
                                        <h5 class="txt-blue mt-0">Onedrive Documents</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-150p">
                                            <div class="allWidth">
                                                <table id="docsCopyResultsTable"
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
                                                            <th>Folder</th>
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

                            </div>

                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="flex pb-2">
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
                                            <div class="hide restoreAnother_cont">
                                                <div class="flex pb-2 ">
                                                    <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                                </div>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="overwrite"
                                                                checked="">Overwrite
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="merge">Merge
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row restoreAnother_cont h-235p">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div>
                                                <div class="mb-10">Specify Onedrive to Restore to:</div>
                                                <div class="mb-10 allWidth">
                                                    <select style="width:100%;" required
                                                        class="js-data-example-ajax users required">
                                                        <option value="">Select User</option>
                                                    </select>
                                                </div>
                                                <div class="mb-10 allWidth relative">
                                                    <div class="onedrive-spinner hide"></div>
                                                    <select
                                                        class="allWidth form-control form_input user_onedrive required custom-form-control font-size"
                                                        required id="onedrive" name="onedrive">
                                                    </select>
                                                </div>
                                                <div class="allWidth">
                                                    <input type="text" required
                                                        class="required form-control form_input custom-form-control font-size"
                                                        id="folder" placeholder="Folder" name="folder" required
                                                        autocomplete="off" />
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

        <div id="restoreFolder" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width modal-mt-10 mt-10v">
                <div class="divBorderRight"></div>
                <div class="divBorderBottom"></div>
                <div class="divBorderleft"></div>
                <div class="divBorderUp"></div>
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modalContent">
                        <div class="row">&nbsp;</div>
                        <div class="row">&nbsp;</div>
                        <div class="row ml-100 mb-15">
                            <div class="input-form-70">
                                <h4 class="per-req ml-2p modal-title">Restore Selected Folders</h4>
                            </div>
                        </div>
                        <form id="restoreFolderForm" class="mb-0" onsubmit="restoreFolder(event)">
                            <div class="custom-left-col">
                                <input type="hidden" class="restoreAction" name="restoreAction" value="" />
                                <input type="hidden" class="restoreType" name="restoreType" />
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
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pt-1 pb-1">
                                            <div class="flex pt-2 pb-2">
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
                                            <div class="hide restoreAnother_cont">
                                                <div class="flex pb-2">
                                                    <label class="mr-4 m-0 nowrap">Documents Last Version Action:</label>
                                                </div>
                                                <div class="radioDiv pb-10">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="overwrite"
                                                                checked="">Overwrite
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="restoreVersionAction"
                                                                class="restoreVersionAction" value="merge">Merge
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hide restoreAnother_cont">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="relative allWidth mb-2">
                                                            <label class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                <input name="restorePermissions" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="checkbox-span-class check-mark"></span>
                                                            </label>
                                                            <span class="ml-25">Restore Permissions</span>
                                                        </div>
                                                    </div>
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

                                                    <div class="row">
                                                        <div class="pr-0 pl-3">
                                                            <div class="relative allWidth mt-2 ml-20p">
                                                                <label>Restore the following items:</label>
                                                            </div>
                                                        </div>
                                                        <div class="w-100"></div>
                                                        <div class="col">
                                                            <div class="relative allWidth mb-2 ml-16">
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="changedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
                                                                </label>
                                                                <span class="ml-25">Changed Items</span>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="relative allWidth mb-2">
                                                                <label
                                                                    class="checkbox-padding-left0 checkbox-container">&nbsp;
                                                                    <input name="deletedItems" type="checkbox"
                                                                        class="form-check-input">
                                                                    <span class="checkbox-span-class check-mark"></span>
                                                                </label>
                                                                <span class="ml-25">Deleted Items</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="custom-right-col">
                                <div class="row">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Onedrive Folders</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder modal-h-190p h-255p">
                                            <div class="allWidth">
                                                <table id="foldersResultsTable"
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
                                                            <th>Folder</th>
                                                            <th>Onedrive</th>
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
                                <div class="row hide restoreAnother_cont">
                                    <div class="input-form-70 mb-1">
                                        <h5 class="txt-blue mt-0">Target Onedrive</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div>
                                                <div class="mb-10">Specify Onedrive to Restore to:</div>
                                                <div class="mb-10 allWidth">
                                                    <select style="width:100%;" required
                                                        class="js-data-example-ajax users required">
                                                        <option value="">Select User</option>
                                                    </select>
                                                </div>
                                                <div class="mb-10 allWidth relative">
                                                    <div class="onedrive-spinner hide"></div>
                                                    <select
                                                        class="allWidth form-control form_input user_onedrive required custom-form-control"
                                                        required id="onedrive" name="onedrive">
                                                    </select>
                                                </div>
                                                <div class="allWidth">
                                                    <input type="text" required
                                                        class="required form-control form_input custom-form-control font-size"
                                                        id="folder" placeholder="Folder" name="folder" required
                                                        autocomplete="off" />
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

        <div class="row main-button-cont mb-40 w-100 ml-10">
            <div class="btnMain main-button flex">
                <div class="btnUpMask"></div>
                <div class="row m-0 pl-4 pr-4 allWidth">
                    <div class="col-lg-4 onedriveFoldersButton">
                        <div class="selected-action allWidth relative folders-actions-btn">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Selected Folders Actions
                                <span class="selectedFolderCount"></span>
                                <span class="fa fa-caret-down"></span></button>

                            <ul class="dropdown-menu allWidth">
                                <li>
                                    <a href="javascript:restoreOnedriveFoldersOverwriteModal(event)"
                                        class="tooltipSpan"
                                        title="Restore Selected to Original Location (Overwrite)">
                                        Restore Selected to Original Location (Overwrite)
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:restoreOnedriveFoldersKeepModal(event)" class="tooltipSpan"
                                        title="Restore Selected to Original Location (Keep)">
                                        Restore Selected to Original Location (Keep)
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:restoreOnedriveFoldersAnotherModal(event)"
                                        class="tooltipSpan" title="Copy Selected to Another Location">
                                        Copy Selected to Another Location
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:exportOnedriveFoldersModal(0)" class="tooltipSpan"
                                        title="Export Selected to .Zip">
                                        Export Selected to .Zip
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-4 onedriveDocumentsButton">
                        <div class="selected-action allWidth relative documents-actions-btn">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false" disabled="disabled">
                                Selected Items Actions
                                <span class="selectedItemCount"></span>
                                <span class="fa fa-caret-down"></span>
                            </button>
                            <ul class="dropdown-menu allWidth">
                                <li>
                                    <a href="javascript:restoreOnedriveDocumentsOverwriteModal(event)"
                                        class="tooltipSpan"
                                        title="Restore Selected to Original Location (Overwrite)">
                                        Restore Selected to Original Location (Overwrite)
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:restoreOnedriveDocumentsKeepModal(event)"
                                        class="tooltipSpan" title="Restore Selected to Original Location (Keep)">
                                        Restore Selected to Original Location (Keep)
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:restoreOnedriveDocumentsCopyModal(event)"
                                        title="Copy Selected to Another Location" class="tooltipSpan">
                                        Copy Selected to Another Location
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:exportOnedriveDocumentsModal(event)"
                                        title="Export Selected to .Zip" class="tooltipSpan">
                                        Export Selected to .Zip
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
            <div class="col-lg-8 z-index-500 mb-35n ml-15n flex place-items-center main-filter-cont">
                <select style="width: 100%!important" name="groupSelect" id="groupSelect"
                    class="form-control form_input required js-data-example-ajax select2">
                    <option value="">Select Onedrive</option>
                    @foreach ($data['search_data'] as $item)
                        <option value="{{ $item->onedriveName.($item->folderName?"_".$item->folderName:"") }}">{{ $item->onedriveName.($item->folderName?": ".$item->folderName:"") }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
        <div class="jobsTable" style="margin-left:0px;">
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
                        <th class="text-left">Onedrive</th>
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
                                ((data.isFolder) ?
                                    '<img class= "tableIcone w-13 mr-0 ml-4 mt-1" src="/svg/folders/none\.svg " title="Folder">' :
                                    '' +
                                    '<img class= "tableIcone w-13 mr-0 ml-4" style="margin-top: 3px;" src="/svg/folders/tasks\.svg " title="File">'
                                )+
                                '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                '<input type="hidden" class="onedriveId" value="' + data.onedrive +
                                '">' +
                                '<input type="hidden" class="onedriveTitle" value="' + data
                                .onedriveTitle + '">' +
                                '<input type="hidden" class="folderId" value="' + data.folder +
                                '">' +
                                '<input type="hidden" class="folderTitle" value="' +
                                (data.folderTitle?data.folderTitle:data.onedriveTitle) + '">' +
                                '<input type="checkbox" isFolder="'+data.isFolder+'" class="singleItemCheck form-check-input" value="' +
                                data.id + '"/>' +
                                '<span class="tree-checkBox check-mark-white check-mark"></span></label>';

                        }
                    },
                    {
                        "class": "text-left",
                        "data": null,
                        "render": function(data){
                            if(data.folderTitle)
                                return data.onedriveTitle+": "+data.folderTitle;
                            return data.onedriveTitle;
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
                            return '<img class= "tableIcone downloadMail hand w-13 mr-0" src="/svg/download\.svg " title="Download">';
                        }
                    },{
                    "data": "isFolder"
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
                        if($('.singleItemCheck[isFolder="true"]:checked').length > 0)
                            $('.folders-actions-btn button').removeAttr("disabled");
                        else
                            $('.folders-actions-btn button').attr("disabled","disabled");

                        if($('.singleItemCheck[isFolder="false"]:checked').length > 0)
                            $('.documents-actions-btn button').removeAttr("disabled");
                        else
                            $('.documents-actions-btn button').attr("disabled","disabled");
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
                            return "Please Select Onedrive";
                        return "No Data to View";
                    },
                    search: "",
                    searchPlaceholder: "Search...",
                    loadingRecords: '&nbsp;',
                },
                "orderFixed": {
                    "pre": [8, 'desc']
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
            $('#foldersResultsTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "onedriveName"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": function(){
                    let parent = $('#foldersResultsTable').closest(".col-lg-12.customBorder");
                    if(parent.hasClass("h-190p"))
                        return "120px";
                    return "185px";
                },
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
                                '" class="form-check-input mailboxCheck" onedriveId="'+data.onedriveId+'">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "185px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });

            $('#docsCopyResultsTable').DataTable({
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
                                '" class="form-check-input mailboxCheck" onedriveId="'+data.onedriveId+'">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "80px",
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
                                '" class="form-check-input mailboxCheck" onedriveId="'+data.onedriveId+'">' +
                                '<input class="onedriveId" value="' + data.onedriveId + '">' +
                                '<input class="onedriveTitle" value="' + data.onedriveTitle + '">' +
                                '<input class="folderTitle" value="' + data.folderTitle + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "folderTitle"
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
                                '" class="form-check-input mailboxCheck" onedriveId="' + data
                                .onedriveId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "onedriveName"
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
            getUsers();
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
        function checkOnedrivesCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onOnedriveResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onOnedriveResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //-------------------------------------------------------------//



        //------- Ajax Functions
        //---------------------------------------------------//
        //---- Ajax Requests
        function downloadSingleDocument() {
            let tr = $('tr.current');
            let item = tr.find('input.mailBoxFolderItemCheck').val();
            let driveId = tr.find('.onedriveId').val();
            let folderTitle = tr.find('.folderTitle').val();
            let onedriveTitle = tr.find('.onedriveTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadOnedriveDocument') }}",
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
                    onedriveId: driveId,
                    folderTitle: folderTitle,
                    fileSize: fileSize,
                    documentId: item,
                    name: tr.find('.fileNameColumn').html(),
                    onedriveTitle: onedriveTitle,
                },
                success: function(res) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    if (res.message)
                        showSuccessMessage(res.message);
                    if (res.file)
                        window.open(res.file);
                },
                statusCode: {
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
        function exportOnedriveFolders() {
            event.preventDefault()
            let folders = $('#exportOnedriveFoldersForm .mailboxCheck:checked');
            let onedrivesFolders = [];
            let index;
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('onedriveId');
                index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                if (index == -1) {
                    onedrivesFolders.push({
                        "id": folderParentId,
                        "name": tr.find('td:nth-child(3)').html()
                    });
                    index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                    onedrivesFolders[index].folders = [];
                }
                onedrivesFolders[index].folders.push({
                    id: $(this).val(),
                    folder: tr.find('td:nth-child(2)').html()
                });
            });
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportOnedriveFolders') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportOnedriveFoldersForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&folders=" + JSON.stringify(onedrivesFolders),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportOnedriveFoldersModal').modal('hide');
                },
                statusCode: {
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
        function exportOnedriveDocuments() {
            event.preventDefault()
            //-----------------------------------//
            let docs = $('#exportOnedriveDocumentsForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function(){
                if(docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0){
                    let onedriveItems = $('#exportOnedriveDocumentsForm .mailboxCheck:checked[onedriveId="'+$(this).attr("onedriveId")+'"]');
                    let onedriveItemsArr = [];
                    onedriveItems.each(function(){
                        let tr = $(this).closest('tr');
                        onedriveItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "folderTitle": tr.find('.folderTitle').val(),
                            "onedriveTitle": tr.find('.onedriveTitle').val(),
                        })
                    });
                    let tr = $(this).closest('tr');
                    docsArr.push({
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveTitle": tr.find('.onedriveTitle').val(),
                        "folderTitle": tr.find('.folderTitle').val(),
                        "docs": onedriveItemsArr
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
                url: "{{ url('exportOnedriveDocuments') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data +
                    "&_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&jobType=" + jobType +
                    "&restoreJobName=" + $("#exportOnedriveDocumentsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportOnedriveDocumentsModal').modal('hide');
                },
                statusCode: {
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
        function restoreFolder() {
            event.preventDefault()
            let folders = $('#restoreFolderForm .mailboxCheck:checked');
            let onedrivesFolders = [];
            let index;
            //-----------------------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                let folderParentId = $(this).attr('onedriveId');
                index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                if (index == -1) {
                    onedrivesFolders.push({
                        "id": folderParentId,
                        "name": tr.find('td:nth-child(3)').html()
                    });
                    index = onedrivesFolders.findIndex(x => x.id === folderParentId);
                    onedrivesFolders[index].folders = [];
                }
                onedrivesFolders[index].folders.push({
                    id: $(this).val(),
                    folder: tr.find('td:nth-child(2)').html()
                });
            });
            //-----------------------------------//
            var data = $('#restoreFolderForm').serialize();
            data += "&changedItems=" + $("#restoreFolderForm [name='changedItems']")[0].checked;
            data += "&deletedItems=" + $("#restoreFolderForm [name='deletedItems']")[0].checked;
            data += "&restorePermissions=" + $("#restoreFolderForm [name='restorePermissions']")[0].checked;
            data += "&sendSharedLinksNotification=" + $("#restoreFolderForm [name='sendSharedLinksNotification']")[0]
                .checked;
            let toOnedrive = {
                'id': $('#restoreFolderForm #onedrive').val(),
                'name': $('#restoreFolderForm .users option:selected').html(),
                'url': $('#restoreFolderForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveFolder') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&jobType=" + jobType +
                    "&restoreJobName=" + $("#restoreFolderForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&folders=" + JSON.stringify(onedrivesFolders),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreFolder').modal('hide');
                },
                statusCode: {
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
        function restoreItem() {
            event.preventDefault()
            //-----------------------------------//
            let docs = $('#restoreDocumentForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function(){
                if(docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0){
                    let onedriveItems = $('#restoreDocumentForm .mailboxCheck:checked[onedriveId="'+$(this).attr("onedriveId")+'"]');
                    let onedriveItemsArr = [];
                    onedriveItems.each(function(){
                        let tr = $(this).closest('tr');
                        onedriveItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "folderTitle": tr.find('.folderTitle').val(),
                            "onedriveTitle": tr.find('.onedriveTitle').val(),
                        })
                    });
                    let tr = $(this).closest('tr');
                    docsArr.push({
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveTitle": tr.find('.onedriveTitle').val(),
                        "folderTitle": tr.find('.folderTitle').val(),
                        "docs": onedriveItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreDocumentForm').serialize();
            let toOnedrive = {
                'id': $('#restoreDocumentForm #onedrive').val(),
                'name': $('#restoreDocumentForm .users option:selected').html(),
                'url': $('#restoreDocumentForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&jobType=" + jobType +
                    "&restoreJobName=" + $("#restoreDocumentForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreItem').modal('hide');
                },
                statusCode: {
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
        function restoreItemCopy() {
            event.preventDefault()
            //-----------------------------------//
            let docs = $('#restoreDocumentCopyForm .mailboxCheck:checked');
            let docsArr = [];
            docs.each(function(){
                if(docsArr.filter(e => e.onedriveId === $(this).attr("onedriveId")).length == 0){
                    let onedriveItems = $('#restoreDocumentCopyForm .mailboxCheck:checked[onedriveId="'+$(this).attr("onedriveId")+'"]');
                    let onedriveItemsArr = [];
                    onedriveItems.each(function(){
                        let tr = $(this).closest('tr');
                        onedriveItemsArr.push({
                            "id": $(this).val(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "folderTitle": tr.find('.folderTitle').val(),
                            "onedriveTitle": tr.find('.onedriveTitle').val(),
                        })
                    });
                    let tr = $(this).closest('tr');
                    docsArr.push({
                        "onedriveId": tr.find('.onedriveId').val(),
                        "onedriveTitle": tr.find('.onedriveTitle').val(),
                        "folderTitle": tr.find('.folderTitle').val(),
                        "docs": onedriveItemsArr
                    });
                }
            });
            //-----------------------------------//
            var data = $('#restoreDocumentCopyForm').serialize();
            let toOnedrive = {
                'id': $('#restoreDocumentCopyForm #onedrive').val(),
                'name': $('#restoreDocumentCopyForm .users option:selected').html(),
                'url': $('#restoreDocumentCopyForm #onedrive option:first').html()
            };
            data += "&onedrive=" + JSON.stringify(toOnedrive);
            data += "&docs=" + JSON.stringify(docsArr);
            //-----------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreOnedriveDocs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&jobType=" + jobType +
                    "&restoreJobName=" + $("#restoreDocumentCopyForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions,
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreItemCopy').modal('hide');
                },
                statusCode: {
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
        function restoreOnedriveDocumentsOverwriteModal() {
            $('#restoreDocumentForm').find('.restoreAction').val('overwrite');
            $('#restoreDocumentForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreDocumentForm .restoreAnother_cont').addClass('hide');
            $('#restoreDocumentForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            $('#restoreItem').find(".modal-title").html("Restore Selected Documents to Original Location (Overwrite)");
            //--------------------//
            restoreOnedriveDocumentsModal();
        }
        //---------------------------------------------------//
        function restoreOnedriveDocumentsKeepModal() {
            $('#restoreDocumentForm').find('.restoreAction').val('keep');
            $('#restoreDocumentForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreDocumentForm .restoreAnother_cont').addClass('hide');
            $('#restoreDocumentForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            $('#restoreItem').find(".modal-title").html("Restore Selected Documents to Original Location (Keep)");
            //--------------------//
            restoreOnedriveDocumentsModal();
        }
        //---------------------------------------------------//
        function restoreOnedriveDocumentsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[isfolder="false"]:checked');
            let data = [];
            let driveId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                onedriveTitle = tr.find('.onedriveTitle').val();
                driveId = tr.find('.onedriveId').val();
                folderTitle = tr.find('.folderTitle').val();
                data.push({
                    "id": $(this).val(),
                    "onedriveId": driveId,
                    "onedriveTitle": onedriveTitle,
                    "folderTitle": (folderTitle=="undefined" ? onedriveTitle : folderTitle),
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = onedriveTitle + '-' + folderTitle;
            //--------------------//
            $('#docsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsResultsTable').DataTable().clear().draw();
            $('#docsResultsTable').DataTable().rows.add(data); // Add new data
            $('#docsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsResultsTable');
            adjustTable();
            $("#docsResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreItem').find(".refreshDeviceCode").click();
            $('#restoreItem').modal('show');
        }
        //---------------------------------------------------//
        function restoreOnedriveDocumentsCopyModal() {
            //--------------------//
            $('#restoreDocumentCopyForm').find('.restoreAction').val('');
            $('#restoreDocumentCopyForm').find('.restoreType').val('another');
            //--------------------//
            $('#restoreDocumentCopyForm .restoreAnother_cont').removeClass('hide');
            $('#restoreDocumentCopyForm .restoreAnother_cont .required').attr('required', 'required');
            //--------------------//
            $('#restoreItemCopy').find(".modal-title").html("Copy Selected Documents to Another Location");
            //--------------------//
            restoreOnedriveDocumentsCopy();
        }
        //---------------------------------------------------//
        function restoreOnedriveDocumentsCopy() {
            //--------------------//
            let items = $('input.singleItemCheck[isfolder="false"]:checked');
            let data = [];
            let driveId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                onedriveTitle = tr.find('.onedriveTitle').val();
                driveId = tr.find('.onedriveId').val();
                folderTitle = tr.find('.folderTitle').val();
                data.push({
                    "id": $(this).val(),
                    "onedriveId": driveId,
                    "onedriveTitle": onedriveTitle,
                    "folderTitle": (folderTitle=="undefined" ? onedriveTitle : folderTitle),
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = onedriveTitle + '-' + folderTitle;
            //--------------------//
            $('#docsCopyResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#docsCopyResultsTable').DataTable().clear().draw();
            $('#docsCopyResultsTable').DataTable().rows.add(data); // Add new data
            $('#docsCopyResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('docsCopyResultsTable');
            adjustTable();
            $("#docsCopyResultsTable").DataTable().draw();
            //--------------------//
            $('#restoreItemCopy').find(".refreshDeviceCode").click();
            $('#restoreItemCopy').modal('show');
        }
        //---------------------------------------------------//
        function exportOnedriveDocumentsModal() {
            //--------------------//
            let items = $('input.singleItemCheck[isfolder="false"]:checked');
            let data = [];
            let driveId;
            let folderTitle;
            items.each(function() {
                var tr = $(this).closest('tr');
                onedriveTitle = tr.find('.onedriveTitle').val();
                driveId = tr.find('.onedriveId').val();
                folderTitle = tr.find('.folderTitle').val();
                data.push({
                    "id": $(this).val(),
                    "onedriveId": driveId,
                    "onedriveTitle": onedriveTitle,
                    "folderTitle": (folderTitle=="undefined" ? onedriveTitle : folderTitle),
                    "name": tr.find('.fileNameColumn').html(),
                });
            });
            let parentName = onedriveTitle + '-' + folderTitle;
            //--------------------//
            $('#exportDocsResultsTable_wrapper').find('.boxesCount').html(items.length);
            $('#exportDocsResultsTable').DataTable().clear().draw();
            $('#exportDocsResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportDocsResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportDocsResultsTable');
            adjustTable();
            $("#exportDocsResultsTable").DataTable().draw();
            //--------------------//
            $('#exportOnedriveDocumentsModal').modal('show');
        }
        //---------------------------------------------------//
        function restoreOnedriveFoldersOverwriteModal() {
            $('#restoreFolderForm').find('.restoreAction').val('overwrite');
            $('#restoreFolderForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
            $('.modal-mt-10').addClass('mt-10v');
            $('.modal-h-190p').addClass('h-255p');
            $('.modal-h-190p').removeClass('h-190p');
            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            $('#restoreFolder').find(".modal-title").html("Restore Selected Folders to Original Location (Overwrite)");
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersKeepModal() {
            $('#restoreFolderForm').find('.restoreAction').val('keep');
            $('#restoreFolderForm').find('.restoreType').val('original');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').addClass('hide');
            $('.modal-mt-10').addClass('mt-10v');
            $('.modal-h-190p').addClass('h-255p');
            $('.modal-h-190p').removeClass('h-190p');
            $('#restoreFolderForm .restoreAnother_cont .required').removeAttr('required');
            //--------------------//
            $('#restoreFolder').find(".modal-title").html("Restore Selected Folders to Original Location (Keep)");
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersAnotherModal() {
            //--------------------//
            $('#restoreFolderForm').find('.restoreAction').val('');
            $('#restoreFolderForm').find('.restoreType').val('another');
            //--------------------//
            $('#restoreFolderForm .restoreAnother_cont').removeClass('hide');
            $('.modal-mt-10').removeClass('mt-10v');
            $('.modal-h-190p').removeClass('h-255p');
            $('.modal-h-190p').addClass('h-190p');
            $('#restoreFolderForm .restoreAnother_cont .required').attr('required', 'required');
            //--------------------//
            $('#restoreFolder').find(".modal-title").html("Copy Selected Folders to Another Location");
            //--------------------//
            restoreOnedriveFoldersModal();
        }
        //----------------------------------------------------//
        function restoreOnedriveFoldersModal() {
            //--------------------//
            let folders = $('input.singleItemCheck:checked[isfolder="true"]');
            let foldersCount = folders.length;
            let data = [];
            //--------------------//
            folders.each(function() {
                var folderCheck = $(this);
                let tr = folderCheck.closest('tr');
                data.push({
                    "id": $(this).val(),
                    "name": tr.find('.fileNameColumn').html(),
                    "onedriveId": tr.find('.onedriveId').val(),
                    "onedriveName": tr.find('.onedriveTitle').val()
                });
            });
            //--------------------//
            $('#foldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#foldersResultsTable').DataTable().clear().draw();
            $('#foldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#foldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('foldersResultsTable');
            adjustTable();
            $("#foldersResultsTable").DataTable().draw();
            $('#restoreFolder').find(".refreshDeviceCode").click();
            $('#restoreFolder').modal('show');
        }
        //---------------------------------------------------//
        function exportOnedriveFoldersModal() {
            //--------------------//
            let folders = $('input.singleItemCheck:checked[isfolder="true"]');
            let foldersCount = folders.length;
            let data = [];
            //--------------------//
            folders.each(function() {
                let tr = $(this).closest('tr');
                data.push({
                    "id": $(this).val(),
                    "name": tr.find('.folderTitle').val(),
                    "onedriveId": tr.find('.onedriveId').val(),
                    "onedriveName": tr.find('.onedriveTitle').val()
                });
            });
            //--------------------------------------------------//
            $('#exportFoldersResultsTable_wrapper').find('.boxesCount').html(foldersCount);
            $('#exportFoldersResultsTable').DataTable().clear().draw();
            $('#exportFoldersResultsTable').DataTable().rows.add(data); // Add new data
            $('#exportFoldersResultsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFoldersResultsTable');
            adjustTable();
            $("#exportFoldersResultsTable").DataTable().draw();
            $('#exportOnedriveFoldersModal').modal('show');
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
        function getUsers() {
            $.ajax({
                type: "GET",
                url: "{{ url('getOnedriveUsers') }}",
                data: {},
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                success: function(data) {
                    let users = data;
                    $('.users').html("");
                    $('.user_onedrive').html("");
                    if (users.length > 0) {
                        let result = [];
                        //--------------------//
                        result.push({
                            id: '',
                            text: 'Select User',
                        });
                        users.forEach((e) => {
                            result.push({
                                id: e.id,
                                name: e.displayName,
                                text: e.displayName,
                            });
                        });
                        //--------------------//
                        $(".users").select2({
                            data: result,
                        });
                        //--------------------//
                        $('.users').change(function() {
                            $('.onedrive-spinner').removeClass('hide');
                            if ($(this).val())
                                getUserOnedrives($(this).val());
                        });
                        //--------------------//
                    }
                },
                statusCode: {
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
                    $("#jobs").val($("#target option:first").val());
                    $(".backupDate").val("");
                    $(".backupTime").html("");
                    $(".backupTime").append(new Option("Select Time", ""));
                    $('.backupTime').attr('disabled', 'disabled');
                    $('.backupDate').attr('disabled', 'disabled');

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
        }
        //----------------------------------------------------//
        function getUserOnedrives(value) {
            $('.user_onedrive').html("");
            $.ajax({
                type: "POST",
                url: "{{ url('getUserOnedrives') }}",
                data: "_token={{ csrf_token() }}&" +
                    "userId=" + value,
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                success: function(data) {
                    if (data.length > 0) {
                        $('.user_onedrive').html("");
                        data.forEach((e) => {
                            $('.user_onedrive').append(new Option(e.url, e.id));
                        });
                    }
                    $('.onedrive-spinner').addClass('hide');
                },
                statusCode: {
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
                    $('.onedrive-spinner').addClass('hide');
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
        }
        //----------------------------------------------------//

    </script>
@endsection
