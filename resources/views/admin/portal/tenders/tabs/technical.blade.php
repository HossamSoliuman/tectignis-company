@extends('admin.portal.tenders.workspace')

@section('tab')
    @include('admin.portal.tenders.tabs._preparation', [
        'heading' => 'Technical Bid',
        'blurb' => 'The compliance matrix, datasheets and OEM paperwork that make up the technical envelope. Nothing priced belongs here — a price sheet in the technical bid disqualifies the whole submission.',
    ])
@endsection
