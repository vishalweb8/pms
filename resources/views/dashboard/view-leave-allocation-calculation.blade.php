<table class="table table-bordered">
    <tbody>
        <tr>
            <td>Joining Date</td>
            <td>{{ !empty($data['join_date']) ? $data['join_date']->format('d-m-Y') : '' }}</td>
        </tr>
        <tr>
            <td>Leave Allocation Start date</td>
            <td>{{ !empty($data['leave_allocation_date']) ? $data['leave_allocation_date']->format('d-m-Y') : '' }}</td>
       </tr>
        <tr>
            <td>Current leave allocation start from</td>
            <td>{{ !empty($data['after_allocation_date']) ? $data['after_allocation_date']->format('d-m-Y') : '' }}</td>
        </tr>
        <tr>
            <td>Current leave allocation end at</td>
            <td>{{ !empty($data['end_allocation_date']) ? $data['end_allocation_date']->format('d-m-Y') : '' }}</td>
        </tr>
        <tr>
            <td>Months</td>
            <td>{{ !empty($data['months']) ? $data['months'] : 0 }}</td>
        </tr>
        <tr>
            <td>Days</td>
            <td>{{ !empty($data['days']) ? $data['days'] : 0 }}</td>
        </tr>
        <tr>
            <td>Total month leave counter</td>
            <td>{{ !empty($data['month_leave_count']) ? $data['month_leave_count'] : 0 }}</td>
        </tr>
        <tr>
            <td>Total days leave counter</td>
            <td>{{ !empty($data['remaining_days_leave_count']) ? $data['remaining_days_leave_count'] : 0 }}</td>
        </tr>
        <tr>
            <td>Total leave count</td>
            <td>{{ !empty($data['leave_to_be_allocated']) ? $data['leave_to_be_allocated'] : 0 }}</td>
        </tr>
        <tr>
            <td>This year birth date</td>
            <td>{{ !empty($data['birth_date']) ? $data['birth_date']->format('d-m-Y') : '' }}</td>
        </tr>
        <tr>
            <td>This year anniversary date</td>
            <td>{{ !empty($data['anniversary_date']) ? $data['anniversary_date']->format('d-m-Y') : '' }}</td>
        </tr>
        <tr>
            <td>Final allocation of leave</td>
            <td>{{ !empty($data['yearly_allocated_leaves']) ? $data['yearly_allocated_leaves'] : 0 }}</td>
        </tr>
    </tbody>
</table>
