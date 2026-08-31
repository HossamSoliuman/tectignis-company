@extends('admin.portal.tenders.workspace')

@section('tab')
    @include('admin.portal.tenders.tabs._preparation', [
        'heading' => 'Commercial Bid',
        'blurb' => 'The BOQ, price schedule and financial paperwork that make up the commercial envelope. Check the figures against the estimated value and the EMD before anything is uploaded.',
    ])
@endsection
