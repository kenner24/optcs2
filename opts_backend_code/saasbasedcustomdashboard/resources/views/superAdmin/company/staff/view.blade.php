<table class="table table-striped">
    <tbody>
        <tr>
            <td>Name</td>
            <td>{{ ucfirst($staff->name) }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $staff->email }}</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>{{ ucfirst($staff->staffCompanyDetails->company_name) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Domain Sector</td>
            <td>{{ ucfirst($staff->staffCompanyDetails->domain_sector) ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Username</td>
            <td>{{ ucfirst($staff->username) }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{ ucfirst($staff->status->value) }}</td>
        </tr>
        <tr>
            <td>Image</td>
            <td>
                <a href="{{ $staff->image }}" target="blank">
                    <img src="{{ $staff->image }}" alt="image" height="100" width="100">
                </a>
            </td>
        </tr>
    </tbody>
</table>
