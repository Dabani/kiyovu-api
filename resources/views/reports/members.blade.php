@extends('layouts.report')

@section('report_title', 'Membership Registry Report')

@section('content')
<table class="report">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>National ID</th>
            <th>Category</th>
            <th>Fee Tier</th>
            <th>Status</th>
            <th>Application Date</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
        <tr>
            <td>{{ $member->full_name }}</td>
            <td>{{ $member->national_id }}</td>
            <td>{{ $member->category?->label_en }}</td>
            <td>{{ $member->feeTier?->label_en }}</td>
            <td>{{ $member->status?->label_en }}</td>
            <td>{{ $member->application_date?->format('d M Y') }}</td>
            <td>{{ $member->phone }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<p style="margin-top: 10px; font-size: 9px; color: #777;">Total records: {{ $members->count() }}</p>
@endsection
