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
        <div id="restoreFilesModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-10v">
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
                                <h4 class="per-req ml-2p">Restore Selected Files
                                </h4>
                            </div>
                        </div>
                        <form id="restoreFilesForm" class="mb-0" onsubmit="restoreFiles(event)">
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
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="flex">
                                                <label class="mr-4 m-0 nowrap">Files Version:</label>
                                                <div class="radioDiv">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="fileVersion" class="fileVersion"
                                                                value="Last" checked="">Last
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="fileVersion" class="fileVersion"
                                                                value="All">All
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex pt-1">
                                                <label class="mr-4 m-0 nowrap">File Last Version Action:</label>
                                                <div class="radioDiv pb-1">
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="fileLastVersionAction"
                                                                class="fileLastVersionAction" value="Overwrite"
                                                                checked="">Overwrite
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="radio m-0">
                                                        <label>
                                                            <input type="radio" name="fileLastVersionAction"
                                                                class="fileLastVersionAction" value="Merge">Merge
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="pr-0 pl-4">
                                                        <label>Restore the following items:</label>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreChangedItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Changed Items</span>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="relative allWidth">
                                                            <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                                <input name="restoreMissingItems" type="checkbox"
                                                                    class="form-check-input">
                                                                <span class="check-mark checkbox-span-class"></span>
                                                            </label>
                                                            <span class="ml-25">Missing Items</span>
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
                                        <h5 class="txt-blue mt-0">Selected Files</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-328p">
                                            <div class="allWidth">
                                                <table id="filesTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span class="tree-checkBox check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>File</th>
                                                            <th>Channel</th>
                                                            <th>Team</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> files selected <span
                                                                    class="unresolvedCount"></span>
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
        <div id="exportFilesModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p">Export Selected Files
                                </h4>
                            </div>
                        </div>
                        <form id="exportFilesForm" class="mb-0" onsubmit="exportFiles(event)">
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
                                    <h5 class="txt-blue mt-0">Selected Files</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth">
                                            <table id="exportFilesTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>File</th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> files selected <span
                                                                class="unresolvedCount"></span>
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
        <div id="exportPostsModal" class="modal modal-center" role="dialog">
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
                                <h4 class="per-req ml-2p">Export Selected Posts
                                </h4>
                            </div>
                        </div>
                        <form id="exportPostsForm" class="mb-0" onsubmit="exportPosts(event)">
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
                                    <h5 class="txt-blue mt-0">Selected Posts</h5>
                                </div>
                                <div class="input-form-70">
                                    <div class="col-lg-12 customBorder">
                                        <div class="allWidth">
                                            <table id="exportPostsTable"
                                                class="stripe table table-striped table-dark display nowrap allWidth">
                                                <thead class="table-th">
                                                    <tr>
                                                        <th>
                                                            <label
                                                                class="checkbox-container checkbox-search checkbox-top-left">
                                                                <input type="checkbox" checked class="form-check-input">
                                                                <span class="tree-checkBox check-mark"></span>
                                                            </label>
                                                        </th>
                                                        <th>Post</th>
                                                        <th>Channel</th>
                                                        <th>Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">
                                                            <span class="boxesCount"></span> posts selected <span
                                                                class="unresolvedCount"></span>
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
        <div id="restoreTabsModal" class="modal modal-center" role="dialog">
            <div class="modal-dialog modal-lg modal-width mt-10v">
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
                                <h4 class="per-req ml-2p">Restore Selected Tabs
                                </h4>
                            </div>
                        </div>
                        <form id="restoreTabsForm" class="mb-0" onsubmit="restoreTabs(event)">
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
                                        <h5 class="txt-blue mt-0">Restore Options</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder pb-3 pt-3">
                                            <div class="row">
                                                <div class="pr-0 pl-3">
                                                    <label>Restore the following items:</label>
                                                </div>
                                                <div class="w-100"></div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreChangedItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Changed Items</span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="relative allWidth">
                                                        <label class="checkbox-container checkbox-padding-left0">&nbsp;
                                                            <input name="restoreMissingItems" type="checkbox"
                                                                class="form-check-input">
                                                            <span class="check-mark checkbox-span-class"></span>
                                                        </label>
                                                        <span class="ml-25">Missing Items</span>
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
                                        <h5 class="txt-blue mt-0">Selected Tabs</h5>
                                    </div>
                                    <div class="input-form-70">
                                        <div class="col-lg-12 customBorder h-215p">
                                            <div class="allWidth">
                                                <table id="tabsTable"
                                                    class="stripe table table-striped table-dark display nowrap allWidth">
                                                    <thead class="table-th">
                                                        <tr>
                                                            <th>
                                                                <label
                                                                    class="checkbox-container checkbox-search checkbox-top-left">
                                                                    <input type="checkbox" checked
                                                                        class="form-check-input">
                                                                    <span class="tree-checkBox check-mark"></span>
                                                                </label>
                                                            </th>
                                                            <th>Tab</th>
                                                            <th>Channel</th>
                                                            <th>Team</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">
                                                                <span class="boxesCount"></span> tabs selected <span
                                                                    class="unresolvedCount"></span>
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
        @php
            $type = $data['search_data'][0]->type;
        @endphp
        <div class="row main-button-cont mb-40">
            <div class="btnMain main-button flex">
                <div class="btnUpMask"></div>
                <div class="row m-0 pl-4 pr-4 allWidth">
                    <div class="col-lg-4 tabsButton">
                        <div class="selected-action allWidth relative">
                            <button type="button" class="btn-sm dropdown-toggle form_dropDown form-control"
                                data-toggle="dropdown" aria-expanded="false">
                                Selected {{ $type == 'posts' ? 'Posts' : ($type == 'files' ? 'Files' : ($type == 'tabs' ? 'Tabs' : '')) }}
                                Actions
                                <span class="selectedItemCount"></span>
                                <span class="fa fa-caret-down"></span></button>

                            <ul class="dropdown-menu allWidth"
                                style="top:{{ $type != 'files' ? '-37px' : '-61px' }}!important">
                                <li class="filesLi {{ $type != 'files' ? 'hide' : '' }}">
                                    <a href="javascript:restoreFilesModal(event)" class="tooltipSpan"
                                        title="Restore Selected Files">
                                        Restore Files
                                    </a>
                                </li>
                                <li class="tabsLi {{ $type != 'tabs' ? 'hide' : '' }}">
                                    <a href="javascript:restoreTabsModal(event)" class="tooltipSpan"
                                        title="Restore Selected Tabs">
                                        Restore Tabs
                                    </a>
                                </li>
                                <li class="postsLi {{ $type != 'posts' ? 'hide' : '' }}">
                                    <a href="javascript:exportPostsModal(event)" class="tooltipSpan"
                                        title="Export Selected Posts to .Zip">
                                        Export Posts to .Zip
                                    </a>
                                </li>
                                <li class="filesLi {{ $type != 'files' ? 'hide' : '' }}">
                                    <a href="javascript:exportFilesModal(event)" class="tooltipSpan"
                                        title="Export Selected Files to .Zip">
                                        Export Files to .Zip
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
                    <option value="">Select Team</option>
                    @foreach ($data['search_data'] as $item)
                        <option value="{{ $item->teamName . ($item->channelName ? '_' . $item->channelName : '') }}">
                            {{ $item->teamName . ($item->channelName ? ': ' . $item->channelName : '') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @php
            $type = $data['search_data'][0]->type;
        @endphp
        <div class="jobsTable {{ $type == 'posts' ? '' : 'hide' }}" style="margin-left:0px; margin-top: -38px;">
            <table id="postsDetailsTable"
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
                        <th class="text-left">Team</th>
                        <th>Author</th>
                        <th>Subject</th>
                        <th>Creation Time</th>
                        <th>Last Modification Time</th>
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
        <div class="jobsTable {{ $type == 'files' ? '' : 'hide' }}" style="margin-left:0px; margin-top: -38px;">
            <table id="filesDetailsTable"
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
                        <th class="text-left">Team</th>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Version</th>
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
        <div class="jobsTable {{ $type == 'tabs' ? '' : 'hide' }}" style="margin-left:0px; margin-top: -38px;">
            <table id="tabsDetailsTable"
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
                        <th class="text-left">Team</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Url</th>
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
                    class="cancel-button btn_primary_state mr-0 min-width-150 justify-content-center">
                    Cancel</a>
                <div class="allWidth relative mr-5 actions-btn">
                    <button type="button" class="dropdown-toggle btn_primary_state ml-auto" data-toggle="dropdown"
                        aria-expanded="false" disabled="disabled">
                        Selected {{ $type == 'posts' ? 'Posts' : ($type == 'files' ? 'Files' : ($type == 'tabs' ? 'Tabs' : '')) }} Actions
                        <span class="selectedItemCount"></span>
                        <span class="fa fa-caret-down ml-2" aria-hidden="true"></span></button>
                    <ul class="dropdown-menu dropup-menu allWidth"
                        style="top:{{ $type != 'files' ? '-37px' : '-61px' }}!important">
                        <li class="filesLi {{ $type != 'files' ? 'hide' : '' }}">
                            <a href="javascript:restoreFilesModal(event)" class="tooltipSpan"
                                title="Restore Selected Files">
                                Restore Files
                            </a>
                        </li>
                        <li class="tabsLi {{ $type != 'tabs' ? 'hide' : '' }}">
                            <a href="javascript:restoreTabsModal(event)" class="tooltipSpan"
                                title="Restore Selected Tabs">
                                Restore Tabs
                            </a>
                        </li>
                        <li class="postsLi {{ $type != 'posts' ? 'hide' : '' }}">
                            <a href="javascript:exportPostsModal(event)" class="tooltipSpan"
                                title="Export Selected Posts to .Zip">
                                Export Posts to .Zip
                            </a>
                        </li>
                        <li class="filesLi {{ $type != 'files' ? 'hide' : '' }}">
                            <a href="javascript:exportFilesModal(event)" class="tooltipSpan"
                                title="Export Selected Files to .Zip">
                                Export Files to .Zip
                            </a>
                        </li>
                    </ul>
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
            if ("{{ $type }}" == "posts") {
                let postsDetailsTable = $('#postsDetailsTable').DataTable({
                    'ajax': {
                        "type": "GET",
                        "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
                        "dataSrc": function(json) {
                            if (json.length == 0)
                                return [];
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
                            d.tableType = "posts";
                            d.searchType = "{{ $type }}";
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
                            $('#postsDetailsTable > tbody').html(
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
                                return '' +
                                    '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                    '<input type="hidden" class="teamId" value="' + data.team +
                                    '">' +
                                    '<input type="hidden" class="teamTitle" value="' + data
                                    .teamTitle + '">' +
                                    '<input type="hidden" class="channelId" value="' + data
                                    .channel +
                                    '">' +
                                    '<input type="hidden" class="channelTitle" value="' + data
                                    .channelTitle + '">' +
                                    '<input type="checkbox" data-type="posts" class="singleItemCheck form-check-input" value="' +
                                    data.id + '"/>' +
                                    '<span class="tree-checkBox check-mark-white check-mark"></span></label>';

                            }
                        },
                        {
                            "class": "text-left",
                            "data": null,
                            "render": function(data) {
                                if (data.channelTitle)
                                    return data.teamTitle + ": " + data.channelTitle;
                                return data.teamTitle;
                            }
                        },
                        {
                            "data": "author",
                            "class": "author"
                        },
                        {
                            "data": "subject",
                            "class": "wrap"
                        },
                        {
                            "data": null,
                            "render": function(data) {
                                return formatDate(data.createdTime);
                            }
                        },
                        {
                            "data": null,
                            "render": function(data) {
                                return formatDate(data.lastModifiedTime);
                            }
                        },
                        {
                            "data": null,
                            "class": "after-none",
                            "width": "5%",
                            render: function(data, type, full, meta) {
                                return '<img class= "hand tableIcone downloadPost w-13 mr-0" src="/svg/download\.svg " title="Download">';
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
                        if ($("#postsDetailsTable_filter label").find('.search-icon').length == 0)
                            $('#postsDetailsTable_filter label').append(icon);
                        $('#postsDetailsTable_filter input').addClass('form_input form-control');
                        //-----------------------------------------//
                        if (nextPartition && $('#postsDetailsTable').DataTable().data().count() > 0) {
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
                        if (rowsArr.indexOf(currentRow) >= 0 && $('#postsDetailsTable').DataTable()
                            .data().count() > 0) {
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
                        $('#postsDetailsTable_wrapper').find('.showingCount').html($(
                            '#postsDetailsTable').DataTable().data().count());
                        //-----------------------------------------//
                        $('#postsDetailsTable_wrapper').find('.boxesCount').html(totalCount);
                        //-----------------------------------------//
                        $('.singleItemCheck').change(function() {
                            if ($('.singleItemCheck:checked').length > 0) {
                                $('.actions-btn button').removeAttr("disabled");
                            } else {
                                $('.actions-btn button').attr("disabled", "disabled");
                            }
                            $('.selectedCount').html($('.singleItemCheck:checked').length);
                        }).change();
                        //-----------------------------------------//
                        $('.tableIcone.downloadPost').unbind('click').click(function() {
                            var tr = $(this).closest('tr');
                            $('tr.current').removeClass('current');
                            tr.addClass('current');
                            downloadSinglePost();
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
                                return "Please Select Team";
                            return "No Data to View";
                        },
                        search: "",
                        searchPlaceholder: "Search...",
                        loadingRecords: '&nbsp;',
                    },
                    'columnDefs': [{
                        'targets': [0, 6], // column index (start from 0)
                        'orderable': false, // set orderable false for selected columns
                    }],
                    "order": [
                        [1, 'asc']
                    ],
                });
                $('#postsDetailsTable').DataTable().buttons().container()
                    .prependTo('#postsDetailsTable_filter');
                // $('#postsDetailsTable_filter').addClass('hide');
            }
            //---------------------------------------//

            //---------------------------------------//
            if ("{{ $type }}" == "files") {
                let filesDetailsTable = $('#filesDetailsTable').DataTable({
                    'ajax': {
                        "type": "GET",
                        "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
                        "dataSrc": function(json) {
                            if (json.length == 0)
                                return [];
                            nextPartition = json['nextPartition'];
                            nextRow = json['nextRow'];
                            totalCount = json['totalCount'];
                            pageItemsCount = json['pageItemsCount'];
                            let data = json['data'];
                            data.forEach(function(e) {
                                e.isFolder = false;
                                if (e.sizeBytes == 0 && e.name.indexOf(".") == -1) {
                                    e.isFolder = true;
                                }
                            });
                            return data;
                        },
                        "data": function(d) {
                            d.filter = getFilterPartition();
                            d.nextPartition = getNextPartition();
                            d.nextRow = getNextRow();
                            d.tableType = "files";
                            d.searchType = "{{ $type }}";
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
                            $('#filesDetailsTable > tbody').html(
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
                                return '' +
                                    ((data.isFolder) ?
                                        '<img class= "tableIcone w-13 mr-0 ml-4 t-2" src="/svg/folders/none\.svg " title="Folder">' :
                                        '' +
                                        '<img class= "tableIcone w-13 mr-0 ml-4 t-2" src="/svg/folders/tasks\.svg " title="File">'
                                    ) +
                                    '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                    '<input type="hidden" class="teamId" value="' + data.team +
                                    '">' +
                                    '<input type="hidden" class="teamTitle" value="' + data
                                    .teamTitle + '">' +
                                    '<input type="hidden" class="channelId" value="' + data
                                    .channel +
                                    '">' +
                                    '<input type="hidden" class="channelTitle" value="' + data
                                    .channelTitle + '">' +
                                    '<input type="checkbox" data-type="files" class="singleItemCheck form-check-input" value="' +
                                    data.id + '"/>' +
                                    '<span class="tree-checkBox check-mark-white check-mark"></span></label>';

                            }
                        },
                        {
                            "class": "text-left",
                            "data": null,
                            "render": function(data) {
                                if (data.channelTitle)
                                    return data.teamTitle + ": " + data.channelTitle;
                                return data.teamTitle;
                            }
                        },
                        {
                            "data": "name",
                            "class": "nameColumn wrap"
                        },
                        {
                            "data": null,
                            "class": "fileSizeColumn",
                            "render": function(data) {
                                if (data.sizeBytes > 0) {
                                    return (data.sizeBytes / 1024 / 1024).toFixed(2) + ' MB';
                                }
                                return data.sizeBytes;
                            }
                        },
                        {
                            "data": "version",
                        },
                        {
                            "data": null,
                            "render": function(data) {
                                return formatDate(data.modified);
                            }
                        },
                        {
                            "data": "modifiedBy",
                        },
                        {
                            "data": null,
                            "class": "after-none",
                            "width": "5%",
                            render: function(data, type, full, meta) {
                                return '<img class= "hand tableIcone downloadFile w-13 mr-0" src="/svg/download\.svg " title="Download">';
                            }
                        }, {
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
                        if ($("#filesDetailsTable_filter label").find('.search-icon').length == 0)
                            $('#filesDetailsTable_filter label').append(icon);
                        $('#filesDetailsTable_filter input').addClass('form_input form-control');
                        //-----------------------------------------//
                        if (nextPartition && $('#filesDetailsTable').DataTable().data().count() > 0) {
                            if (rowsArr.indexOf(nextRow) == -1) {
                                rowsArr.push(nextRow);
                                partitionsArr.push(nextPartition);
                            }
                            $('.nextPage').removeAttr("disabled");
                        } else {
                            $('.nextPage').attr("disabled", "disabled");
                        }
                        //-----------------------------------------//
                        if (rowsArr.indexOf(currentRow) >= 0 && $('#filesDetailsTable').DataTable()
                            .data().count() > 0) {
                            $('.previousPage').removeAttr("disabled");
                        } else {
                            $('.previousPage').attr("disabled", "disabled");
                        }
                        //-----------------------------------------//
                        $(".currentPage").html(rowsArr.indexOf(currentRow) + 2);
                        $(".totalPages").html(Math.ceil(totalCount / pageItemsCount));
                        //-----------------------------------------//
                        $('#filesDetailsTable_wrapper').find('.showingCount').html($(
                            '#filesDetailsTable').DataTable().data().count());
                        //-----------------------------------------//
                        $('#filesDetailsTable_wrapper').find('.boxesCount').html(totalCount);
                        //-----------------------------------------//
                        $('.singleItemCheck').change(function() {
                            if ($('.singleItemCheck:checked').length > 0) {
                                $('.actions-btn button').removeAttr("disabled");
                            } else {
                                $('.actions-btn button').attr("disabled", "disabled");
                            }
                            $('.selectedCount').html($('.singleItemCheck:checked').length);
                        }).change();
                        //-----------------------------------------//
                        $('.tableIcone.downloadFile').unbind('click').click(function() {
                            var tr = $(this).closest('tr');
                            $('tr.current').removeClass('current');
                            tr.addClass('current');
                            downloadSingleFile();
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
                                return "Please Select Team";
                            return "No Data to View";
                        },
                        search: "",
                        searchPlaceholder: "Search...",
                        loadingRecords: '&nbsp;',
                    },
                    'columnDefs': [{
                        'targets': [0, 7], // column index (start from 0)
                        'orderable': false, // set orderable false for selected columns
                    }, {
                        'targets': [8], // column index (start from 0)
                        'visible': false, // set orderable false for selected columns
                    }],
                    "orderFixed": {
                        "pre": [8, 'desc']
                    },
                    "order": [
                        [1, 'asc']
                    ],
                });
                $('#filesDetailsTable').DataTable().buttons().container()
                    .prependTo('#filesDetailsTable_filter');
                // $('#filesDetailsTable_filter').addClass('hide');
            }
            //---------------------------------------//

            //---------------------------------------//
            if ("{{ $type }}" == "tabs") {
                let tabsDetailsTable = $('#tabsDetailsTable').DataTable({
                    'ajax': {
                        "type": "GET",
                        "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
                        "dataSrc": function(json) {
                            if (json.length == 0)
                                return [];
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
                            d.tableType = "tabs";
                            d.searchType = "{{ $type }}";
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
                            $('#tabsDetailsTable > tbody').html(
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
                                return '' +
                                    '<label class="custom-top-left checkbox-container checkbox-search left-17">&nbsp;' +
                                    '<input type="hidden" class="teamId" value="' + data.team +
                                    '">' +
                                    '<input type="hidden" class="teamTitle" value="' + data
                                    .teamTitle + '">' +
                                    '<input type="hidden" class="channelId" value="' + data
                                    .channel +
                                    '">' +
                                    '<input type="hidden" class="channelTitle" value="' + data
                                    .channelTitle + '">' +
                                    '<input type="checkbox" data-type="tabs" class="singleItemCheck form-check-input" value="' +
                                    data.id + '"/>' +
                                    '<span class="tree-checkBox check-mark-white check-mark"></span></label>';

                            }
                        },
                        {
                            "class": "text-left",
                            "data": null,
                            "render": function(data) {
                                if (data.channelTitle)
                                    return data.teamTitle + ": " + data.channelTitle;
                                return data.teamTitle;
                            }
                        },
                        {
                            "data": "name",
                            "class": "wrap"
                        },
                        {
                            "data": "type",
                        },
                        {
                            "data": "url",
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
                        if ($("#tabsDetailsTable_filter label").find('.search-icon').length == 0)
                            $('#tabsDetailsTable_filter label').append(icon);
                        $('#tabsDetailsTable_filter input').addClass('form_input form-control');
                        //-----------------------------------------//
                        if (nextPartition && $('#tabsDetailsTable').DataTable().data().count() > 0) {
                            if (rowsArr.indexOf(nextRow) == -1) {
                                rowsArr.push(nextRow);
                                partitionsArr.push(nextPartition);
                            }
                            $('.nextPage').removeAttr("disabled");
                        } else {
                            $('.nextPage').attr("disabled", "disabled");
                        }
                        //-----------------------------------------//
                        if (rowsArr.indexOf(currentRow) >= 0 && $('#tabsDetailsTable').DataTable()
                            .data().count() > 0) {
                            $('.previousPage').removeAttr("disabled");
                        } else {
                            $('.previousPage').attr("disabled", "disabled");
                        }
                        //-----------------------------------------//
                        $(".currentPage").html(rowsArr.indexOf(currentRow) + 2);
                        $(".totalPages").html(Math.ceil(totalCount / pageItemsCount));
                        //-----------------------------------------//
                        $('#tabsDetailsTable_wrapper').find('.showingCount').html($(
                            '#tabsDetailsTable').DataTable().data().count());
                        //-----------------------------------------//
                        $('#tabsDetailsTable_wrapper').find('.boxesCount').html(totalCount);
                        //-----------------------------------------//
                        $('.singleItemCheck').change(function() {
                            if ($('.singleItemCheck:checked').length > 0) {
                                $('.actions-btn button').removeAttr("disabled");
                            } else {
                                $('.actions-btn button').attr("disabled", "disabled");
                            }
                            $('.selectedCount').html($('.singleItemCheck:checked').length);
                        }).change();
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
                                return "Please Select Team";
                            return "No Data to View";
                        },
                        search: "",
                        searchPlaceholder: "Search...",
                        loadingRecords: '&nbsp;',
                    },
                    'columnDefs': [{
                        'targets': [0], // column index (start from 0)
                        'orderable': false, // set orderable false for selected columns
                    }],
                    "order": [
                        [1, 'asc']
                    ],
                });
                $('#tabsDetailsTable').DataTable().buttons().container()
                    .prependTo('#tabsDetailsTable_filter');
                // $('#tabsDetailsTable_filter').addClass('hide');
            }
            //---------------------------------------//

            //---------------------------------------//
            $('#filesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
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
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "260px",
                "paging": false,
                "autoWidth": false,
                "processing": false,
                'columnDefs': [{
                    'targets': [0], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }]
            });
            //--------------------------------------------------//
            $('#exportFilesTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": "name",
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
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
            //--------------------------------------------------//
            $('#exportPostsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
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
            //--------------------------------------------------//
            $('#tabsTable').DataTable({
                "data": [],
                "createdRow": function(row, data, rowIndex) {
                    $.each($('td', row), function(colIndex) {
                        if (!$(this).hasClass('after-none')) {
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
                            return '<label class="checkbox-container checkbox-search checkbox-top-left">' +
                                '<input type="hidden" value="' + data.teamId +
                                '" class="form-check-input teamId">' +
                                '<input type="hidden" value="' + data.channelId +
                                '" class="form-check-input channelId">' +
                                '<input type="checkbox" checked value="' + data.id +
                                '" class="form-check-input mailboxCheck" data-teamId="' + data
                                .teamId + '" data-channelId="' + data.channelId + '">' +
                                '<span class="tree-checkBox check-mark"></span>' +
                                '</label>';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            return data.name;
                        },
                    },
                    {
                        "data": "channelName",
                    },
                    {
                        "data": "teamName",
                    }
                ],
                "searching": false,
                "info": false,
                "fnDrawCallback": function() {},
                "scrollY": "145px",
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
            $('#groupSelect').change(function() {
                filterPartition = $(this).val();
                nextPartition = null;
                nextRow = null;
                currentPartition = null;
                currentRow = null;
                partitionsArr = [];
                rowsArr = [];
                $('.allItemsCheck').prop("checked", false).change();
                if ("{{ $type }}" == "posts")
                    $('#postsDetailsTable').DataTable().ajax.reload();
                if ("{{ $type }}" == "files")
                    $('#filesDetailsTable').DataTable().ajax.reload();
                if ("{{ $type }}" == "tabs")
                    $('#tabsDetailsTable').DataTable().ajax.reload();
            });
            //---------------------------------------//
            $('.allItemsCheck').change(function() {
                $('.singleItemCheck').prop("checked", $(this).prop("checked")).change();
            })
            //---------------------------------------//

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
            let channels = $('#' + tableName + '_wrapper').find('tbody .form-check-input:checked');
            let channelsCount = channels.length;
            let unresolvedCount = 0;
            $('#' + tableName + '_wrapper').find('.boxesCount').html(channelsCount);
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
        function checkTeamsCount(tableName) {
            $('#' + tableName + '_wrapper').find('thead .form-check-input').click(function() {
                if ($(this).prop('checked'))
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', true);
                    });
                else
                    $('#' + tableName + '_wrapper').find('tbody .form-check-input').each(function() {
                        $(this).prop('checked', false);
                    });
                onTeamResultChange(tableName);
            });

            $('#' + tableName + '_wrapper').find('tbody .form-check-input').change(function() {
                onTeamResultChange(tableName);
            });
            adjustTable();
            $("#" + tableName).DataTable().draw();
        }
        //-------------------------------------------------------------//



        //------- Ajax Functions
        //---------------------------------------------------//
        //---------------------------------------------------//




        //------- Modal Functions
        //---------------------------------------------------//
        function restoreFilesModal() {
            let files = $('.singleItemCheck[data-type="files"]:checked');
            var tableData = [];
            let filesCount = files.length;
            let unresolvedCount = 0;
            files.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.nameColumn').html(),
                });
            });
            $('#filesTable_wrapper').find('.boxesCount').html(filesCount);
            $('#filesTable').DataTable().clear().draw();
            $('#filesTable').DataTable().rows.add(tableData); // Add new data
            $('#filesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('filesTable');
            adjustTable();
            $("#filesTable").DataTable().draw();
            $('#restoreFilesModal').find(".refreshDeviceCode").click();
            $('#restoreFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function exportFilesModal() {
            let files = $('.singleItemCheck[data-type="files"]:checked');
            var tableData = [];
            let filesCount = files.length;
            let unresolvedCount = 0;
            files.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.nameColumn').html(),
                });
            });
            $('#exportFilesTable_wrapper').find('.boxesCount').html(filesCount);
            $('#exportFilesTable').DataTable().clear().draw();
            $('#exportFilesTable').DataTable().rows.add(tableData); // Add new data
            $('#exportFilesTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportFilesTable');
            adjustTable();
            $("#exportFilesTable").DataTable().draw();
            $('#exportFilesModal').modal('show');
        }
        //----------------------------------------------------//
        function exportPostsModal() {
            let posts = $('.singleItemCheck[data-type="posts"]:checked');
            var tableData = [];
            let postsCount = posts.length;
            let unresolvedCount = 0;
            posts.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.author').html(),
                });
            });
            $('#exportPostsTable_wrapper').find('.boxesCount').html(postsCount);
            $('#exportPostsTable').DataTable().clear().draw();
            $('#exportPostsTable').DataTable().rows.add(tableData); // Add new data
            $('#exportPostsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('exportPostsTable');
            adjustTable();
            $("#exportPostsTable").DataTable().draw();
            $('#exportPostsModal').modal('show');
        }
        //----------------------------------------------------//
        function restoreTabsModal() {
            let tabs = $('.singleItemCheck[data-type="tab"]:checked');
            var tableData = [];
            let tabsCount = tabs.length;
            let unresolvedCount = 0;
            tabs.each(function() {
                var tr = $(this).closest('tr');
                tableData.push({
                    id: $(this).val(),
                    teamId: tr.find('.teamId').val(),
                    teamName: tr.find('.teamTitle').val(),
                    channelId: tr.find('.channelId').val(),
                    channelName: tr.find('.channelTitle').val(),
                    name: tr.find('.name').html(),
                });
            });
            $('#tabsTable_wrapper').find('.boxesCount').html(tabsCount);
            $('#tabsTable').DataTable().clear().draw();
            $('#tabsTable').DataTable().rows.add(tableData); // Add new data
            $('#tabsTable').DataTable().columns.adjust().draw(); // Redraw the DataTable

            checkTableCount('tabsTable');
            adjustTable();
            $("#tabsTable").DataTable().draw();
            $('#restoreTabsModal').find(".refreshDeviceCode").click();
            $('#restoreTabsModal').modal('show');
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
            if ("{{ $type }}" == "posts")
                $('#postsDetailsTable').DataTable().ajax.reload();
            if ("{{ $type }}" == "files")
                $('#filesDetailsTable').DataTable().ajax.reload();
            if ("{{ $type }}" == "tabs")
                $('#tabsDetailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function nextPage() {
            $('.previousPage,.nextPage').attr("disabled", "disabled");
            $('.allItemsCheck').prop("checked", false).change();
            if ("{{ $type }}" == "posts")
                $('#postsDetailsTable').DataTable().ajax.reload();
            if ("{{ $type }}" == "files")
                $('#filesDetailsTable').DataTable().ajax.reload();
            if ("{{ $type }}" == "tabs")
                $('#tabsDetailsTable').DataTable().ajax.reload();
        }
        //---------------------------------------------------//
        function filterItems() {
            $.ajax({
                "type": "GET",
                "url": "{{ url('getEdiscoveryJobResult', [$data['kind'], optional($data)['job']->id]) }}",
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
                "success": function(res) {
                    nextPartition = res.nextPartition;
                    nextRow = res.nextRow;
                    if (res.data.length > 0) {
                        $('#postsDetailsTable').DataTable().rows.add(res.data);
                        $('#postsDetailsTable').DataTable().columns.adjust().draw();
                        $('#filesDetailsTable').DataTable().rows.add(res.data);
                        $('#filesDetailsTable').DataTable().columns.adjust().draw();
                        $('#tabsDetailsTable').DataTable().rows.add(res.data);
                        $('#tabsDetailsTable').DataTable().columns.adjust().draw();
                    }
                },
            });
        }
        //----------------------------------------------------//
        function restoreFiles() {
            event.preventDefault()
            let data = $('#restoreFilesForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreFilesForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreFilesForm [name='restoreChangedItems']")[0].checked;
            //--------------------------------------------------//
            let files = $('#restoreFilesForm .mailboxCheck:checked');
            let filesArr = [];
            //-----------------------------------------//
            files.each(function() {
                let tr = $(this).closest('tr');
                if (filesArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamFiles = $('#restoreFilesForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamFilesArr = [];
                    teamFiles.each(function() {
                        tr = $(this).closest('tr');
                        teamFilesArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
                        })
                    });
                    filesArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamFilesArr
                    });
                }
            });
            //--------------------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreTeamsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#restoreFilesForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreFilesModal').modal('hide');
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
            return false;
        }
        //---------------------------------------------------//
        function exportFiles() {
            event.preventDefault()
            let files = $('#exportFilesForm .mailboxCheck:checked');
            let filesArr = [];
            //-----------------------------------------//
            files.each(function() {
                let tr = $(this).closest('tr');
                if (filesArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamFiles = $('#exportFilesForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamFilesArr = [];
                    teamFiles.each(function() {
                        tr = $(this).closest('tr');
                        teamFilesArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
                        })
                    });
                    filesArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamFilesArr
                    });
                }
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportTeamsFiles') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportFilesForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&files=" + JSON.stringify(filesArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportFilesModal').modal('hide');
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
            return false;
        }
        //---------------------------------------------------//
        function exportPosts() {
            event.preventDefault()
            let posts = $('#exportPostsForm .mailboxCheck:checked');
            let postsArr = [];
            //-----------------------------------------//
            posts.each(function() {
                let tr = $(this).closest('tr');
                if (postsArr.filter(e => e.teamId === $(this).attr("data-teamId") && e.channelId === $(this).attr(
                        "data-channelId")).length == 0) {
                    let teamPosts = $('#exportPostsForm .mailboxCheck:checked[data-teamId="' + $(this).attr(
                        "data-teamId") + '"][data-channelId="' + $(this).attr("data-channelId") + '"]');
                    let teamPostsArr = [];
                    teamPosts.each(function() {
                        tr = $(this).closest('tr');
                        teamPostsArr.push({
                            "id": $(this).val(),
                            "teamName": tr.find('td:nth-child(4)').html(),
                            "name": tr.find('td:nth-child(2)').html(),
                            "channelName": tr.find('td:nth-child(3)').html(),
                            "channelId": $(this).attr("data-channelid"),
                            "teamId": $(this).attr("data-teamId"),
                        })
                    });
                    postsArr.push({
                        "teamId": $(this).attr("data-teamId"),
                        "teamName": tr.find('td:nth-child(4)').html(),
                        "channelName": tr.find('td:nth-child(3)').html(),
                        "channelId": $(this).attr("data-channelid"),
                        "items": teamPostsArr
                    });
                }
            });
            //-----------------------------------------//
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('exportTeamsPosts') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#exportPostsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&posts=" + JSON.stringify(postsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#exportPostsModal').modal('hide');
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
            return false;
        }
        //---------------------------------------------------//
        function restoreTabs() {
            event.preventDefault()
            let tabs = $('#restoreTabsForm .mailboxCheck:checked');
            let data = $('#restoreTabsForm').serialize();
            data += "&restoreMissingItems=" + $("#restoreTabsForm [name='restoreMissingItems']")[0].checked;
            data += "&restoreChangedItems=" + $("#restoreTabsForm [name='restoreChangedItems']")[0].checked;
            let tabsArr = [];
            tabs.each(function() {
                let tr = $(this).closest('tr');
                tabsArr.push({
                    id: $(this).val().trim(),
                    teamId: tr.find('.teamId').val(),
                    channelId: tr.find('.channelId').val(),
                    name: tr.find('td:nth-child(2)').html(),
                    channelName: tr.find('td:nth-child(3)').html(),
                    teamName: tr.find('td:nth-child(4)').html(),
                });
            });
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('restoreTeamsTabs') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: data + '&' +
                    "_token={{ csrf_token() }}&" +
                    "jobId=" + jobId +
                    "&restoreJobName=" + $("#restoreTabsForm").find('[name="restoreJobName"]').val() +
                    "&jobTime=" + jobTime +
                    "&jobType=" + jobType +
                    "&showDeleted=" + showDeleted +
                    "&showVersions=" + showVersions +
                    "&tabs=" + JSON.stringify(tabsArr),
                success: function(data) {
                    $(".spinner_parent").css("display", "none");
                    $('body').removeClass('removeScroll');
                    showSuccessMessage(data.message);
                    $('#restoreTabsModal').modal('hide');
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
            return false;
        }
        //---------------------------------------------------//
        function downloadSingleFile() {
            let tr = $('tr.current');
            let item = tr.find('input.singleItemCheck').val();
            let pointTeamId = tr.find('.teamId').val();
            let channelId = tr.find('.channelId').val();
            let teamName = tr.find('.teamTitle').val();
            let channelName = tr.find('.channelTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadTeamsFile') }}",
                beforeSend: function(request) {
                    request.setRequestHeader("fromHistory", true);
                    request.setRequestHeader("ediscoveryId", "{{ $data['job']->id }}");
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    jobId: jobId,
                    jobType: jobType,
                    showDeleted: showDeleted,
                    showVersions: showVersions,
                    teamId: pointTeamId,
                    channelId: channelId,
                    teamName: teamName,
                    channelName: channelName,
                    fileSize: fileSize,
                    fileId: item,
                    name: tr.find('.nameColumn').html(),
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
        function downloadSinglePost() {
            let tr = $('tr.current');
            let item = tr.find('input.singleItemCheck').val();
            let pointTeamId = tr.find('.teamId').val();
            let channelId = tr.find('.channelId').val();
            let teamName = tr.find('.teamTitle').val();
            let channelName = tr.find('.channelTitle').val();
            let fileSize = tr.find('.fileSizeColumn').html();
            $(".spinner_parent").css("display", "block");
            $('body').addClass('removeScroll');
            $.ajax({
                type: "POST",
                url: "{{ url('downloadTeamsPost') }}",
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
                    teamId: pointTeamId,
                    fileSize: fileSize,
                    postId: item,
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
        //----------------------------------------------------//
    </script>
@endsection
