<div class="card">
    <div class="card-body">
        <h4 class="mt-0 header-title mb-4">Today Activity - {{ date("F d Y")}} </h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Warehouse</th>
                        <th scope="col">Success</th>
                        <th scope="col">Failed</th>
                        <th scope="col">Pending/ No Status</th>
                        <th scope="col">Total Activity Today</th>
                        <th scope="col">Progress</th>
                        <th scope="col" class="text-center">OverAll</th>
                    </tr>
                </thead>
                <tbody>
                  
                    @foreach (array_merge(...array_values($dataTable->toArray())) as $name => $value)
                    <tr @if ($value['nostatus_count']>0) style="background-color: #f8d7da" @endif>
                        <th scope="row">{{ $name }}</th>
                        <td>{{ $value['user']['wrhs'] }}</td>
                        <td>{{ $value['success_count'] }}</td>
                        <td>{{ $value['failed_count'] }}</td>
                        <td>
                            @if ($value['nostatus_count']>0)
                            <span class="badge badge-danger">{{ $value['nostatus_count'] }}</span>
                            @else
                                {{ $value['nostatus_count'] }}
                            @endif
                        </td>
                        <td>{{ $value['activity_count'] }}</td>
                        <td>
                            <div class="progress" style="height: 5px;">
                                @php
                                    $successPercentage = $value['activity_count'] > 0 ? ($value['success_count'] / $value['activity_count']) * 100 : 0;
                                @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $successPercentage }}%" aria-valuenow="{{ $successPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($value['nostatus_count']>0)
                            <i class="fas fa-times-circle text-danger" style="font-size:18px"></i>
                            @else
                            <i class="fas fa-check-circle text-success" style="font-size:18px"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>