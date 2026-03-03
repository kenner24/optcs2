<table class="table table-striped">
    <tbody>
        <tr>
            <td>Owner Name</td>
            <td>{{ ucfirst($company->name) }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $company->email }}</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td>{{ ucfirst($company->company_name) }}</td>
        </tr>
        <tr>
            <td>Work Email</td>
            <td>{{ $company->work_email }}</td>
        </tr>
        <tr>
            <td>Total Emp.</td>
            <td>{{ $company->total_employees }}</td>
        </tr>
        <tr>
            <td>Domain Sector</td>
            <td>{{ ucfirst($company->domain_sector) }}</td>
        </tr>
        <tr>
            <td>Username</td>
            <td>{{ ucfirst($company->username) }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{ ucfirst($company->status->value) }}</td>
        </tr>
        <tr>
            <td>Image</td>
            <td>
                <a href="{{ $company->image }}" target="blank">
                <img src="{{ $company->image }}" alt="image" height="100" width="100">
                </a>
            </td>
        </tr>
    </tbody>
</table>
