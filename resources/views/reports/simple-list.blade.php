@extends('layouts.report')

@section('report_title', $reportTitle)

@section('content')
<table class="report">
    <thead>
        <tr>
            @foreach ($columns as $label => $accessor)
                <th>{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
        <tr>
            @foreach ($columns as $accessor)
                <td>{{ data_get($row, $accessor) }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
<p style="margin-top: 10px; font-size: 9px; color: #777;">Total records: {{ $rows->count() }}</p>
@endsection
