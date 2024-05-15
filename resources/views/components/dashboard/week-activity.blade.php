<div class="card">
    <div class="card-header">
        Weekly Report (Count activity per day)
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                       <td>Name</td>
                       @php
                        $start = new DateTime('this week Monday');
                        $startFormatted = $start->format('Y-m-d');    
                       @endphp
                          <td class="text-center">{{ (new DateTime('this week Monday'))->modify('this week')->format('F j (l)') }}</td>
                       @foreach (range(1, 5) as $i)
                          <td class="text-center">{{ (new DateTime($startFormatted))->modify('+'.$i.' days')->format('F j (l)') }}</td>
                       @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataWeekTable->toArray() as $key => $item)
                        <tr>
                            <td>{{ $key }}</td>
                            @foreach (range(0, 5) as $i)
                            <td class="text-center" class="bg-dark {{ $item[(new DateTime($startFormatted))->modify('+'.$i.' days')->format('Y-m-d')] ?? '' }}">{{ $item[(new DateTime($startFormatted))->modify('+'.$i.' days')->format('Y-m-d')] ?? '-' }}</td>
                                {{-- <td>{{ (new DateTime($startFormatted))->modify('+'.$i.' days')->format('Y-m-d') }}</td> --}}
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
