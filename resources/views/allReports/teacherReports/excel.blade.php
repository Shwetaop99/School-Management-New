<table>
    <tr>
        <th colspan="2">TEACHER REPORT</th>
    </tr>

    <tr>
        <td><strong>Teacher ID</strong></td>
        <td>{{ $teacher->teacher_id }}</td>
    </tr>

    <tr>
        <td><strong>First Name</strong></td>
        <td>{{ $teacher->first_name }}</td>
    </tr>

    <tr>
        <td><strong>Last Name</strong></td>
        <td>{{ $teacher->last_name }}</td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td>{{ $teacher->email }}</td>
    </tr>

    <tr>
        <td><strong>Phone</strong></td>
        <td>{{ $teacher->phone }}</td>
    </tr>

    <tr>
        <td><strong>Date of Birth</strong></td>
        <td>
            {{ $teacher->date_of_birth ? $teacher->date_of_birth->format('d-m-Y') : 'N/A' }}
        </td>
    </tr>

    <tr>
        <td><strong>Gender</strong></td>
        <td>{{ $teacher->gender ?? 'N/A' }}</td>
    </tr>

    <tr>
        <td><strong>Qualification</strong></td>
        <td>{{ $teacher->qualification ?? 'N/A' }}</td>
    </tr>

    <tr>
        <td><strong>Joining Date</strong></td>
        <td>
            {{ $teacher->joining_date ? $teacher->joining_date->format('d-m-Y') : 'N/A' }}
        </td>
    </tr>

    <tr>
        <td><strong>Status</strong></td>
        <td>{{ $teacher->status }}</td>
    </tr>

    <tr>
        <td><strong>Address</strong></td>
        <td>{{ $teacher->address ?? 'N/A' }}</td>
    </tr>
</table>