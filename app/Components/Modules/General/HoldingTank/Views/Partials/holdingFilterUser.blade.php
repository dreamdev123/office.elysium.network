
    <table class="table table-striped table-bordered table-hover dataTable dtr-inline reporttable" role="grid"
           aria-describedby="sample_1_info" style="width: 1028px; margin-right: auto; margin-left: auto;">
        <thead>
        <tr>
            <th> {{ _mt($moduleId, 'HoldingTank.username') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.firstname') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.lastname') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.current_position') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.total_left') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.total_right') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.total_left_carry') }}</th>
            <th> {{ _mt($moduleId, 'HoldingTank.total_right_carry') }}</th>
        </tr>
        </thead>
        <tbody>
        
            <tr>
                <td>{{ $user->username }}</td>
                <td>{{ $user->metaData->firstname }}</td>
                <td>{{ $user->metaData->lastname }}</td>
                <td>
                    @if(isset($position))
                        {{ ($position == 1) ? 'Left' : 'Right' }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $binaryInfo['leftpoints'] ?:0 }}</td>
                <td>{{ $binaryInfo['rightpoints'] ?:0 }}</td>
                <td>{{ $binaryInfo['leftCarry'] ?:0 }}</td>
                <td>{{ $binaryInfo['rightCarry'] ?:0 }}</td>
            </tr>
        </tbody>
    </table>
