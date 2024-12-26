<table class="table-auto w-full">
    <thead>
        <tr>
            <!-- Loop through the columns to display table headers -->
            @foreach($columns as $column)
                <th class="px-4 py-2 text-left">{{ ucfirst($column) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <!-- Loop through the rows to display each schedule's data -->
        @foreach($rows as $row)
            <tr>
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($row['date'])->format('l, F j, Y') }}</td> <!-- Format the date -->
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($row['start_time'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($row['end_time'])->format('h:i A') }}</td> <!-- Format start and end time --
            </tr>
        @endforeach
    </tbody>
</table>