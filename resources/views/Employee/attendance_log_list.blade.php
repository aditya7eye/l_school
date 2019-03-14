<table class="center-aligned-table table table-bordered" id="example">
    <thead style="background-color: #34BF9B;">
    <tr>
        <th class="border-bottom-0" style="color:white;">Employee Code</th>
        <th class="border-bottom-0" style="color:white;">Log Date</th>
        <th class="border-bottom-0" style="color:white;">Check In/Out</th>
    </tr>
    </thead>

    <tbody>
    @if(count($devicelogs) > 0)
        @foreach ($devicelogs as $index => $attendanc)
            <tr>
                <td>{{ isset($attendanc->UserId)?$attendanc->UserId :'' }}</td>
                <td>{{date_format(date_create($attendanc->LogDate), "d-M-Y h:i A")}}</td>
                <td>{{ isset($attendanc->C1)?$attendanc->C1 :'-' }}</td>

            </tr>
        @endforeach
    @else
        <tr>
            <td align="center" colspan="5">< No Record Found ></td>
        </tr>
    @endif
    </tbody>
</table>