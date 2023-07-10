@if ($type == 'string')
    <input placeholder="Value" type="text" name="value" required
        class="value condition_value form_input form-control" />
    <span class="fa fa-info-circle value-info txt-blue" data-toggle="tooltip" title="{{ __('variables.alerts.contains_criteria_description') }}"></span>
@elseif($type == 'list')
    <select class="form_input form-control font-size valueList value" name="value" required>
        @foreach ($values as $item)
            <option value="{{ $item }}">
                {{ $item }}</option>
        @endforeach
    </select>
@elseif($type == 'date')
    @if ($condition == 'Between')
        <input placeholder="From" type="text" name="value" required
            class="value date condition_value form_input form-control mr-10" />
        <input placeholder="To" type="text" name="sec_value" required
            class="sec_value date condition_value form_input form-control ml-2" />
    @elseif($static)
        <input placeholder="Value" value="{{ $condition }}" type="text" disabled name="value"
            class="value date condition_value form_input form-control" />
    @else
        <input placeholder="Value" type="text" name="value" required
            class="value date condition_value form_input form-control" />
    @endif
@endif
